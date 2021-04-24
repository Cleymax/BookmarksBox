<?php

namespace App\Services;


use App\Tools\Str;

class CsrfService
{
    public static function generate(): string
    {
        $timestamp = time();
        $token = self::generateToken();
        $signature = self::generateSignature($timestamp, $token);
        return base64_encode("$timestamp:$token:$signature");
    }

    private static function generateToken(): string
    {
        return Str::random(10);
    }

    private static function generateSignature(int $timestamp, string $token): string
    {
        if (!is_numeric($timestamp)) {
            throw new \InvalidArgumentException('$timestamp must be an integer');
        }

        $timestamp = (int)$timestamp;
        return base64_encode(hash_hmac("sha256", json_encode(compact('timestamp', 'token')), $_ENV['SALT']));
    }

    public static function verify(string $token): bool
    {
        $token = base64_decode($token);
        $args = explode(':', $token);
        if (sizeof($args) != 3) {
            return false;
        }
        list($timestamp, $token, $signature) = $args;

        if ($timestamp < time() - $_ENV['CSRF_TIME']) {
            return false;
        }
        return self::generateSignature($timestamp, $token) == $signature;
    }

    public static function html(): string
    {
        echo "<input type='hidden' name='_csrf_token' value='" . self::generate() . "'>";
    }
}
