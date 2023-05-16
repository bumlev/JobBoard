<?php
namespace App\Http\Middleware;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Closure;

class SentinelWare
{
	public function handle($request, Closure $next)
	{
		if (Sentinel::guest()) {
			return response()->json("You are not logged in!!");
		}

		return $next($request);
	}
}
