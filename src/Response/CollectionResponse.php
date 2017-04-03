<?php

namespace Ordent\RamenResource\Response;

use InvalidArgumentException;
use League\Fractal\Resource\Collection;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Collection as IlluminateCollection;

class CollectionResponse extends ResponseAbstract
{
    //instantiate resource object
    protected function createResource(){
        return new Collection;
    }

    //override setData function
    public function setData($data = []){

        //check data type
        $this->checkDataType($data);

        //if $data is LengthAwarePaginator, set paginator to resource
        if ($data instanceof LengthAwarePaginator){
            $this->resource->setPaginator(new IlluminatePaginatorAdapter($data));
        }

        //set data using pareng function
        return parent::setData($data);
    }

    //override getTransformerFromResource function
    protected function getTransformerFromResource($resource){

        //if $resource is paginator, collection, or array, get first element from it
        if ($resource instanceof AbstractPaginator || $resource instanceof IlluminateCollection || is_array($resource)) {
            $resource = array_first($resource);
        }

        //use parent function with updated $resource
        return parent::getTransformerFromResource($resource);
    }

    //check data type. only accept paginator, collection, or array
    protected function checkDataType($data){

        //if $data is paginator, collection, or array, return it
        if ($data instanceof AbstractPaginator || $data instanceof IlluminateCollection || is_array($data)){
            return $data;
        }

        //else throw error
        throw new InvalidArgumentException(
            'data value must be array, or instance of '.IlluminateCollection::class.' or '.AbstractPaginator::class
        );
    }
}