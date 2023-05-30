<?php


namespace App\Http\Middleware;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Closure;
use Illuminate\Http\Response;

class AllPermissionsMiddleware
{
	public function handle($request, Closure $next, $permissions)
	{
		if (!Sentinel::hasAccess($permissions)) {
			Sentinel::logout(NULL, true);
			return response()->json(['Access' => 'You are not authorized to access this page...']);
		}
		return $next($request);
	}
}
