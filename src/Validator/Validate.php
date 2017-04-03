<?php

namespace Ordent\RamenResource\Validator;

use InvalidArgumentException;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Http\Request;

class Validate
{
    protected $ruleSet;

    //__construct and set rulesSet if any
    public function __construct($ruleSet = null){

        //set rules if provided, or get from config
        $this->setRules($ruleSet ?: (array) config('validationRules'));
    }

    //get validated data from $container
    public function __invoke($data, $rules, array $messages = [], array $customAttributes = []){

        //if data is request, extract all input from it
        if ($data instanceof Request){
            $data = $data->all();
        }

        //if $rules is string, we assume it is rulekey and retrieve actual rules from $ruleSet using it
        if (is_string($rules)){
            $rules = array_get($this->ruleSet, $rules);
        }

        //if there's no rules, just return the data without validation
        if (!$rules){
            return $data;
        }

        //validate $data with given $rules, throw error if fails
        $validator = Factory::make($data, $rules, $messages, $customAttributes);
        if ($validator->fails()){
            response()->errorValidation($validator->errors()->all());
        }

        //return data
        return $data;
    }

    //create new Validate object
    static public function make($ruleSet = null)){
        return new static($ruleSet);
    }

    //set ruleSet
    public function setRules($rules){

        //if $rules is object we try to extract the rules from it
        if (is_object($rules) && method_exists($rules, 'validationRules')){
            $rules = $rules->validationRules();
        }

        //if $rules is not array throw error
        if (!is_array($rules)){
            throw new InvalidArgumentException('rules must be array or object with method "validationRules"');
        }

        $this->rulesSet = $rules;
        return $this;
    }
}