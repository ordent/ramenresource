<?php

namespace Prototype\Resource;

use ReflectionException;
use Illuminate\Database\Eloquent\Model;
use Prototype\Resource\Errors\ErrorTrait;

class Client
{
    use HasHandlerTrait, ErrorTrait;

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
    public function index(array $parameters = []){

        $container = $this->newContainer()
            ->setParameters($parameters);

        return $this->execute('index', $container);
    }

    //get single resource
    public function show($id, array $parameters = []){

        $container = $this->newContainer()
            ->setIdentifier($id)
            ->setParameters($parameters);

        return $this->execute('show', $container);
    }

    //store single resource
    public function store(array $data, array $parameters = []){

        $container = $this->newContainer()
            ->setData($data)
            ->setParameters($parameters);

        return $this->execute('store', $container);
    }

    //update single resource
    public function update($id, array $data, array $parameters = []){
        $container = $this->newContainer()
            ->setIdentifier($id)
            ->setData($data)
            ->setParameters($parameters);

        return $this->execute('update', $container);
    }

    //delete single resource
    public function delete($id, array $parameters = []){
        $container = $this->newContainer()
            ->setIdentifier($id)
            ->setParameters($parameters);

        return $this->execute('delete', $container);
    }

    //get collection related resource
    public function indexRelated(string $relation, $id, array $parameters = []){
        $container = $this->newContainer()
            ->setIdentifier($id, $relation)
            ->setParameters($parameters);

        return $this->execute('indexRelated', $container);
    }

    //store new related resource
    public function storeRelated(string $relation, $id, array $data, array $parameters = []){
        $container = $this->newContainer()
            ->setIdentifier($id, $relation)
            ->setData($data)
            ->setParameters($parameters);

        return $this->execute('storeRelated', $container);
    }

    //dynamic execute handler, for custom handler
    public function __call($method, $parameters){

        //dynamic index related
        if ( starts_with($method, 'index') ){

            //generate key name.
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

//main function
//===========================================================================

    //execute handler
    public function execute($handler, $container, string $relation = null, array $data = [], array $parameters = []){

        // if handler is not callable, resolve it
        if ( !is_callable($handler) ){
            $handler = $this->resolveHandler($handler);
        }

        // if $container is not Container object, we assume it is $id
        // and create new Container using it and the rest of arguments
        if ( !($container instanceOf Container)){
            $container = $this->newContainer()
                ->setIdentifier($container, $relation)
                ->setData($data)
                ->setParameters($parameters);
        }

        //execute $handler with $input as argument
        return $handler($container);
    }

//helpers
//===========================================================================

    //create new empty container
    public function newContainer(){
        return new Container($this->model);
    }

    protected function resolveModel($modelName){

        //if $modelName is string, we try instantiante it first
        if ( is_string($modelName) ){
            try {
                $model = app($modelName);
            }catch (ReflectionException $exception) {
                $model = null;
            }
        }

        //if model isn't instance of eloquent, throw error 404
        if ( !($model instanceOf Model) ) {
            $this->errorInternal('Resource model not found');
        }

        //return the model
        return $model;
    }

    //get client's model
    public function getModel(){
        return $this->model;
    }
}