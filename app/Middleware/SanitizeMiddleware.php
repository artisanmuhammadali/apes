<?php

namespace App\Middleware;

use Core\Utils\Sanitizer;

class SanitizeMiddleware {
    public function handle($request, $next) {
        $_GET = Sanitizer::sanitize($_GET);
        $_POST = Sanitizer::sanitize($_POST);
        $_REQUEST = Sanitizer::sanitize($_REQUEST);
        $_COOKIE = Sanitizer::sanitize($_COOKIE);

        return $next($request);
    }
}
