<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class isSale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user()->role != config('constants.SALE_STAFF')) {
            Abort(403, 'Unauthorized action.');
        }
        return $next($request);
    }
}
