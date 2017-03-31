<?php

namespace Ordent\RamenResource\Response;

class ResponseFactory
{
    //create response for single item resource
    public function resourceItem($data = [], $status = 200, array $meta = [], $headers = []){
        return new ItemResponse(...func_get_args());
    }

    //create response for collection of resource
    public function resourceCollection($data = [], $status = 200, array $meta = [], $headers = []){
        return new CollectionResponse(...func_get_args());
    }

//error factory
//===========================================================================

    // 400 bad request. general request error
    public function errorBadRequest($message = 'Bad request'){
        $this->error(400, $message);
    }

    // 401 unauthorized. auth failed error
    public function errorUnauthorized($message = 'Unauthorized'){
        $this->error(401, $message);
    }

    // 403 forbidden error
    public function errorForbidden($message = 'Forbidden'){
        $this->error(403, $message);
    }

    // 404 not found
    public function errorNotFound($message = 'Resource not found'){
        $this->error(404, $message);
    }

    // UNUSED FOR NOW
    // 405 method not allowed error
    // public function errorMethodNotAllowed($message = 'Method Not Allowed'){
    //     $this->error($message, 405);
    // }

    // 422 validation error
    public function errorValidation($errors = null, $message = 'Validation failed'){
        $this->error(422, $message, $errors);
    }

    // 500 internal error. general system error
    public function errorInternal($message = 'Internal error'){
        $this->error(500, $message);
    }

    //general error
    public function error($statusCode = 500, $message = null, $detail = null){

        //create response content in array format
        $content['status'] = $statusCode;
        $content['message'] = $message;
        if ( $detail ){
            $content['detail'] = $detail;
        }

        //create json response and throw it
        response()->json($content, $statusCode)->throwResponse();
    }
}