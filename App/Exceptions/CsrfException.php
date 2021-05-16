<?php

namespace App\Exceptions;

class CsrfException extends \Exception
{

    /**
     * CsrfException constructor.
     */
    public function __construct()
    {
        parent::__construct('Requête non idéntifié. Veuillez réessayer.',400);
    }
}
