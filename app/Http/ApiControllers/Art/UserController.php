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
use App\Http\Resources\Art\NotificationResource;
use App\Http\Resources\Experience\UserResource;
use App\Models\User;
use Illuminate\Http\Request;


class UserController extends ApiController
{

   public function userInfo(Request $request){

        if($user_id=auth()->id()){
            $request['user_id']=$user_id;
            return $this->success(new UserResource(User::query()->find($user_id?:5)));

        }else{
            return $this->failed('error');
        }
    }

   public function unReadMsg()
    {

        $user = app()->environment() == 'local' ? User::find(5) : auth()->user();

        $data=$user->notifications()->paginate(10);

        $links=['current_page'=>$data->currentPage(),'total'=>$data->lastPage()];

        $notifications = NotificationResource::collection($data);
        // 标记为已读，未读数量清零
        $user->markAsRead();

        return  $this->success(['data'=>$notifications,'link'=>$links]);

    }
}