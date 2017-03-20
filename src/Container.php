<?php

namespace Prototype\Resource;

class Container
{
    public $model;
    public $id = null;
    public $relation = null;
    public $data = [];
    public $parameters = [];

    //construct
    public function __construct($model){

        $this->model = $model;
    }

// setter
//===========================================================================
    public function setIdentifier($id, string $relation = null){
        $this->id = $id;
        $this->relation = $relation;
        return $this;
    }

    public function setData(array $data){
        $this->data = $data;
        return $this;
    }

    public function setParameters(array $parameters){
        $this->parameters = $parameters;
        return $this;
    }
}
