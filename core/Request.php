<?php

namespace Core;

class Request {
    public static function capture() {
        return [
            'method' => strtolower($_SERVER['REQUEST_METHOD']),
            'uri' => parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),
            'params' => array_merge($_GET, $_POST, json_decode(file_get_contents('php://input'), true) ?? [])
        ];
    }
}
