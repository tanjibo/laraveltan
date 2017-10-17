<?php
/**
 * |--------------------------------------------------------------------------
 * |微信公众号模板通知
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 17/10/2017
 * Time: 4:34 PM
 */

namespace App\Foundation\Lib;


use App\Models\ExperienceBooking;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redis;

class WechatSmsNotify
{

    private static $urlfix = 'https://api.weixin.qq.com/cgi-bin';

    private static $tplUrl = 'https://api.weixin.qq.com/cgi-bin/message/template/send';


    const TYPE_EXPERIENCE_PAID_WITH_USER       = 21;   // 体验店预约完成支付用户短信
    const TYPE_EXPERIENCE_PAID_WITH_OPERATOR   = 23;   // 体验店预约完成支付运营短信
    const TYPE_EXPERIENCE_CANCEL_WITH_USER     = 24;   // 体验店取消预约用户短信
    const TYPE_EXPERIENCE_CANCEL_WITH_OPERATOR = 25;   // 体验店取消预约运营短信


    /**
     * @return 获取access_token  要区别这个和授权的
     */
    private static function getAccessToken()
    {

        if (Redis::exists('api_access_token2')) return Redis::get('api_access_token2');
        $data = [
            'grant_type' => 'client_credential',
            'appid'      => config('lrss.wechatSms.app_id'),
            'secret'     => config('lrss.wechatSms.secret_id'),
        ];
        $url  = static::$urlfix . '/token';

        $access_token = json_decode(static::httpGet($url, $data), true);

        Redis::setEx('api_access_token2', $access_token[ 'expires_in' ], $access_token[ 'access_token' ]);

        return $access_token[ 'access_token' ];

    }


    /**
     * 发送微信模板消息
     */
    public static function sendTpl( ExperienceBooking $booking, string $open_id, int $type )
    {
        $tmlData = [
            'touser'      => App::environment() == 'develop' ? config('lrss.wechatSms.open_id') : $open_id,
            'template_id' => static::getTplId($type),
            'url'         => '',  //跳转地址
        ];

        $sendData = [
            'room' =>$booking->rooms->pluck('name')->map(
                    function( $item ) {
                        return '【' . $item . '】';
                    }
                )->implode('和'),

            'checkin'     => $booking->checkin->toDateString(),
            'checkout'    => $booking->checkout->toDateString(),
            'price'       => $booking->real_price,
            'mobile'      => 13051747151, //客服电话
            'customer'    => $booking->customer,
            'user_mobile' => $booking->mobile,
            'sex'         => $booking->gender == 1 ? '先生' : '女士',
        ];

        $postData = collect($sendData)->map(
            function( $name, $key ) {

                return [
                    'value' => $key == 'price' ? number_format($name, 2, '.', ',') : $name,
                    'color' => $key == 'price' ? '#DC143C' : '#173177',
                ];
            }
        )->toArray()
        ;

        $tmlData[ 'data' ] = static::combineTplData($type, $postData);

        $data = json_encode($tmlData);
        $url  = static::$tplUrl . '?' . http_build_query([ 'access_token' => static::getAccessToken() ]);

        return static::httpPost($url, $data);
    }


    /**
     * @param int $type
     * @param array $postData
     * @return array
     * 组合数据
     */
    private static function combineTplData( int $type, array $postData )
    {
        $data = [];
        switch ( $type ) {

            case self::TYPE_EXPERIENCE_PAID_WITH_OPERATOR:     // 体验店预约完成支付运营短信

                foreach ( $postData as $key => $v ) {

                    if ($key == 'customer') $data[ 'first' ] = [ 'value' => $v[ 'value' ] . '支付成功', 'color' => $v[ 'color' ] ];
                    if ($key == 'checkin') $data[ 'keyword3' ] = $v;
                    if ($key == 'checkout') $data[ 'keyword4' ] = $v;
                    if ($key == 'price') $data[ 'keyword5' ] = $v;
                    if ($key == 'room') $data[ 'keyword2' ] = $v;
                    if ($key == 'customer') $data[ 'keyword1' ] = $v;

                }
                break;

            case self::TYPE_EXPERIENCE_CANCEL_WITH_OPERATOR:  // 体验店取消预约运营短信
                foreach ( $postData as $key => $v ) {
                    if ($key == 'customer') $data[ 'first' ] = [ 'value' => $v[ 'value' ] . '取消预约', 'color' => $v[ 'color' ] ];
                    if ($key == 'checkin') $data[ 'keyword2' ] = $v;
                    if ($key == 'checkout') $data[ 'keyword3' ] = $v;
                    if ($key == 'price') $data[ 'keyword4' ] = $v;
                    if ($key == 'room') $data[ 'keyword1' ] = $v;

                }
                break;


        }
        $data[ 'remark' ] = [ 'value' => '请后台查看详情', 'color' => '#173177' ];

        return $data;
    }

    /**
     * @param int $type
     * @return string 获取模板id
     */
    private static function getTplId( int $type )
    {
        switch ( $type ) {
            case self::TYPE_EXPERIENCE_PAID_WITH_OPERATOR:     // 体验店预约完成支付运营短信
                return config('lrss.wechatSms.template_id_pay_success');

            case self::TYPE_EXPERIENCE_CANCEL_WITH_OPERATOR:  // 体验店取消预约运营短信
                return config('lrss.wechatSms.template_id_cancel');
        }
    }


    /**
     * @param $url
     * @param $param
     * @return mixed
     * @throws \Exception
     */
    static private function httpGet( $url, $param )
    {
        $opts = [
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_URL            => $url . '?' . http_build_query($param),
        ];
        /* 初始化并执行curl请求 */
        $ch = curl_init();
        curl_setopt_array($ch, $opts);
        $data  = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        //发生错误，抛出异常
        if ($error) throw new \Exception('请求发生错误：' . $error);
        return $data;
    }

    /**
     * @param $url
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    static private function httpPost( $url, $data )
    {
        $opts = [
            CURLOPT_URL            => $url,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_POST           => 1,
            CURLOPT_POSTFIELDS     => $data,
        ];
        if (is_string($data)) { //发送JSON数据
            $opts[ CURLOPT_HTTPHEADER ] = [
                'Content-Type: application/json; charset=utf-8',
                'Content-Length: ' . strlen($data),
            ];
        }

        /* 初始化并执行curl请求 */
        $ch = curl_init();
        curl_setopt_array($ch, $opts);
        $data  = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        //发生错误，抛出异常
        if ($error) throw new \Exception('请求发生错误：' . $error);
        return $data;
    }


}