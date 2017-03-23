<?php

namespace Ordent\RamenResource\Handlers;

use Ordent\RamenResource\Container;

class StoreRelated extends BaseHandler
{
	//store related resource
    public function __invoke(Container $container){

    	//get relation and validated data
    	$relation = $this->findResource($container);
    	$data = $this->validateData($container, 'store'.ucfirst($container->relation));

		//store new resource and return it
        return $relation->create($data);
	}
}