<?php

namespace App\Services;

use Predis\Client;
use RateLimit\PredisRateLimiter;
use RateLimit\Rate;
use RateLimit\Status;

class RateLimitService
{
    private static $instance = null;
    private static $status = null;

    public static function getRedisRateLimiter(): ?PredisRateLimiter
    {
        if (self::$instance == null) {
            self::$instance = new PredisRateLimiter(new Client("tcp://{$_ENV['REDIS_HOST']}:{$_ENV['REDIS_PORT']}", [
                "parameters" => [
                    "password" => $_ENV['REDIS_PASSWORD'],
                    "database" => $_ENV['REDIS_DATABASE'] ?? 0
                ]
            ]));
        }
        return self::$instance;
    }

    public static function getStatus(): ?Status
    {
        if (is_null(self::$status)) {
            self::$status = self::getRedisRateLimiter()->limitSilently($_SERVER['REMOTE_ADDR'], Rate::perMinute($_ENV['API_RATE_LIMIT'] ?? 60));
        }
        return self::$status;
    }
}
