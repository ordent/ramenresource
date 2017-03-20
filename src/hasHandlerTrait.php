<?php

namespace Prototype\Resource;

trait HasHandlerTrait
{
    protected $handlers = [];
    protected static $defaultHandlers = [
        'index'         => 'Prototype\Resource\handlers\Index',
        'show'          => 'Prototype\Resource\handlers\Show',
        'store'         => 'Prototype\Resource\handlers\Store',
        'update'        => 'Prototype\Resource\handlers\Update',
        'delete'        => 'Prototype\Resource\handlers\Delete',
        'indexRelated'  => 'Prototype\Resource\handlers\IndexRelated',
        'storeRelated'  => 'Prototype\Resource\handlers\StoreRelated',
    ];

    //resolve handler
    public function resolveHandler(string $handler){

        // check user defined handler. if exist we return it
        if ( isset($this->handlers[$handler]) ){
            return $this->handlers[$handler];
        }

        // if not found then we check from default handler
        if ( isset(static::$defaultHandlers[$handler]) ){
            return $this->getDefaultHandler($handler);
        }

        //if still not found, throw error
        $this->errorInternal("Method {$handler} not Found");
    }

    //get default handler
    public function getDefaultHandler(string $handler){

        //if the handler is not instantiated, we instantiate it first
        if ( is_string(static::$defaultHandlers[$handler]) ){
            static::$defaultHandlers[$handler] = app(static::$defaultHandlers[$handler]);
        }

        //return the handler
        return static::$defaultHandlers[$handler];
    }

    //get user defined handler from model
    protected function setHandlerFromModel($model){

        // get all method name from model. stop process if empty
        $methods = get_class_methods($model);
        if ( !$methods ){
            return;
        }

        //filter $methods to get handlers
        foreach ($methods as $method) {

            //process only $method with prefix 'resource'
            if (starts_with($method, 'resource')) {

                //generate key name.
                //remove 'resource' prefix and lowercase first letter from $method
                $key = lcfirst(substr($method, strlen('resource')));

                //insert $model and $method as callable to $this->handlers
                $this->handlers[$key] = [$model, $method];
            }
        }
    }
}
