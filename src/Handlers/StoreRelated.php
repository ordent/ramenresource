<?php

namespace Ordent\RamenResource\Handlers;

class StoreRelated
{
	use HandlerTrait;

	//store function
	protected $store;

	//constructor
	public function __construct(Store $store){
		$this->store = $store;
	}

	//store related resource
    public function __invoke($model, string $relation, $id, $data, array $parameters = []){

    	//find resource relation using $id and $relation, throw error 404 if not found
		$relation = $this->findRelation($model, $id, $relation);

		//get store handler
		$store = $this->store;

		//store new resource and return it
        return $store($relation, $id, $data, $parameters);
	}
}