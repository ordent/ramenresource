<?php

namespace Ordent\RamenResource\Handlers;

use BadMethodCallException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

trait HandlerTrait
{

//handler helpers
//===========================================================================

    //find resource from $container
    protected function findResource($model, $id){

        //if $id is array, we use where-first method
        //else, we assume the $id as table id
        if (is_array($id)){
            $resource = $model->where($id)->first();
        }
        else {
            $resource = $model->find($id);
        }
        
        //if resource exist, we return it. else throw error 404
        return $resource ?: response()->errorNotFound();
    }

    //find resource's relation from $container
    protected function findRelation($model, $id, $relationName){

        //find resource
        $resource = $this->findResource($model, $id);

        //try to find resource relation, catch error if fails
        try{
            $relation = $resource->$relationName();
        } catch (BadMethodCallException $exception){
            $relation = null;
        }

        //if relation found, return it. else throw error 404
        return ($relation instanceOf Relation) ? $relation : response()->errorNotFound($relationName.' not Found');
    }

    //run index filter from model if exist
    protected function indexFilter($query, $parameters = []){

        // get model from query
        $model = ($query instanceOf Model) ? $query : $query->getModel();

        //if scope index filter exist in $model, execute it
        if (method_exists($model, 'scopeIndexFilter')){
            $query = $query->indexFilter($parameters);
        }

        //return $query
        return $query;
    }

    //return list of resources from query
    protected function indexResult($query, $parameters = []){

        //if page or limit exist, we will paginate resource
        $limit = isset($parameters['limit']) ? (int) $parameters['limit'] : null;
        if ( isset($parameters['page']) || isset($limit) ){
            return $query->paginate($limit);
        }

        //else return collection
        return $query->get();
    }
}