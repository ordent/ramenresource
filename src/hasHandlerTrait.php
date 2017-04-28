<?php

namespace Ordent\RamenResource;

use InvalidArgumentException;
use UnexpectedValueException;

trait HasHandlerTrait
{
    protected $handlers = [];
    protected static $defaultHandlers = [
        'index'         => 'Ordent\RamenResource\Handlers\Index',
        'show'          => 'Ordent\RamenResource\Handlers\Show',
        'store'         => 'Ordent\RamenResource\Handlers\Store',
        'update'        => 'Ordent\RamenResource\Handlers\Update',
        'delete'        => 'Ordent\RamenResource\Handlers\Delete',
        'indexRelated'  => 'Ordent\RamenResource\Handlers\IndexRelated',
        'storeRelated'  => 'Ordent\RamenResource\Handlers\StoreRelated',
    ];

    //resolve handler
    protected function resolveHandler(string $handler){

        // check user defined handler. if exist we return it
        if ( isset($this->handlers[$handler]) ){
            return $this->handlers[$handler];
        }

        // if not found then we try to get from default handler
        if ( isset(static::$defaultHandlers[$handler]) ){
            return $this->getDefaultHandler($handler);
        }

        //if still not found, throw error
        throw new InvalidArgumentException("Resource handler {$handler} not Found");
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

                //if $value isn't callable, throw error
                if (!is_callable($value)){
                    throw new UnexpectedValueException('handler must be callable');
                }

                //register handler
                $this->handlers[$key] = $value;
            }
        }
    }
}
