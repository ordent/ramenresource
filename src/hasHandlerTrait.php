<?php

namespace Ordent\RamenResource;

use InvalidArgumentException;

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
    protected function resolveHandler(string $handler){

        // check user defined handler. if exist we return it
        if ( isset($this->handlers[$handler]) ){
            return $this->getHandler($handler);
        }

        // if not found then we try to get from default handler
        if ( isset(static::$defaultHandlers[$handler]) ){
            return $this->getDefaultHandler($handler);
        }

        //if still not found, throw error
        throw new InvalidArgumentException("Resource handler {$handler} not Found");
    }

    //get user defined handler
    protected function getHandler(string $handler){

        //instantiate the handler if it is string
        if (is_string($this->handlers[$handler])){
            $this->handlers[$handler] = resolve($this->handlers[$handler]);
        }

        //return the handler
        return $this->handlers[$handler];        
    }

    //get default handler
    protected function getDefaultHandler(string $handler){

        //instantiate the handler if it isn't yet
        if (is_string(static::$defaultHandlers[$handler])){
            static::$defaultHandlers[$handler] = resolve(static::$defaultHandlers[$handler]);
        }

        //return the handler
        return static::$defaultHandlers[$handler];
    }

    //get user defined handler from model
    protected function setHandlerFromModel($model){

        //check 'resourceHandler' function in model.
        //if it doesn't exist, skip the process
        if (!method_exists($model, 'resourceHandler')){
            return;
        }

        //get handler from model
        $handler = $model->resourceHandler();

        //if handler is string, we assume it is handler class path
        //so we instantiate it
        if (is_string($handler)){
            $handler = resolve($handler);
        }

        //if handler is object, extract every public method as handler
        if (is_object($handler)){
            $methods = get_class_methods($model);
            foreach ($methods as $method) {
                
                //skip any magic method
                if (starts_with($method, '__')){
                    break;
                }

                //register handler
                $this->handlers[$method] = [$handler, $method];
            }
        }

        //else if handler is array, extract every callable value from it as handler
        elseif (is_array($handler)){

            //filter $methods to get handlers
            foreach ($handler as $key => $value) {

                //only register $value if it is callable or string
                if (is_string($value) || is_callable($value)){
                    $this->handlers[$key] = $value;
                }
            }
        }
    }
}
