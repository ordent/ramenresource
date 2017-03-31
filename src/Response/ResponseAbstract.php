<?php

namespace Ordent\RamenResource\Response;

use InvalidArgumentException;
use UnexpectedValueException;
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

    public function __construct($data = [], $status = 200, array $meta = [], $headers = []){

        $this->fractal = new Manager;
        $this->fractal->setSerializer($this->defaultSerializer());

        $this->resource = $this->createResource();
        $this->resource
            ->setTransformer($this->getTransformerFromResource($data))
            ->setMeta($meta);

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

        //set resource data
        $this->resource->setData($data);

        //update data and return it
        return $this->updateData();
    }

    //set serializer to fractal
    public function setSerializer(SerializerAbstract $serializer){

        //set serializer then update data
        $this->fractal->setSerializer($serializer);
        return $this->updateData();
    }

    //set transformer to resource
    public function setTransformer($transformer){

        //set transformer then update data
        $this->resource->setTransformer($this->checkTransformerType($transformer));
        return $this->updateData();
    }

    //set meta data to resource
    public function setMeta(array $meta){

        //set meta then update data
        $this->resource->setMeta($meta);
        return $this->updateData();   
    }

    //add meta data to resource
    public function addMeta($key, $value){

        //add meta then update data
        $this->resource->setMetaValue($key, $value);
        return $this->updateData();
    }

    //instantiate resource object
    abstract protected function createResource();

    //update data using fractal
    protected function updateData(){

        //create data using fractal manager
        $this->data = $this->fractal
            ->createData($this->resource)
            ->toJson();

        //update content and return it
        return $this->update();
    }

    //try to get transformer from $resource
    protected function getTransformerFromResource($resource){

        //if $resource is object and has getTransformer method, return it
        if (is_object($resource) && method_exists($resource, 'getTransformer')) {
            $transformer = $resource->getTransformer();
        }

        //if $transformer found, return it. else return null
        return isset($transformer) ? $this->checkTransformerType($transformer) : null;
    }

    //check transformer type.
    protected function checkTransformerType($transformer){

        //if $transformer is null, callable, or TransformerAbstract, return it
        if ($transformer instanceof TransformerAbstract || is_callable($transformer) || is_null($transformer)){
            return $transformer;
        }

        //else throw error
        throw new InvalidArgumentException(
            'transformer value must be null, callable, or instance of '.TransformerAbstract::class
        );
    }

    //get default serializer
    protected function defaultSerializer(){

        //instantiate serializer from config
        $serializer = new config('resource.defaultSerializer', DataArraySerializer::class);

        //if $serializer is SerializerAbstract, return it
        if ($serializer instanceof SerializerAbstract) {
            return $serializer;
        }
        
        //else throw error
        throw new UnexpectedValueException('default serializer must be instance of '.SerializerAbstract::class);
    }
}