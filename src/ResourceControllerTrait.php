<?php

namespace Ordent\RamenResource;

use Illuminate\Http\Request;

trait ResourceControllerTrait
{
    //resource client
    protected $resource;

    //set resource
    protected function setResource($model){
        $this->resource = new Client($model);
    }

    //get index resource
    public function index(Request $request){
        return response()->resourceCollection($this->resource->index($request));
    }

    //show single resource
    public function show(Request $request, $id){
        return response()->resourceItem($this->resource->show($id, $request));
    }

    //store new resource
    public function store(Request $request){
        return response()->resourceItem($this->resource->store($request));
    }

    //update resource
    public function update(Request $request, $id){
        return response()->resourceItem($this->resource->update($id, $request));
    }

    //delete resources
    public function delete(Request $request, $id){
        return response()->resourceItem($this->resource->delete($id, $request));
    }
}