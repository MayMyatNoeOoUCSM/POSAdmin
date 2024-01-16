<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class checkCompanyLicense
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
        if (Auth::user()->company_id) {
            $companyLicense = \App\Models\CompanyLicense::where('company_id', '=', Auth::user()->company_id)
                ->where('status', config('constants.COMPANY_LICENSE_ACTIVE'))
                ->get();
            if ($companyLicense->count() == 0) {
                Abort(403, 'Your company license is expired!');
            }
        } else {
            Abort(403, 'Unauthorized action.');
        }
        return $next($request);
    }
}
