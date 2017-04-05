<?php

namespace Ordent\RamenResource\Response;

use League\Fractal\Resource\Item;

class ItemResponse extends ResponseAbstract
{
    //instantiate resource object
    protected function createResource(){
        return new Item;
    }
}