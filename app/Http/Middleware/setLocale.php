<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle($request, Closure $next)
    { 
        App::setLocale(Session()->get('applocale'));
        return $next($request);
    }
}