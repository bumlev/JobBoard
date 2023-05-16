<?php


namespace App\Http\Middleware;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Closure;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;

class AllPermissionsMiddleware
{
	public function handle($request, Closure $next, ...$permissions)
	{
		
		if (!Sentinel::hasAccess($permissions)) {
			if ($request->ajax() || $request->wantsJson()) {
				return Response::create('Denies Access...', 401);
			} else {
				Sentinel::logout(NULL, true);
				return response()->json('You are not authorized to access this page...');
			}
		}
		return $next($request);
	}
}
