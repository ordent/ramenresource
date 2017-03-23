<?php

namespace Ordent\RamenResource\FileUpload;

class StoreFilesMiddleware
{
    protected $storeFiles;

    public function handle($request, $next){

        $storeFiles = new StoreFiles;

        $request->merge($storeFiles($request->allFiles()));

        return $next($request);
    }    
}