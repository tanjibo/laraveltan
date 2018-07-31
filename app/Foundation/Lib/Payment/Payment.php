<?php

namespace App\Foundation\Lib\Payment;

use App\Exceptions\InternalException;


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

    static public function unifiedorder( array $val )
    {
        extract($val);
        $params = [
            'out_trade_no'     => $number,
            'body'             => $body,
            'total_fee'        => (double)$fee * 100,
            'notify_url'       => 'https://' . $_SERVER[ 'HTTP_HOST' ] . $notify,
            'trade_type'       => 'JSAPI',
            'openid'           => $openid,
            'appid'            => $appid,
            'mch_id'           => $mch_id,
            'spbill_create_ip' => request()->getClientIp(),
            'nonce_str'        => static::createNoncestr(),
        ];

        $params[ 'sign' ] = static::createSign($params, $pay_key);
        $xml              = static::array2xml($params);

        // 获取预支付ID
        $data = static::post('https://api.mch.weixin.qq.com/pay/unifiedorder', $xml);

        $data = static::xml2array($data);

        if (!isset($data[ 'prepay_id' ])) {
            throw new InternalException('支付发生错误:' . $data[ 'err_code_des' ]);
        }
        $prepayId = $data[ 'prepay_id' ];

        //再次签名
        $time                    = time();
        $parameters              = [
            'appId'     => $appid,
            'timeStamp' => "$time",
            'nonceStr'  => static::createNoncestr(),
            'package'   => "prepay_id=$prepayId",
            'signType'  => "MD5",
        ];
        $parameters[ 'paySign' ] = static::createSign($parameters, $pay_key);
        unset($parameters[ 'appId' ]);
        return $parameters;

    }

    /**
     * @param $number
     * @param $fee
     * array:18 [
     * "return_code" => "SUCCESS"
     * "return_msg" => "OK"
     * "appid" => "wx28cb8b1d2b4514d0"
     * "mch_id" => "1376613602"
     * "nonce_str" => "NphYD7vfzGxs0SA4"
     * "sign" => "C587C7CFBC334D69C371C65B5B453B3D"
     * "result_code" => "SUCCESS"
     * "transaction_id" => "4200000019201710178620678951"
     * "out_trade_no" => "E000000000203"
     * "out_refund_no" => "233333422333"
     * "refund_id" => "50000704712017101702050579395"
     * "refund_channel" => []
     * "refund_fee" => "10"
     * "coupon_refund_fee" => "0"
     * "total_fee" => "10"
     * "cash_fee" => "10"
     * "coupon_refund_count" => "0"
     * "cash_refund_fee" => "10"
     * ]
     */

    static public function orderRefund( array $val )
    {
        extract($val);
        $params           = [
            'appid'         => $appid,
            'mch_id'        => $mch_id,
            'nonce_str'     => static::createNoncestr(),
            'out_trade_no'  => $number,
            'out_refund_no' => static::refundId(),//自己系统退款单号
            'total_fee'     => (double)$fee * 100,
            'refund_fee'    => (double)$fee * 100,
            'op_user_id'    => $mch_id,
            // 'sign_type'     => 'MD5',
        ];
        $params[ 'sign' ] = static::createSign($params, $pay_key);
        $xml              = static::array2xml($params);

        // 获取预支付ID
        $data = static::postSsl('https://api.mch.weixin.qq.com/secapi/pay/refund', $xml);

        $result = static::xml2array($data);

        if ($result[ 'return_code' ] == 'SUCCESS') {
            return $result;
        }
        return false;
    }

    /**
     * @return string
     * 自己系统生成的退款订单号
     */
    private static function refundId()
    {
        return date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
    }

    /**
     * @param $url
     * @param $params
     * @return bool|mixed
     * 不能再本地调试，而且不能再post 方法中调用，你要不要这么坑爹
     */
    private static function postSsl( $url, $params )
    {
        $ch = curl_init();
        //超时时间
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //这里设置代理，如果有的话
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        //第一种方法，cert 与 key 分别属于两个.pem文件
        //默认格式为PEM，可以注释
        curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
        curl_setopt($ch, CURLOPT_SSLCERT, storage_path() . '/wechatKey/apiclient_cert.pem');
        //默认格式为PEM，可以注释
        curl_setopt($ch, CURLOPT_SSLKEYTYPE, 'PEM');
        curl_setopt($ch, CURLOPT_SSLKEY, storage_path() . '/wechatKey/apiclient_key.pem');

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        $data = curl_exec($ch);
        if ($data) {
            curl_close($ch);
            return $data;
        }
        else {
            $error = curl_errno($ch);
            echo "call faild, errorCode:$error\n";
            curl_close($ch);
            return false;
        }
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
    public static function createSign( $params, $pay_key )
    {
        ksort($params);
        $str = '';
        foreach ( $params as $k => $v ) {
            $str .= $k . '=' . $v . '&';
        }
        $str = $str . 'key=' . $pay_key;
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


    /**
     * @return array|bool
     * 微信支付回调
     */
   static public function wechatNotify( $pay_key )
    {
        $xml = file_get_contents("php://input");

        $data = static::xml2array($xml);
        $sign = $data[ 'sign' ];
        unset($data[ 'sign' ]);

        if ($sign == static::createSign($data, $pay_key)) {
            $returnXml = static::array2xml([ 'return_code' => 'SUCCESS' ]);
            if ($data[ "return_code" ] == "FAIL") {
                $data[ 'message' ] = '通信出错';
            }
            else if ($data[ "result_code" ] == "FAIL") {
                $data[ 'message' ] = '业务出错';
            }
            else {
                $data[ 'message' ] = '支付成功';
            }
        }
        else {
            $returnXml = static::array2xml([ 'return_code' => 'FAIL', 'return_msg' => '签名失败' ]);
            $data      = false;
        }
// 返回结果给微信
        echo $returnXml;
        return $data;
    }
}