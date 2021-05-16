<?php

namespace App\Exceptions;

class TokenNotFoundException extends \Exception
{
    public function __construct(string $message = '')
    {
        parent::__construct('Token not found or invalid token !' . $message, 400);
    }
}
