<?php
namespace App\Http\Middleware;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Closure;
use Illuminate\Http\Request;

class SentinelWare
{
	public function handle(Request $request, Closure $next)
	{
		if (Sentinel::guest()) {
			return response()->json(["guestAccess"=>"You are not logged in!!"]);
		}
		return $next($request);
	}
}
