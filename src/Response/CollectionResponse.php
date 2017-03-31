<?php

namespace Ordent\RamenResource\Response;

use League\Fractal\Resource\Collection;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Collection as IlluminateCollection;

class ItemResponse extends ResponseAbstract
{
    //instantiate resource object
    protected function createResource(){
        return new Collection;
    }

    //override setData function
    public function setData($data = []){

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
}