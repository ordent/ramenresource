<?php

namespace Ordent\RamenResource\Response;

use InvalidArgumentException;
use UnexpectedValueException;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use League\Fractal\TransformerAbstract;
use League\Fractal\Manager;
use League\Fractal\Serializer\SerializerAbstract;
use League\Fractal\Serializer\DataArraySerializer;

abstract class ResponseAbstract extends JsonResponse
{

    protected $fractal;
    protected $resource;
    protected $needUpdate;

    public function __construct($data = [], $status = 200, $headers = []){

        $this->fractal = new Manager;
        $this->fractal
            ->setSerializer($this->defaultSerializer())
            ->parseIncludes($this->includesInput());

        $this->resource = $this->createResource()
            ->setTransformer($this->getTransformerFromResource($data));

        parent::__construct($data, $status, $headers);
    }

    //override setEncodingOptions
    public function setEncodingOptions($options){

        //set encodingOptions
        $this->encodingOptions = (int) $options;

        //only return $this after set encodingOptions to reduce useless process
        return $this;
    }

    //override setData function
    public function setData($data = []){

        //set original data
        $this->original = $data;

        //set data to resource
        $this->resource->setData($data);

        //update data and return it
        return $this->updateData();
    }

    //set serializer to fractal
    public function setSerializer(SerializerAbstract $serializer){

        //set serializer and mention that data need updated
        $this->fractal->setSerializer($serializer);
        $this->needUpdate = true;

        //return current object
        return $this;
    }

    //set transformer to resource
    public function setTransformer($transformer){

        //set transformer and mention that data need updated
        $this->resource->setTransformer($this->checkTransformerType($transformer));
        $this->needUpdate = true;

        //return current object
        return $this;
    }

    //set meta data to resource
    public function setMeta(array $meta){

        //set meta and mention that data need updated
        $this->resource->setMeta($meta);
        $this->needUpdate = true;   

        //return current object
        return $this;
    }

    //add meta data to resource
    public function addMeta(string $key, $value){

        //add meta and mention that data need updated
        $this->resource->setMetaValue($key, $value);
        $this->needUpdate = true;

        //return current object
        return $this;
    }

    //set resourceKey to resource
    public function setResourceKey(string $key){

        //set resourceKey and mention that data need updated
        $this->resource->setResourceKey($key);
        $this->needUpdate = true;

        //return current object
        return $this;
    }

    //get update status
    public function needUpdate(){
        return (bool) $this->needUpdate;
    }

    //update data using fractal
    public function updateData(){

        //create data using fractal manager
        $this->data = $this->fractal
            ->createData($this->resource)
            ->toJson();

        //throw error if json encode failed
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new InvalidArgumentException(json_last_error_msg());
        }

        //mention that data has been updated
        $this->needUpdate = false;

        //update content and return it
        return $this->update();
    }

    //instantiate resource object
    abstract protected function createResource();

    //try to get transformer from $resource
    protected function getTransformerFromResource($resource){

        //if $resource is object and has getTransformer method, return it
        if (is_object($resource) && method_exists($resource, 'getTransformer')) {
            $transformer = $resource->getTransformer();
        }

        //if $transformer found, return it. else return defaultTransformer
        return isset($transformer) ? $this->checkTransformerType($transformer) : $this->defaultTransformer();
    }

    //check transformer type.
    protected function checkTransformerType($transformer){

        //if $transformer is callable or TransformerAbstract, return it
        if ($transformer instanceof TransformerAbstract || is_callable($transformer)){
            return $transformer;
        }

        //else throw error
        throw new InvalidArgumentException(
            'transformer value must be callable, or instance of '.TransformerAbstract::class
        );
    }

    //get default serializer
    protected function defaultSerializer(){

        //instantiate serializer from config
        $serializer = config('ramenResource.defaultSerializer', DataArraySerializer::class);
        $serializer = new $serializer;

        //if $serializer is SerializerAbstract, return it
        if ($serializer instanceof SerializerAbstract) {
            return $serializer;
        }
        
        //else throw error
        throw new UnexpectedValueException('default serializer must be instance of '.SerializerAbstract::class);
    }

    //get default transformer
    protected function defaultTransformer(){

        //return callable as default transformer
        return function($data){

            //if data is arrayable, we use toArray method
            if ($data instanceof Arrayable){
                $data = $data->toArray();
            }

            return (array) $data;
        };
    }

    //try to get include param from request, or return empty array
    protected function includesInput(){
        return request('include', []);
    }
}