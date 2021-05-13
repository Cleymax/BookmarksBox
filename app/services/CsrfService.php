<?php

namespace App\Services;

use App\Tools\Str;

/**
 * Class CsrfService
 * @package App\Services
 * @author Clément PERRIN <clement.perrin@etu.univ-smb.fr>
 */
class CsrfService
{
    /**
     * Generate a new CSRF token.
     * @return string the token
     */
    public static function generate(): string
    {
        $timestamp = time();
        $token = self::generateToken();
        $signature = self::generateSignature($timestamp, $token);
        return base64_encode("$timestamp:$token:$signature");
    }

    /**
     * Generate a token.
     * @return string
     */
    private static function generateToken(): string
    {
        return Str::random(10);
    }

    /**
     * Generate the signature for the csrf token.
     * @param int $timestamp
     * @param string $token
     * @return string
     */
    private static function generateSignature(int $timestamp, string $token): string
    {
        if (!is_numeric($timestamp)) {
            throw new \InvalidArgumentException('$timestamp must be an integer');
        }

        $timestamp = (int)$timestamp;
        return base64_encode(hash_hmac("sha256", json_encode(compact('timestamp', 'token')), $_ENV['SALT']));
    }

    /**
     * Verify a CSRF token.
     * @param string $token
     * @return bool if it's a success
     */
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

    /**
     * Generate the html code for the csrf token input in form.
     * @return string
     */
    public static function html(): string
    {
        return "<input type='hidden' name='_csrf_token' value='" . self::generate() . "'>";
    }
}
