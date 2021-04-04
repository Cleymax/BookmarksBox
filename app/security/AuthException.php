<?php


class AuthException extends Exception
{

    /**
     * AuthException constructor.
     * @param string $message
     * @param int $code
     */
    public function __construct(string $message = "", int $code = 0)
    {
        parent::__construct($message, $code);
    }
}
