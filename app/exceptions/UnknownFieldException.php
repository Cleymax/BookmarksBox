<?php


namespace App\Exceptions;


class UnknownFieldException extends \Exception
{

    /**
     * UnknownFieldException constructor.
     */
    public function __construct(string $field)
    {
        parent::__construct("unknown '$field' field!", 400);
    }
}
