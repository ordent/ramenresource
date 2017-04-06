<?php

namespace Ordent\RamenResource\FileUpload;

trait StoreFilesTrait
{
    //store files
    public function storeFiles($key, $file = null){

        //create new storeFiles handler, set config, execute it and return the result
        $storeFiles = new StoreFiles($this->storeFileConfig());
        return $storeFiles(...func_get_args());
    }

    //set config for storing files
    protected function storeFileConfig(){
        return [];
    }
}