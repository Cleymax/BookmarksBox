<?php
namespace App\Security;

/**
 * Class AuthException
 * @package App\Security
 */
class AuthException extends \Exception
{

    /**
     * AuthException constructor.
     * @param string $message
     * @param int $code
     */
    public function __construct(string $message = "", int $code = 400)
    {
        parent::__construct($message, $code);
    }
}
