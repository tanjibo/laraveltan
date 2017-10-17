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


use App\Models\ExperienceBooking;
use GuzzleHttp\Client;
use function GuzzleHttp\Psr7\build_query;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class WechatTemplate
{
    const TOKEN                   = 'wechat_access_token';
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
                    'appid'      => config('wxxcx.appid'),
                    'secret'     => config('wxxcx.secret'),
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
    function sendPayTpl( $prepay_id,$booking_id )
    {
        $model=ExperienceBooking::query()->with('rooms')->find($booking_id);

        if(!$model) return false;

        $rooms='您预订的房间:'.$model->rooms->pluck('name')->map(function($item){
                return '【'.$item.'】';
            })->implode('和').'. PS:请关注小程序关联公众号"了如三舍"以便收到最新资讯😊';

        $params = [
            'touser'      => Auth::user() ?Auth::user()->mini_open_id:'oIu4X0aZSiZsJ0VwxzNmnW3pxxbs',
            'template_id' => 'rqPgeD210gaWSd032szu1b2SGM73Fr1pZanleCIsfJw',
            'page'        => 'order',
            'form_id'     => $prepay_id,
            'data'        => [
                //入住时间
                'keyword1'    => [
                    'value' => $model->checkin,
                    'color' => '#182a68',
                ],
                //退房时间
                'keyword2'    => [
                    'value' => $model->checkout,
                    'color' => '#182a68',
                ],
                //联系人姓名
                'keyword3'    => [
                    'value' => $model->customer,
                    'color' => '#182a68',
                ],
                //联系人手机
                'keyword4'    => [
                    'value' => $model->mobile,
                    'color' => '',
                ],
                //地点
                'keyword5'    => [
                    'value' => '浙江省湖州市安吉县报福镇三亩田自然村(大石浪景区附近)',
                    'color' => '#182a68',
                ],
                //客服电话
                'keyword6'    => [
                    'value' => '13051747151',
                    'color' => '#182a68',
                ],
                //备注
                'keyword7'    => [
                    'value' => $rooms,
                    'color' => '#cc6844',
                ],

            ],
        ];

        $client=new Client();
      $data=$client->post(static::WECHAT_TEMPLATE_URL.'access_token='.$this->accessToken(),['body'=>$params]);
      dd(json_decode($data->getBody()->getContents(),true));

    }

    /**
     * @param $form_id
     * 发送支付退款通知
     */
    function sendCancelTpl($form_id){
        $params = [
            'touser'      => Auth::user()->mini_open_id ?: '',
            'template_id' => 'ShNkGQ8IvSzpQQofYcf87QYzkB5dT4JacErxfSQxVek',
            'page'        => 'order',
            'form_id'     => $form_id,
            'data'        => [
                //房间信息
                'keyword1'    => [
                    'value' => '',
                    'color' => '',
                ],
                //订单退款
                'keyword2'    => [
                    'value' => '',
                    'color' => '',
                ],
                //取消时间
                'keyword3'    => [
                    'value' => '',
                    'color' => '',
                ],
                //备注
                'keyword4'    => [
                    'value' => '',
                    'color' => '',
                ],
            ],
        ];
    }
}