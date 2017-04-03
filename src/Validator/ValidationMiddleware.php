<?php

namespace Ordent\RamenResource\Validator;

class ValidationMiddleware
{
    use ValidationTrait;

    //validate request
    public function handle($request, $next, $ruleKey){

        //validate request
        $this->validate($request, $ruleKey);

        //continue request
        return $next($request);
    }
}