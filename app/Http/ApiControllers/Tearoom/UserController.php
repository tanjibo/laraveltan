<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 3/9/2018
 * Time: 2:52 PM
 */

namespace App\Http\ApiControllers\Tearoom;


use App\Http\ApiControllers\ApiController;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends ApiController
{
    /**
     * @param Request $request
     * @return mixed
     * 获取用户信息
     */
    function userInfo( Request $request )
    {
        if (Auth::id()) {
            $request[ 'user_id' ] = Auth::id();
            return $this->success(new UserResource(User::query()->find(Auth::id() ?: 165)));
        }
        else {
            return $this->failed('获取用户信息错误');
        }

    }
}