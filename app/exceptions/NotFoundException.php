<?php

namespace App\Exceptions;

class NotFoundException extends \Exception
{

    /**
     * NotFoundException constructor.
     */
    public function __construct(string $message)
    {
        parent::__construct($message, 400);
    }
}
