<?php

require_once __DIR__ . '/vendor/autoload.php';

use Core\Database;
use Core\Request;
use Core\Router;
use App\Middleware\SanitizeMiddleware;

// Load routes
$routesConfig = require_once __DIR__ . '/routes.php';

Database::getInstance()->getPdo();
$request = Request::capture();
$router = new Router($routesConfig);

// Add sanitation middleware
$sanitizeMiddleware = new SanitizeMiddleware();

// Middleware function to handle the request
$next = function($request) use ($router) {
    $router->dispatch($request);
};

// Apply sanitation middleware
$sanitizeMiddleware->handle($request, $next);