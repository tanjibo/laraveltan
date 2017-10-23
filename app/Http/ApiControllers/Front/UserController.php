<?php

namespace App\Http\ApiControllers\Front;

use App\Http\ApiControllers\ApiController;
use App\Http\Resources\Front\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends ApiController
{

    /**
     * @param Request $request
     * @return mixed
     * 获取用户信息
     */
    function userInfo(Request $request){
        if(Auth::id()){
             $request['user_id']=Auth::id();
            return $this->success(new UserResource(User::query()->find(Auth::id()?:1)));

        }else{
            return $this->failed('error');
        }
    }
}
