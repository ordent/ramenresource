<?php

namespace Ordent\RamenResource\FileUpload;

class StoreFilesMiddleware
{
    public function handle($request, $next){

        $storeFiles = new StoreFiles;

        $request->merge($storeFiles($request->allFiles()));

        return $next($request);
    }    
}