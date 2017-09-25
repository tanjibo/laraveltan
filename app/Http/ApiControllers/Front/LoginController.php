<?php

/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 21/9/2017
 * Time: 10:53 AM
 */

namespace App\Http\ApiControllers\Front;

use App\Http\ApiControllers\ApiController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends ApiController
{
    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login( Request $request )
    {

        return $this->token($request);
    }


    public function logout( Request $request )
    {
        if (Auth::guard('api')->check()){
            dd(Auth::guard('api')->user()->token());
        }
    }
}