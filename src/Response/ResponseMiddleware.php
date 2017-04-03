<?php

namespace Ordent\RamenResource\Response;

class ResponseMiddleware
{
    //update resource response if needed
    public function handle($request, $next){
        
        //get response
        $response = $next($request);

        //if response is resource and need updated, we update it
        if ($response instanceof ResponseAbstract && $response->needUpdate()){
            $response->updateData();
        }

        return $response;
    }
}