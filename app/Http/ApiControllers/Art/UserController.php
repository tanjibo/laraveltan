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


    /**
     * @param Request $request
     * @return mixed
     * refresh token
     */
    public function refreshToken( Request $request )
    {

        $request->request->add(
            [
                'grant_type'    => 'refresh_token',
                'refresh_token' => $request->refresh_token,
                'client_id'     => config('passport.art.client_id'),
                'client_secret' => config('passport.art.client_secret'),
                'scope'         => '',
            ]

        );

        $proxy = Request::create('oauth/token', 'POST');
        $response = \Route::dispatch($proxy);

        $data = json_decode($response->getContent(), true);
        if ($response->getStatusCode() == $this->statusCode) {
            return $this->success($data);
        }
        else {
            return $this->notFound($data);
        }

    }
}