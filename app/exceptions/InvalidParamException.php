<?php


namespace App\Exceptions;


class InvalidParamException extends \Exception
{

    /**
     * InvalidParamException constructor.
     */
    public function __construct($for, $need)
    {
        parent::__construct("Invalid value of parameters for '$for', need to be '$need' !");
    }
}
