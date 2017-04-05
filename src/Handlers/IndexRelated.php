<?php

namespace Ordent\RamenResource\Handlers;

class IndexRelated
{
	use HandlerTrait;

	//index function
	protected $index;

	//constructor
	public function __construct(Index $index){
		$this->index = $index;
	}

	//get collection of related resources
    public function __invoke($model, string $relation, $id, $parameters = []){

		//find resource relation using $id and $relation, throw error 404 if not found
		$relation = $this->findRelation($model, $id, $relation);

		//get index handler
		$index = $this->index;

        //return indexed resource
        return $index($relation, $parameters);
    }
}