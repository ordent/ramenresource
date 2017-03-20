<?php

namespace Prototype\Resource\Handlers;

use Prototype\Resource\Container;

class Store extends BaseHandler
{
	//store resource
    public function __invoke(Container $container){

    	//get validated data from resource
    	$data = $this->validateData($container, 'store');

		//store new resource and return it
        return $container->model->create($data);
	}
}