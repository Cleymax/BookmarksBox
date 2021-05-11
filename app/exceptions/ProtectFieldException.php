<?php

namespace App\Exceptions;

class ProtectFieldException extends \Exception
{
    public function __construct($params)
    {
        parent::__construct("Can't use params '$params' !", 400);
    }
}
