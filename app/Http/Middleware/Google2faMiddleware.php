<?php

namespace App\Http\Middleware;

use Closure;
use PragmaRX\Google2FALaravel\Support\Authenticator;

class Google2faMiddleware
{
    public function handle($request, Closure $next)
    {
        if (array_get(cache('settings'), '2fa_activation')) {
            $authenticator = app(Authenticator::class)->boot($request);
            if ($authenticator->isAuthenticated()) {
                return $next($request);
            }
            return $authenticator->makeRequestOneTimePasswordResponse();
        }
        return $next($request);

    }
}