<?php

namespace App\Middleware;

class RateLimiter {
    private $limit;
    private $interval;

    public function __construct($limit = 5, $interval = 3600) {
        $this->limit = $limit;
        $this->interval = $interval;
    }

    public function handle($request) {
        $ip = $_SERVER['REMOTE_ADDR'];
        $key = "rate_limit:$ip";

        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = ['count' => 0, 'timestamp' => time()];
        }

        $rateData = $_SESSION[$key];
        if (time() - $rateData['timestamp'] > $this->interval) {
            $rateData['count'] = 0;
            $rateData['timestamp'] = time();
        }

        if ($rateData['count'] >= $this->limit) {
            return false;
        }

        $rateData['count']++;
        $_SESSION[$key] = $rateData;

        return true;
    }
}
