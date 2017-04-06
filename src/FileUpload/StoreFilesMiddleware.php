<?php

namespace Ordent\RamenResource\FileUpload;

class StoreFilesMiddleware
{
	use StoreFilesTrait;

    public function handle($request, $next){

    	//store all files found from request
    	//then merge the result back to request
        $request->merge($this->storeFiles($request->allFiles()));

        return $next($request);
    }    
}