<?php

namespace Ordent\RamenResource\Handlers;

use Illuminate\Http\Request;

class Delete
{
    use HandlerTrait;

	//show resource
    public function __invoke($model, $id, $parameters = []){

        //if $parameters is Request, we extract query as parameters from it
        if ($parameters instanceof Request){
            $parameters = $parameters->query();
        }

        //find resource using id, throw error 404 if not found
        $resource = $this->findResource($model, $id);

        //if $parameters[include] is set, load relation using it
        if (isset($parameters['include'])){
            $model = $model->with($parameters['include']);
        }

        //delete resource, throw internal error if fails
        if ( !$resource->delete() ){
            throw new RuntimeException('delete process failed');
        }

        //return resource
        return $resource;
	}
}