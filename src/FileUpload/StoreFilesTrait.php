<?php

namespace Ordent\RamenResource\FileUpload;

trait StoreFileTrait
{
    //store files
    public function storeFiles($key, $file = null){

        //create new storeFiles handler, set config, execute it and return the result
        $storeFiles = new StoreFiles($this->storeFileConfig());
        return $validator(...func_get_args());
    }

    //set config for storing files
    protected function storeFileConfig(){
        return [];
    }
}