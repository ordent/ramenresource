<?php

namespace Ordent\RamenResource\Handlers;

use Ordent\RamenResource\Container;

class Index extends BaseHandler
{
	//get collection of resources
    public function __invoke(Container $container){

        //get filtered query
    	$query = $this->indexFilter($container->model, $container->parameters);

        //return indexed resource
        return $this->getIndex($query, $container->parameters);
    }
}