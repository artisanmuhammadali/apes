<?php

namespace Core;

use Core\Database;

class Model {
    protected static $table;
    protected static $hidden = [];

    public static function setTable($table) {
        static::$table = $table;
    }

    protected static function getTable() {
        if (empty(static::$table)) {
            static::$table = strtolower((new \ReflectionClass(static::class))->getShortName()) . 's';
        }
        return static::$table;
    }

    public static function all() {
        $results = Database::run("SELECT * FROM ".self::getTable());
        $results = static::filterAllResults($results);
        return static::createObjects($results);

    }

    public static function find($id) {
        $results = Database::run("SELECT * FROM ".self::getTable()." WHERE id = ?", [$id]);
        $results = static::filterAllResults($results);
        return $results ? static::createObject($results[0]) : null;
    }

    public static function where($column, $value) {
        $results = Database::run("SELECT * FROM ".self::getTable()." WHERE $column = ?", [$value]);
        $results = static::filterAllResults($results);
        return static::createObjects($results);
    }

    public static function insert($data) {
        $keys = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $values = array_values($data);

        $sql = "INSERT INTO ".self::getTable()." ($keys) VALUES ($placeholders)";
        $results = Database::run($sql, $values);
        $results = static::filterAllResults($results);
        return static::createObjects($results);
    }

    public static function update($id, $data) {
        $set = implode(', ', array_map(fn($key) => "$key = ?", array_keys($data)));
        $values = array_values($data);
        $values[] = $id;

        $sql = "UPDATE ".self::getTable()." SET $set WHERE id = ?";
        $results = Database::run($sql, $values);
        $results = static::filterAllResults($results);
        return static::createObjects($results);
    }

    public static function delete($id) {
        $results = Database::run("DELETE FROM ".self::getTable()." WHERE id = ?", [$id]);
        $results = static::filterAllResults($results);
        return static::createObjects($results);
    }

    protected static function filterAllResults($rows) {
        $filteredResults = [];
        foreach ($rows as $row) {
            $filteredResults[] = static::filterSensitiveFields($row);
        }
        return $filteredResults;
    }
    protected static function filterSensitiveFields($row) {
        return array_diff_key($row, array_flip(static::$hidden));
    }
    protected static function createObject($data) {
        $className = static::class;
        $object = new $className();

        foreach ($data as $key => $value) {
            if (!in_array($key, static::$hidden)) {
                $object->$key = $value;
            }
        }

        return $object;
    }

    protected static function createObjects($rows) {
        $objects = [];
        foreach ($rows as $row) {
            $objects[] = static::createObject($row);
        }
        return $objects;
    }
}
