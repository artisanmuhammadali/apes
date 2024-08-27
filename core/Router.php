<?php

namespace Core;

use App\Middleware\RateLimiter;

class Router {
    private $routes = [];
    private $middlewares = [];

    public function __construct($routesConfig) {
        $this->routes = $routesConfig['api']['routes'];
        $this->middlewares = [
            RateLimiter::class,
        ];
    }

    public function dispatch($request) {
        $method = $request['method'];
        $uri = $request['uri'];
        $params = $request['params'];
        $basePath = '/api';
        $uri = str_replace($basePath, '', $uri);
        foreach ($this->middlewares as $middlewareClass) {
            $middleware = new $middlewareClass();
            if (!$middleware->handle($params)) {
                http_response_code(429); // 429 Too Many Requests
                echo json_encode(['error' => 'Rate Limit Exceeded']);
                return;
            }
        }
        
        if (isset($this->routes[$uri])) {
            $route = $this->routes[$uri];
            if ($route[0] === $method) {
                $action = $route[1];
                $middlewares = $route['middleware'] ?? [];

                foreach ($middlewares as $middlewareClass) {
                    $middleware = new $middlewareClass();
                    if (!$middleware->handle($params)) {
                        http_response_code(403);
                        echo json_encode(['error' => 'Forbidden']);
                        return;
                    }
                }

                list($controller, $method) = explode('@', $action);
                $controller = "App\\Controllers\\$controller";
                $instance = new $controller();
                echo json_encode($instance->$method($params));
            } else {
                http_response_code(405);
                echo json_encode(['error' => 'Method Not Allowed']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Not Found']);
        }
    }
}
