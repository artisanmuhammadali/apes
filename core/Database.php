<?php

namespace Core;

use PDO;
use PDOException;
use Core\EnvReader;

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        $env = new EnvReader(__DIR__ . '/../.env');
        $host = $env->get('DB_HOST');
        $port = $env->get('DB_PORT');
        $dbname = $env->get('DB_DATABASE');
        $username = $env->get('DB_USERNAME');
        $password = $env->get('DB_PASSWORD');

        $dsn = "mysql:host=$host;port=$port;dbname=$dbname";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $username, $password, $options);
        } catch (PDOException $e) {
            die('Connection failed: ' . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public static function run($query, $params = []) {
        $pdo = self::getInstance()->getPdo();
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getPdo() {
        return $this->pdo;
    }
}
