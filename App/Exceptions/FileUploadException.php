<?php

namespace App\Exceptions;

class FileUploadException extends \Exception
{
    public function __construct($message = "Erreur lors de l'upload du fichier !")
    {
        parent::__construct($message, 400);
    }
}
