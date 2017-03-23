<?php

namespace Ordent\RamenResource\Handlers;

use Ordent\RamenResource\Container;

class Show extends BaseHandler
{
	//show resource
    public function __invoke(Container $container){

		//find resource from container and return it
		return $this->findResource($container);
	}
}