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
     */
    public static function success(string $message)
    {
        self::add('success', $message);
    }

    /**
     * Add a information message.
     * @param string $message
     */
    public static function info(string $message)
    {
        self::add('info', $message);
    }

    /**
     * Add a error message.
     * @param string $message
     */
    public static function error(string $message)
    {
        self::add('error', $message);
    }

    /**
     * Add a message with a custom type.
     * @param string $type
     * @param string $message
     */
    public static function add(string $type, string $message): void
    {
        $_SESSION['flash'][$type] = $message;
    }

    /**
     * Test if a flash message exist with his type.
     * @param string $type
     * @return bool
     */
    public static function has(string $type = ''): bool
    {
        if ($type == '') {
            return !empty($_SESSION['flash']);
        } else {
            return isset($_SESSION['flash'][$type]);
        }
    }

    /**
     * Call when a request is called.
     */
    public static function onRequest(): void
    {
        unset($_SESSION['flash']);
    }

    /**
     * Get a flash message.
     * @param string $type
     * @return array|string
     */
    public static function get(string $type = '')
    {
        return $type == '' ? $_SESSION['flash'] : $_SESSION['flash'][$type];
    }
}
