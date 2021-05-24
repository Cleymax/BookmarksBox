<?php

namespace App\Exceptions;

class MissingAccessException extends \Exception
{
    public function __construct($message = "")
    {
        parent::__construct($message, 403);
    }
}
