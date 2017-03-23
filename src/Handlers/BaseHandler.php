<?php

namespace Ordent\RamenResource\Handlers;

use BadMethodCallException;
use Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Ordent\RamenResource\Errors\ErrorTrait;

class BaseHandler
{
    use ErrorTrait;

//handler helpers
//===========================================================================

    //find resource from $container
    protected function findResource($container){

        $resource = $this->findEntity($container->model, $container->id);
        
        //if resource exist, we return it. else throw error not found
        return isset($resource) ? $resource : $this->errorNotFound();
    }

    //find resource's relation from $container
    protected function findRelation($container){

        $resource = $this->findResource($container);

        //try to find the relation, catch error if fails
        try{
            $result = $resource->$relation();
        } catch (BadMethodCallException $exception){
            $result = null;
        }

        //if relation exist, we return it. else throw error not found
        return ($result instanceOf Relation) ? $result : $this->errorNotFound();
    }

    //get validated data from $container
    protected function validateData($container, $rules = []){

        //if $rules is string we assume it is rulekey.
        //then we search the actual rule from with it
        if ( is_string($rules) ){

            $ruleSet = $this->validationRules($container);
            $rules = isset($ruleSet[$rules]) ? $ruleSet[$rules] : [];
        }

        //if there's no rules, just return the data without validation
        if ( !$rules ){
            return $container->data;
        }

        //validate $container->data with given $rules, throw error 422 if data invalid
        $validator = Validator::make($container->data, $rules);
        if ($validator->fails()){
            $this->errorValidation($validator->errors()->all());
        }

        //return data
        return $container->data;
    }

    //return list of resources from query
    public function getIndex($query, $parameters = []){

        //if page or limit exist, we will paginate resource
        $limit = isset($parameters['limit']) ? (int) $parameters['limit'] : null;
        if ( isset($parameters['page']) || isset($limit) ){
            return $query->paginate($limit);
        }

        //else return collection
        return $query->get();
    }

//override-able functions
//===========================================================================

    //find resource entity
    protected function findEntity($model, $id){

        //prioriry 1 :
        // if user defined method exist, we will use it instead
        if ( method_exists($model, 'findEntity') ){
            return $model->findEntity($model, $id);
        }

        //prioriry 2 :
        //if $id is array, we use default where-first method
        if (is_array($id)){
            return $model->where($id)->first();
        }

        //prioriry 3 :
        //else, we assume the $id as table id
        return $model->find($id);
    }

    //set of validation rules
    protected function validationRules($container){

        // if user defined rules exist, we will use it instead
        if ( method_exists($container->model, 'validationRules') ){
            return $container->model->validationRules(clone $container);
        }

        //else just return empty array
        return [];
    }

    //run index filter if exist
    protected function indexFilter($query, $parameters = []){

        // get model from query
        $model = ($query instanceOf Model) ? $query : $query->getModel();

        // run index filter from model if exist, else just return the query
        return method_exists($model, 'indexFilter') ? $model->indexFilter($query, $parameters) : $query;
    }
}