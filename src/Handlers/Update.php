<?php

namespace Ordent\RamenResource\Handlers;

use Ordent\RamenResource\Container;

class Update extends BaseHandler
{
	//store resource
    public function __invoke(Container $container){

        //get resource and validated data from container
        $resource = $this->findResource($container);
        $data = $this->validateData($container, 'update');

		//update data to resource, throw internal error if fails
        if ( ! $resource->update($data)){
            $this->errorInternal('update process failed');
        }

        //return the resource
        return $resource;
	}
}