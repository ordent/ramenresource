<?php

namespace Ordent\RamenResource\Validator;

trait ValidationTrait
{
    //validate using ramen validator
    public function validate($data, $rules, array $messages = [], array $customAttributes = []){

        //create new validator, set rules, execute it and return the result
        $validator = new Validate($this->validationRules());
        return $validator(...func_get_args());
    }

    //set of rules for validation
    protected function validationRules(){
        return [];
    }
}