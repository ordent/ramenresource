<?php

namespace Ordent\RamenResource\Handlers;

use Illuminate\Http\Request;

class Index
{
	use HandlerTrait;

	//get collection of resources
    public function __invoke($model, $parameters = []){

    	//if $parameters is Request, we extract query as parameters from it
		if ($parameters instanceof Request){
			$parameters = $parameters->query();
		}

		//run index filter if any
		$query = $this->indexFilter($model, $parameters);

        //get index result and return it
        return $this->indexResult($query, $parameters);
    }
}