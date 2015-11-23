<?php namespace App\Exceptions;

class ValidationException extends \Exception {

    function __construct($message = 'Validation error')

    {
        $this->message = $message;
    }
    
}
