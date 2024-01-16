<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class isShopAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   //die;
        if (Auth::guard('staff')->user()->role != config('constants.SHOP_ADMIN')) {
            if (Auth::guard('staff')->user()->role != config('constants.COMPANY_ADMIN')) {
                if (Auth::guard('staff')->user()->role != config('constants.ADMIN')) {
                    Abort(403);
                }
            }
        }
        return $next($request);
    }
}
