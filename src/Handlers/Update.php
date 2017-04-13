<?php

namespace Ordent\RamenResource\Handlers;

use RuntimeException;
use Illuminate\Http\Request;

class Update
{
    use HandlerTrait;

    //store resource
    public function __invoke($model, $id, $data, array $parameters = []){

        //if $data is Request, we extract data and parameters from it
        if ($data instanceof Request){
            $parameters = $data->query();
            $data = $data->all();
        }

        //find resource using id, throw error 404 if not found
        $resource = $this->findResource($model, $id);

    	//update data to resource, throw error if fails
        if ( !$resource->update($data)){
            throw new RuntimeException('update process failed');
        }

        //if $parameters[include] is set, load relation using it
        if (isset($parameters['include'])){
            $resource->load($parameters['include']);
        }

        //return the resource
        return $resource;
	}
}