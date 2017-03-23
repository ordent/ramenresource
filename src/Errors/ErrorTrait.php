<?php

namespace Ordent\RamenResource\Errors;

trait ErrorTrait
{

//throw error function
//===========================================================================

    // 401 unauthorized. auth failed error
    public function errorUnauthorized($message = 'Unauthorized'){
        $this->error($message, 401);
    }

    // 404 not found
    public function errorNotFound($message = 'Resource not found'){
        $this->error($message, 404);
    }

    // 403 forbidden error
    public function errorForbidden($message = 'Forbidden'){
        $this->error($message, 403);
    }

    // 422 validation error
    public function errorValidation($errors = null, $message = 'Validation failed'){
        $this->error($message, 422, $errors);
    }

    // 500 internal error. general system error
    public function errorInternal($message = 'Internal error'){
        $this->error($message, 500);
    }

    // 400 bad request. general request error
    public function errorBadRequest($message = 'Bad request'){
        $this->error($message, 400);
    }

    // UNUSED FOR NOW
    // 405 method not allowed error
    // public function errorMethodNotAllowed($message = 'Method Not Allowed'){
    //     $this->error($message, 405);
    // }

//general error function
//===========================================================================

    public function error($message, $statusCode = 500, $errors = null){

        //create response content in array format
        $content['message'] = $message;
        if ( $errors ){
            $content['errors'] = $errors;
        }

        //create json response and throw it
        response()->json($content, $statusCode)->throwResponse();
        
        // UNUSED
        // throw new ResourceException($statusCode, $message, $errors);
    }
}
