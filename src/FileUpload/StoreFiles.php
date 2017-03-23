<?php

namespace Ordent\RamenResource\FileUpload;

use Storage;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;

class StoreFiles
{
    //storage disk instance
    protected $storage;

    public function __construct(){

        //bind storage::disk instance to the object
        //get the disk from config, or use 'public' as default
        $this->storage = Storage::disk(config('fileUpload.disk', 'public'));
    }

    //store $files to the storage and return the path
    public function __invoke($files){

        //create collection of files
        //then resolve every $files
        //and then save every $files
        $files = collection($files)
            ->transform([$this, 'resolveFile'])
            ->transform([$this, 'saveFile']);

        //if there's only 1 file and the collection is not assosiative,
        //we extract it from collection and return it. else we return the collection as array
        return ( 1 === $files->count() && $files->has(0) ) ?
            $files->pop() : $files->all();
    }

    //resolve $file
    protected function resolveFile($file){

        //if $file is string we assume it is filepath and instantiate it
        if ( is_string($file) ){
            $file = new File($file);
        }

        //return the $file if it is instance of File or UploadedFile
        if ( $file instanceOf UploadedFile || $file instanceOf File ){
            return $file;
        }

        // TODO : throw error
    }

    //save file and return the path
    protected function saveFile($file){
        return $this->storage->putFileAs('', $file, $this->generateFileName($file));
    }

    //generate random string for filename
    protected function generateFileName($file){
        return uniqid(str_random(6)).$file->extension();
    }
}