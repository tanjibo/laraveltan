<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/LrssMiniProgram',
        "/official_account/*",
        "/official_account",
    ];
    protected function isReading($request)
    {
        $method=['HEAD', 'GET', 'OPTIONS'];
        if(app()->environment('test')){
           array_push($method,'POST');
        }
        return in_array($request->method(),$method);
    }
}
