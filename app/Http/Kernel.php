<?php

namespace App\Http;

use App\Http\Middleware\checkCompanyLicense;
use App\Http\Middleware\isCashier;
use App\Http\Middleware\isCompanyAdmin;
use App\Http\Middleware\isKitchen;
use App\Http\Middleware\isSale;
use App\Http\Middleware\isShopAdmin;
use App\Http\Middleware\isShopStaff;
use App\Http\Middleware\isSuperAdmin;
use App\Http\Middleware\isWaiter;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
        \App\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            'throttle:60,1',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'setlocale' => \App\Http\Middleware\SetLocale::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'isSuperAdmin' => isSuperAdmin::class,
        'isCompanyAdmin' => isCompanyAdmin::class,
        'isShopAdmin' => isShopAdmin::class,
        'isShopStaff' => isShopStaff::class,
        'isCashier' => isCashier::class,
        'isKitchen' => isKitchen::class,
        'isWaiter' => isWaiter::class,
        'isSale' => isSale::class,
        'checkCompanyLicense'=>checkCompanyLicense::class,
        'preventBackHistory' => \App\Http\Middleware\PreventBackHistory::class,
        'XssSanitizer' => \App\Http\Middleware\XssSanitizer::class

    ];
}
