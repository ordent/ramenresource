<?php

namespace Prototype\Resource\Errors;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ResourceException extends HttpException
{
    //for validation error
    protected $errors;

    public function __construct($statusCode, $message = null, $errors = null, $previous = null, $headers = [], $code = 0){

        parent::__construct($statusCode, $message, $previous, $headers, $code);
        $this->errors = is_null($errors) ? null : (array) $errors;
    }

    public function getErrors(){
        return $this->errors;
    }

    public function hasErrors(){
        return (bool) $this->errors;
    }
}