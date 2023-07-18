<?php

namespace App\Http\Middleware;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Closure;

class AllPermissionsMiddleware
{
	public function handle($request, Closure $next, $permissions)
	{
		if (!Sentinel::hasAccess($permissions)) {
			$roleName = Sentinel::getUser()->roles()->first()->name;
			Sentinel::logout(NULL, true);
			return response()->json(['AuthorizationError' =>
			 __("messages.AuthorizationRole").$roleName." ".__("messages.AuthorizationError")] , 403);
		}
		return $next($request);
	}
}
