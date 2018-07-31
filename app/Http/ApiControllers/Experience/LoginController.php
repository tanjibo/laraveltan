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

namespace App\Http\ApiControllers\Experience;

use App\Exceptions\InternalException;
use App\Http\ApiControllers\ApiController;
use App\Http\Requests\MiniAuthorizationRequest;
use App\Models\User;
use App\Traits\WechatLogin;
use EasyWeChat\Factory;
use GuzzleHttp\Client;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Repositories\StoreUserRepository;


class LoginController extends ApiController
{
    use AuthenticatesUsers;
    use WechatLogin;

    protected $mini;

    public function __construct( Request $request )
    {

        $this->middleware('guest')->except('logout');

        $this->mini = Factory::miniProgram(config('wechat.mini_program.experience'));
    }

    /**
     * 小程序登录
     */
    public function miniLogin( MiniAuthorizationRequest $request, StoreUserRepository $repository )
    {

        $decryptedData = $this->decryptedUserInfo($request);

        if ($user = $repository->storeUserByExperienceMini($decryptedData)) {

            $request->offsetSet('username', $decryptedData[ 'unionId' ]);
            $request->offsetSet('password', '');
            return $this->success($this->accessToken($request));

        }
        return $this->failed('login failed');


    }

    /**
     * @param Request $request
     * @return mixed
     * refresh token
     */
    public function refreshToken( Request $request )
    {

        $params = [
            'grant_type'    => 'refresh_token',
            'refresh_token' => $request->refresh_token,
            'client_id'     => config('passport.experience.client_id'),
            'client_secret' => config('passport.experience.client_secret'),
            'scope'         => '*',
        ];
        return $this->success($this->requestPassportApi($params));

    }

}