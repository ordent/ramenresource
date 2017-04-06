<?php

namespace Ordent\RamenResource\FileUpload;

use Storage;
use InvalidArgumentException;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;

class StoreFiles
{

    protected $defaultConfig = [
        'path' => '',
        'disk' => 'public'
    ];

    //config
    protected $config;

    //storage disk instance
    protected $storages;

    public function __construct(array $config = []){

        //get config from config files
        $this->config = $config?: config('ramen.storeFiles', []);
    }

    //store $files to the storage and return the path
    public function __invoke($key, $file = null){

        //if $file is string, then we assume the input is key - value pair
        //so we convert it to array first
        if (!is_array($key)){
            $key = [$key => $file];
            $singleFile = true;
        }

        //create collection of files to make process easier
        //resolve every files first
        //and then save all of it
        $fileCollection = collect($key)
            ->transform([$this, 'resolveFile'])
            ->transform([$this, 'saveFile']);

        //if there's originnaly only 1 file, we extract it from collection and return it.
        // else we return the collection as array
        return isset($singleFile) ?
            $fileCollection->pop() : $fileCollection->all();
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

        //else throw error
        throw new InvalidArgumentException('StoreFiles input must be path of file, instance of File or UploadedFile');
    }

    //save file and return the path
    public function saveFile($file, $keyName){

        //get config by keyName
        $config = $this->getConfig($keyName);

        //save and return the path
        return $this->getStorage($config['disk'])->putFileAs($config['path'], $file, $this->generateFileName($file));
    }

    //generate random string for filename
    protected function generateFileName($file){
        return uniqid(str_random(6)).'.'.$file->extension();
    }

    //get config by $key
    protected function getConfig($key){

        //get config, merge with default config, and return it
        $config = isset($this->config[$key]) ? $this->config[$key] : [];
        return array_merge($this->defaultConfig, $config);
    }

    //get storage by $disk
    protected function getStorage($disk){

        //if the selected storage isn't instantiated, we instantiate it first
        if (!isset($this->storages[$disk])){
            $this->storages[$disk] = Storage::disk($disk);
        }

        //return the storage disk
        return $this->storages[$disk];
    }
}