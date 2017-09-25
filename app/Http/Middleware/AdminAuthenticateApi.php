<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate;

class AdminAuthenticateApi extends  Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    protected function authenticate(array $guards=['admin_api'])
    {

            if ($this->auth->guard('admin_api')->check()) {

                return $this->auth->shouldUse('admin_api');
            }

        throw new AuthenticationException('Unauthenticated.', $guards);
    }
}
