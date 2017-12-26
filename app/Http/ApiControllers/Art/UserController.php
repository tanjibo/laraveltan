<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 26/12/2017
 * Time: 3:22 PM
 */

namespace App\Http\ApiControllers\Art;


use App\Http\ApiControllers\ApiController;
use App\Http\Resources\Experience\UserResource;
use App\Models\User;
use Qiniu\Http\Request;

class UserController extends ApiController
{

    function userInfo(Request $request){

        if($user_id=auth()->id()){
            $request['user_id']=$user_id;
            return $this->success(new UserResource(User::query()->find($user_id?:165)));

        }else{
            return $this->failed('error');
        }
    }

    function unReadMsg(){

          $user=app()->environment()=='local'?User::find(5):auth()->user();

         $notifications = $user->notifications()->paginate(20);
        // 标记为已读，未读数量清零
          $user->markAsRead();

          return $this->success($notifications);
    }
}