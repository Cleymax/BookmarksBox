<?php

namespace App\Exceptions;

class MissingAccessException extends \Exception
{
    public function __construct($message = "Tu n'as pas la permission !")
    {
        parent::__construct($message, 403);
    }
}
