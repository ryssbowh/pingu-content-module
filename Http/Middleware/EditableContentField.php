<?php

namespace Pingu\Content\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Pingu\Core\Exceptions\ProtectedModel;

class EditableContentField
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
        if($field and !$field->editable){
            throw ProtectedModel::forEdition($field);
        }
        return $next($request);
    }
}
