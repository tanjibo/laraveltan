<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 30/7/2018
 * Time: 3:05 PM
 */

namespace App\Traits;


use App\Exceptions\InternalException;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


trait WechatLogin
{


    public function decryptedUserInfo( Request $request )
    {

        $result = $this->mini->auth->session($request->code);

        if (isset($result[ 'errcode' ])) {

            throw new InternalException('error:' . $result[ 'errmsg' ]);
        }

        //  cache('art:' . $result[ 'unionid' ], $result[ 'session_key' ], 3600 * 24);
        //根据code 获取登录信息

        $decryptedData = $this->mini->encryptor->decryptData($result[ 'session_key' ], $request->iv, $request->encryptedData);

        if (isset($decryptedData[ 'errcode' ])) {

            throw new InternalException('error:' . $result[ 'errmsg' ]);
        }

        return $decryptedData;
    }


    protected function accessToken( Request $request, string $client = 'experience' )
    {

        $user = $request->username ?: $request->union_id;

        $params = array_merge(
            config('passport.' . $client),
            [
                'username' => $user,
                'password' => $request->password,
            ]
        );

        return $this->requestPassportApi($params);

    }

    /**
     * @param string $url
     * @param array $params
     * @return mixed
     * request from laravel passport api
     */
    protected function requestPassportApi( array $params )
    {
        $http     = new Client;
        $response = $http->post(url('oauth/token'), [ 'form_params' => $params ]);
        if ($response->getStatusCode() == Response::HTTP_OK) {
            return json_decode((string)$response->getBody(), true);
        }
        throw new InternalException('请求passport 出现错误:');
    }


}