<?php

namespace App\Http\Middleware;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Closure;

class AllPermissionsMiddleware
{
	public function handle($request, Closure $next, ...$permissions)
	{
		if (!Sentinel::hasAccess($permissions)) {
			Sentinel::logout(NULL, true);
			return response()->json(['AuthorizationError' => __("messages.AuthorizationError")]);
		}
		return $next($request);
	}
}
