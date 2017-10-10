<?php

namespace App\Http\Middleware;


use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate;

class AuthenticateApi extends Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    protected function authenticate(array $guards=['api'])
    {

        if ($this->auth->guard('api')->check()) {

            return $this->auth->shouldUse('api');
        }

        throw new AuthenticationException('Unauthenticated.', $guards);
    }
}
