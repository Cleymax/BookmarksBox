<?php

namespace App\Services;

/**
 * Class FlashService
 * @package app\Services
 * @author Clément PERRIN <clement.perrin@etu.univ-smb.fr>
 */
class FlashService
{

    /**
     * Add a success message.
     * @param string $message
     * @param int|null $duration
     */
    public static function success(string $message, int $duration = null): void
    {
        self::add('success', $message, $duration);
    }

    /**
     * Add a information message.
     * @param string $message
     * @param int|null $duration
     */
    public static function info(string $message, int $duration = null): void
    {
        self::add('info', $message, $duration);
    }

    /**
     * Add a error message.
     * @param string $message
     * @param int|null $duration
     */
    public static function error(string $message, int $duration = null): void
    {
        self::add('error', $message, $duration);
    }

    /**
     * Add a message with a custom type.
     * @param string $type
     * @param string $message
     * @param int|null $duration
     */
    public static function add(string $type, string $message, ?int $duration): void
    {
        $message = [
            'text' => $message,
            'type' => $type,
            'duration' => $duration ?? 'none'
        ];
        $_SESSION['flash'][] = $message;
    }

    /**
     * Test if a flash message
     * @return bool
     */
    public static function has(): bool
    {
        return isset($_SESSION['flash']);
    }

    /**
     * Get the flash messages.
     * @return array
     */
    public static function get(): array
    {
        return $_SESSION['flash'];
    }

    /**
     * Call when a request is receive.
     */
    public static function request(): void
    {
        unset($_SESSION['flash']);
    }
}
