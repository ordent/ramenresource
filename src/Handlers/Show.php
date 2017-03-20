<?php

namespace Prototype\Resource\Handlers;

use Prototype\Resource\Container;

class Show extends BaseHandler
{
	//show resource
    public function __invoke(Container $container){

		//find resource from container and return it
		return $this->findResource($container);
	}
}