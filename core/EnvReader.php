<?php

namespace Core;

class EnvReader {
    private $env;

    public function __construct($filePath) {
        $this->env = parse_ini_file($filePath);
    }

    public function get($key, $default = null) {
        return $this->env[$key] ?? $default;
    }
}
