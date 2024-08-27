<?php

use App\Middleware\AuthMiddleware;
use App\Middleware\RateLimiter;

return [
    'api' => [
        'routes' => [
            '/profile' => ['get', 'HomeController@index', 'middleware' => [AuthMiddleware::class, RateLimiter::class]],
            '/change-password' => ['post', 'HomeController@password'],
        ],
    ],
];
