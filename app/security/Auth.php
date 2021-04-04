<?php


class Auth
{
    private const SESSION_NAME = 'RT';
    private const PASSWORD_BCRYPT_COST = 12;

    public static function register()
    {

    }

    public static function login()
    {

    }

    public static function logout()
    {

    }

    public static function remember_me()
    {

    }

    public static function check(): bool
    {
        return isset($_SESSION['user']) && !empty($_SESSION['user']) && $_SESSION['user']['logged'];
    }
}
