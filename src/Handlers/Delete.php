<?php

namespace Ordent\RamenResource\Handlers;

use Ordent\RamenResource\Container;

class Delete extends BaseHandler
{
	//show resource
    public function __invoke(Container $container){

        //get resource
        $resource = $this->findResource($container);

        //delete resource, throw internal error if fails
        if ( !$resource->delete() ){
            $this->errorInternal('delete process failed');
        }

        //return resource
        return $resource;
	}
}