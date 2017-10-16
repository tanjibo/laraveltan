<?php

namespace App\Foundation\Lib;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;


/**
 * |--------------------------------------------------------------------------
 * |支付
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 16/10/2017
 * Time: 10:52 AM
 */
class Payment
{

    static public function unifiedorder( $number, $fee, $body, $notifyUrl )
    {
        $params = [
            'out_trade_no'     => $number,
            'body'             => $body,
            'total_fee'        => (double)$fee * 100,
            'notify_url'       => 'https://' . $_SERVER[ 'HTTP_HOST' ] . $notifyUrl,
            'trade_type'       => 'JSAPI',
            'openid'           => Auth::user() ? Auth::user()->mini_open_id : 'oIu4X0aZSiZsJ0VwxzNmnW3pxxbs',
            'appid'            => config('wxxcx.appid'),
            'mch_id'           => config('pay.mch_id'),
            'spbill_create_ip' => Request::getClientIp(),
            'nonce_str'        => static::createNoncestr(),
        ];

        $params[ 'sign' ] = static::createSign($params);
        $xml              = static::array2xml($params);

        // 获取预支付ID
        $data = static::post('https://api.mch.weixin.qq.com/pay/unifiedorder', $xml);
        $data = static::xml2array($data);
        if (!isset($data[ 'prepay_id' ]))
            return false;
        $prepayId = $data[ 'prepay_id' ];

        //再次签名
        $parameters              = [
            'appId'     => config('wxxcx.app_id'),
            'timeStamp' => time(),
            'nonceStr'  => static::createNoncestr(),
            'package'   => "prepay_id=$prepayId",
            'signType'  => "MD5",
        ];
        $parameters[ 'paySign' ] = static::createSign($parameters);
        unset($parameters[ 'appId' ]);
        return $parameters;

    }

    public static function post( $url, $params )
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

    /**
     * 生成签名
     * @param  array $params 参数
     * @return string
     */
    public static function createSign( $params )
    {
        ksort($params);
        $str = '';
        foreach ( $params as $k => $v ) {
            $str .= $k . '=' . $v . '&';
        }
        $str = $str . 'key=' . config('pay.pay_key');
        $str = md5($str);
        return strtoupper($str);
    }

    /**
     * 产生随机字符串，不长于32位
     * @param  integer $length 长度
     * @return string
     */
    public static function createNoncestr( $length = 32 )
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str   = "";
        for ( $i = 0; $i < $length; ++$i ) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    /**
     * array转xml
     * @param  array $params 参数
     * @return string
     */
    public static function array2xml( $params )
    {
        $xml = "<xml>";
        foreach ( $params as $key => $val ) {
            if (is_numeric($val)) {
                $xml .= '<' . $key . '>' . $val . '</' . $key . '>';
            }
            else {
                $xml .= '<' . $key . '><![CDATA[' . $val . ']]></' . $key . '>';
            }
        }
        $xml .= '</xml>';

        return $xml;
    }

    /**
     * xml转array
     * @param  string $xml XML
     * @return array
     */
    public static function xml2array( $xml )
    {
        $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $array_data;
    }
}