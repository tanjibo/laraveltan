<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 19/9/2017
 * Time: 1:57 PM
 */

namespace App\Foundation\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

class LrssEloquentUserProvider extends EloquentUserProvider
{

    public function validateCredentials( UserContract $user, array $credentials )
    {
        $plain        = $credentials[ 'password' ];

        $authPassword = $user->getAuthPassword();

        return LrssPassword::validate($plain, $authPassword);
    }
}