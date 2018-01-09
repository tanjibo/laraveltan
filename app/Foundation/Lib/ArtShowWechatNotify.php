<?php
/**
 * |--------------------------------------------------------------------------
 * |微信模板通知
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 17/10/2017
 * Time: 10:42 AM
 */

namespace App\Foundation\Lib;


use GuzzleHttp\Client;
use function GuzzleHttp\Psr7\build_query;
use Illuminate\Support\Facades\Redis;

class ArtShowWechatNotify
{
    const TOKEN                   = 'art_show_access_token';
    const ACCESS_TOKEN_URL_PREFIX = 'https://api.weixin.qq.com/cgi-bin/token?';
    const WECHAT_TEMPLATE_URL     = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?';


    /**
     * @return mixed
     * 获取 access_token
     */
    function accessToken()
    {
        if (Redis::exists(static::TOKEN)) {
            return Redis::get(static::TOKEN);
        }
        else {

            $params = build_query(
                [
                    'grant_type' => 'client_credential',
                    'appid'      => config('minilrss.art.appid'),
                    'secret'     => config('minilrss.art.secret'),
                ]
            );

            $client = new Client();

            $obj = $client->post(static::ACCESS_TOKEN_URL_PREFIX . $params);

            $model = json_decode($obj->getBody()->getContents(), true);

            if ($model[ 'access_token' ]) {

                Redis::setex(static::TOKEN, $model[ 'expires_in' ], $model[ 'access_token' ]);
                return $model[ 'access_token' ];
            }
            else {
                return '';
            }
        }

    }

    /**
     * 发送支付模板通知
     */
    public  function commentReply( $data )
    {

        if(!$data['art_open_id'] || !$data['form_id']) return false;

        $params = [
           'touser'      => $data['art_open_id'],
            'template_id' => 'xTFBxJx7usPgTUb715BFLV0oiGgF-PBGwr5-4_x66Q4',
            'page'        => 'pages/userComment?comment_id='.$data['parent_comment_id'],
            'form_id'     => $data['form_id'],
            'data'        => [
                //回复者
                'keyword1'    => [
                    'value' => $data['reply_user'],
                    'color' => '#182a68',
                ],
                //回复内容
                'keyword2'    => [
                    'value' =>$data['reply_comment'],
                    'color' => '#182a68',
                ],
                //回复时间
                'keyword3'    => [
                    'value' => $data['date'],
                    'color' => '#182a68',
                ],
                //讨论话题
                'keyword4'    => [
                    'value' => $data['art_show_name'],
                    'color' => '#182a68',
                ],
                //温馨提示
                'keyword5'    => [
                    'value' => '请关注小程序关联公众号"了如三舍"或"茶边求"以便收到最新资讯😊',
                    'color' => '#182a68',
                ]
            ],
        ];
           dd($this->accessToken());
        $this->post(static::WECHAT_TEMPLATE_URL.'access_token='.$this->accessToken(),json_encode($params));

    }

    private function post( $url, $params )
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, 0);         // 过滤HTTP头
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);   // 显示输出结果
        curl_setopt($curl, CURLOPT_POST, true);          // post传输
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params); // 传输数据
        $responseText = curl_exec($curl);
        curl_close($curl);
        return $responseText;
    }

}