<?php

namespace Ordent\RamenResource;

trait HasHandlerTrait
{
    protected $handlers = [];
    protected static $defaultHandlers = [
        'index'         => 'Ordent\RamenResource\handlers\Index',
        'show'          => 'Ordent\RamenResource\handlers\Show',
        'store'         => 'Ordent\RamenResource\handlers\Store',
        'update'        => 'Ordent\RamenResource\handlers\Update',
        'delete'        => 'Ordent\RamenResource\handlers\Delete',
        'indexRelated'  => 'Ordent\RamenResource\handlers\IndexRelated',
        'storeRelated'  => 'Ordent\RamenResource\handlers\StoreRelated',
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
