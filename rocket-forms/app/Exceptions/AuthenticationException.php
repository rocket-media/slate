<?php namespace App\Exceptions;

class AuthenticationException extends \Exception {

    function __construct($message = 'Authentication error')

    {
        $this->message = $message;
    }
    
}
