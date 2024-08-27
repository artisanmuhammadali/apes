<?php

namespace Core\Utils;

class Sanitizer {
    public static function sanitize($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = self::sanitize($value);
            }
        } else {
            // Basic sanitization, you can add more rules as needed
            $data = self::sanitizeString($data);
        }

        return $data;
    }

    public static function sanitizeString($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        return $data;
    }

    public static function sanitizeEmail($data) {
        return filter_var($data, FILTER_SANITIZE_EMAIL);
    }

    public static function sanitizeURL($data) {
        return filter_var($data, FILTER_SANITIZE_URL);
    }

    public static function sanitizeInt($data) {
        return filter_var($data, FILTER_SANITIZE_NUMBER_INT);
    }

    public static function sanitizeFloat($data) {
        return filter_var($data, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }

    public static function sanitizeSql($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = self::sanitizeSql($value);
            }
        } else {
            // Escape special characters
            $data = addslashes($data);
        }

        return $data;
    }
}
