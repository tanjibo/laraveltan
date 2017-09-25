<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 19/9/2017
 * Time: 2:37 PM
 */

namespace App\Http\ApiControllers\Admin;

use App\Http\ApiControllers\ApiController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends ApiController
{
  use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    public function login( Request $request )
    {

        return $this->token($request,'admin_api');
    }


}