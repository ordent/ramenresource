<?php

namespace Ordent\RamenResource\Handlers;

use Illuminate\Http\Request;

class Store
{
	use HandlerTrait;

	//store resource
	public function __invoke($model, $data, array $parameters = []){

		//if $data is Request, we extract data and parameters from it
		if ($data instanceof Request){
			$data = $data->all();
			$parameters = $data->query();
		}

		//create new resource
		$resource = $model->create($data);

		//if $parameters[include] is set, load relation using it
		if (isset($parameters['include'])){
			$resource->load($parameters['include']);
		}

		//return the resource
		return $resource;
	}
}