<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\URL;
use Session;

class SetLocale
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
        
            // dd($request->lang);
        if ($request->lang) {
            Session::put('locale', $request->lang);
        }
        app()->setLocale(Session::get('locale'));
        return $next($request);
    }
}
