<?php

namespace Ordent\RamenResource\Handlers;

use Ordent\RamenResource\Container;

class IndexRelated extends BaseHandler
{
	//get collection of related resources
    public function __invoke(Container $container){

    	//get relation filtered query
    	$relation = $this->findRelation($container);
    	$query = $this->filter($relation, $container->parameters);

        //return indexed resource
        return $this->getIndex($query, $container->parameters);
    }
}