<?php

namespace App\Exceptions;

class UserNotFoundException extends NotFoundException
{

    /**
     * UserNotFoundException constructor.
     */
    public function __construct()
    {
        parent::__construct("Utilisateur non trouvé !");
    }
}
