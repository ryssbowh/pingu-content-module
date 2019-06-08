<?php

namespace Pingu\Content\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Pingu\Core\Exceptions\ProtectedModel;

class DeletableContentField
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $field = $request->route()->parameters['field'];
        if($field and !$field->deletable){
            throw ProtectedModel::forDeletion($field);
        }
        return $next($request);
    }
}
