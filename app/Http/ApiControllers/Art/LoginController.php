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

namespace App\Http\ApiControllers\Art;

use App\Http\ApiControllers\ApiController;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tanjibo\Minilrss\Minilrss;


class LoginController extends ApiController
{
    use AuthenticatesUsers;
    protected $wx;

    public function __construct(Request $request)
    {
        $this->middleware('guest')->except('logout');

        $this->wx = new Minilrss(config('minilrss.art.appid'), config('minilrss.art.secret'));

    }

    /**
     * 小程序登录
     */
    public function miniLogin( Request $request )
    {
        if($request->bearerToken()){
            return  $this->success(['message'=>'已经登录']);
        }
        if(app()->environment()=='local'){
            $user=User::find(5);
            $request->request->add([ 'union_id' =>$user->union_id , 'password' => "" ]);

            return $this->token($request, 'art');
        }
        //根据code 获取登录信息
        $this->wx->getLoginInfo($request->code);

        $userInfo = json_decode($this->wx->getUserInfo($request->encryptedData, $request->iv), true);
       extract($userInfo);
        $userData = [
            'avatar'      => $avatarUrl,
            'nickname'    => $nickName,
            'union_id'    => $unionId,
            'gender'      => $gender,
            'art_open_id' => $openId,
            'status'      => User::USER_STATUS_ON,

        ];


        if ($user = User::query()->where('union_id', $unionId)->first()) {

            $user->update($userData);

        }
        else {
            //用户来源
            $userData[ 'source' ] = User::SOURCE_ARTSHOW;
            User::query()->create($userData);

        }

        $request->request->add([ 'union_id' => $unionId, 'password' => "" ]);

        return $this->token($request, 'art');
    }





}