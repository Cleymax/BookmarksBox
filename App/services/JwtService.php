<?php

namespace App\Services;

use Firebase\JWT\JWT;

/**
 * Class JwtService.
 * @package App\Services
 * @see \Firebase\JWT\JWT
 * @author Clément PERRIN <clement.perrin@etu.univ-smb.fr>
 */
class JwtService
{

    /**
     * Decodes a JWT string into a PHP object.
     *
     * @param string $token The JWT
     * @return object The JWT's payload as a PHP object
     */
    public static function verify(string $token): object
    {
        return JWT::decode($token, $_ENV['APP_KEY'], [$_ENV['JWT_ALGORITHM'] ?? 'HS256']);
    }

    /**
     * Converts and signs a PHP object or array into a JWT string.
     *
     * @param array|object $payload PHP object or array
     * @return string A signed JWT
     */
    public static function create($payload): string
    {
        return JWT::encode($payload, $_ENV['APP_KEY'], $_ENV['JWT_ALGORITHM'] ?? 'HS256');
    }
}
