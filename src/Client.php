<?php

namespace Ordent\RamenResource;

use InvalidArgumentException;
use Illuminate\Database\Eloquent\Model;

class Client
{
    use HasHandlerTrait;

    protected $model;

//initialize class / object
//===========================================================================

    //construtor
    public function __construct($model = null){

        //resolve model and get user defined handler from it, then bind them to the client
        $this->model = $this->resolveModel($model);
        $this->setHandlerFromModel($this->model);
    }

    //create new instance for given model
    public static function newClient($model){
        return new static($model);
    }

//factory method / shortcut
//===========================================================================

    //get collection of resources
    public function index($parameters = []){
        return $this->execute('index', ...func_get_args());
    }

    //get single resource
    public function show($id, $parameters = []){
        return $this->execute('show', ...func_get_args());
    }

    //store single resource
    public function store($data, array $parameters = []){
        return $this->execute('store', ...func_get_args());
    }

    //update single resource
    public function update($id, $data, array $parameters = []){
        return $this->execute('update', ...func_get_args());
    }

    //delete single resource
    public function delete($id, $parameters = []){
        return $this->execute('delete', ...func_get_args());
    }

    //get collection related resource
    public function indexRelated(string $relation, $id, $parameters = []){
        return $this->execute('indexRelated', ...func_get_args());
    }

    //store new related resource
    public function storeRelated(string $relation, $id, $data, array $parameters = []){
        return $this->execute('storeRelated', ...func_get_args());
    }

    //execute handler
    public function execute($handler, ...$arguments){

        // if handler is not callable, resolve it first
        if ( !is_callable($handler) ){
            $handler = $this->resolveHandler($handler);
        }

        //execute $handler with $model and $arguments
        return $handler($this->model, ...$arguments);
    }

    //dynamic execute handler, especially for custom handler
    public function __call($method, $parameters = []){

        //dynamic index related
        if ( starts_with($method, 'index') ){

            //get relation name.
            //remove 'index' prefix and lowercase first letter from $method
            $relation = lcfirst(substr($method, strlen('index')));

            //call $this->indexRelated
            return $this->indexRelated($relation, ...$parameters);
        }

        //dynamic store related
        if ( starts_with($method, 'store') ){

            //generate key name.
            //remove 'store' prefix and lowercase first letter from $method
            $relation = lcfirst(substr($method, strlen('store')));

            //call $this->storeRelated
            return $this->storeRelated($relation, ...$parameters);
        }

        //call user defined handler
        return $this->execute($method, ...$parameters);
    }

//helpers
//===========================================================================

    //resolve $model
    protected function resolveModel($modelName){

        //if $modelName is string, we try instantiante it first
        if ( is_string($modelName) ){
            $model = resolve($modelName);
        }

        //if model is instance of Model, return it
        if ($model instanceOf Model) {
            return $model;
        }

        //else throw error
        throw new InvalidArgumentException('model input must be model instance or path of the model class');
    }

    //get client's model
    public function getModel(){
        return $this->model;
    }
}