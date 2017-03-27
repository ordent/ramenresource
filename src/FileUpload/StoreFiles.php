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

        //if only 1 files given, we change it to array
        if ( !is_array($files) ){
            $files = [$files];
            $singleFile = true;
        }

        //create collection of files
        //then resolve every $files
        //and then save every $files
        $files = collect($files)
            ->transform([$this, 'resolveFile'])
            ->transform([$this, 'saveFile']);

        //if there's originnaly only 1 file, we extract it from collection and return it.
        // else we return the collection as array
        return isset($singleFile) ?
            $files->pop() : $files->all();
    }

    //resolve $file
    public function resolveFile($file){

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
    public function saveFile($file, $keyName){
        return $this->storage->putFileAs($this->getPath($keyName), $file, $this->generateFileName($file));
    }

    //generate random string for filename
    protected function generateFileName($file){
        return uniqid(str_random(6)).'.'.$file->extension();
    }

    //get path from $keyname, or empty string if not found
    public function getPath(string $keyName){
        return config("fileUpload.path.$keyName", '');
    }

    //set storage disk
    public function setDisk(string $disk){
        $this->storage = Storage::disk($disk);
        return $this;
    }
}