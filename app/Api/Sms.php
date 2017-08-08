<?php

namespace App\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Database\Eloquent\Model;

class Sms extends Model
{
    /**
     * 类型
     */
    const TYPE_CAPTCHA                  = 1;    // 验证码验证码
    const TYPE_ORDER_PAID_WITH_USER     = 2;    // 商城订单已支付用户短信
    const TYPE_ORDER_SHIPPED_WITH_USER  = 3;    // 商城订单已发货用户短信
    const TYPE_ORDER_PAID_WITH_OPERATOR = 9;    // 商城订单已支付运营短信

    const TYPE_TEAROOM_BOOKING_WITH_USER     = 10;    // 茶舍预约用户短信
    const TYPE_TEAROOM_BOOKING_WITH_OPERATOR = 11;    // 茶舍预约茶艺师短信
    const TYPE_TEAROOM_CANCEL_WITH_OPERATOR  = 19;    // 茶舍取消茶艺师短信

    const TYPE_EXPERIENCE_BOOKING_WITH_USER     = 20;   // 体验店预约用户短信
    const TYPE_EXPERIENCE_PAID_WITH_USER        = 21;   // 体验店预约完成支付用户短信
    const TYPE_EXPERIENCE_BOOKING_WITH_OPERATOR = 22;   // 体验店预约运营短信
    const TYPE_EXPERIENCE_PAID_WITH_OPERATOR    = 23;   // 体验店预约完成支付运营短信
    const TYPE_EXPERIENCE_CANCEL_WITH_USER      = 24;   // 体验店取消预约用户短信
    const TYPE_EXPERIENCE_CANCEL_WITH_OPERATOR  = 25;   // 体验店取消预约运营短信

    const TYPE_EXPERIENCE_SPECIAL_BOOKING_WITH_USER            = 30;   // 体验店特殊房间预约用户短信
    const TYPE_EXPERIENCE_SPECIAL_BOOKING_PAID_WITH_USER       = 26;   // 体验店特殊房间预约完成支付用户短信
    const TYPE_EXPERIENCE_SPECIAL_BOOKING_CANCEL_WITH_USER     = 29;   // 体验店特殊房间取消预约用户短信
    const TYPE_EXPERIENCE_SPECIAL_BOOKING_WITH_OPERATOR        = 27;   // 体验店特殊房间预约运营短信
    const TYPE_EXPERIENCE_SPECIAL_BOOKING_PAID_WITH_OPERATOR   = 28;   // 体验店特殊房间预约完成支付运营短信
    const TYPE_EXPERIENCE_SPECIAL_BOOKING_CANCEL_WITH_OPERATOR = 30;   // 体验店特殊房间取消预约运营短信


    const TYPE_OTHER = 0;    // 其他

    /**
     * 验证码配置
     */
    const CAPTCHA_DELAY  = 60;  // 连续发送需间隔的秒数
    const CAPTCHA_LIMIT  = 6;   // 30分钟内最多发送条数
    const CAPTCHA_EXPIRY = 600; // 验证码有效时间

    /**
     * 短信接口配置
     * (示远科技)
     */
    const SMS_REQUEST  = 'http://send.18sms.com/msg/HttpBatchSendSM';   // 请求地址
    const SMS_ACCOUNT  = '10hi6t';      // 账号
    const SMS_PASSWORD = '9zI91A6V';    // 密码

    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'sms';

    /**
     * Disabled auto maintain timestamp
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable
        = [
            'mobile',
            'content',
            'type',
            'status',
            'created_at',
        ];

    /**
     * 短信模板
     *
     * @param integer $type 模板类型，参考常量 TYPE_xxxx
     * @param string
     */
    public static function template( $type, $params = [] )
    {
        extract($params);

        switch ( $type ) {
            // 验证码
            case self::TYPE_CAPTCHA:
                $template = '验证码：' . $captcha . '，' . (self::CAPTCHA_EXPIRY / 60) . '分钟内有效，登录过程中请勿将验证码告知他人并确认是您本人操作！';
                break;

            // 商城订单已支付用户短信
            case self::TYPE_ORDER_PAID_WITH_USER:
                $template = '您的订单' . $number . '已经支付成功！我们将尽快为您发货，请耐心等待，谢谢！';
                break;

            // 商城订单已发货用户短信
            case self::TYPE_ORDER_SHIPPED_WITH_USER:
                $template = '您的订单' . $number . '已经发货！运单号为：' . $expressName . ' ' . $expressNumber . '。你可以访问' . $expressName . '官网查看物流信息。贵重物品，请务必开箱验货确认后再签收。有任何问题请联络“了如三舍”微信公众号在线客服，谢谢！';
                break;

            // 商城订单已支付运营短信
            case self::TYPE_ORDER_PAID_WITH_OPERATOR:
                $template = '有一张新的订单已完成支付，订单号：' . $number . '，请即时登录后台查看！';
                break;

            // 茶舍预约用户短信
            case self::TYPE_TEAROOM_BOOKING_WITH_USER:
                $template = '您已成功预约 ' . $datetime . ' 安定门茶舍空间-' . $name . '，订单金额￥' . $fee . '。茶舍地址：北京市东城区安定门西大街9号。请您按时前往，消费后在店内微信扫码支付即可，谢谢！';
                break;

            // 茶舍预约茶艺师短信
            case self::TYPE_TEAROOM_BOOKING_WITH_OPERATOR:
                $template = $customer . $gender . ' ' . $mobile . ' 成功预约 ' . $datetime . ' 安定门茶舍空间 －' . $name . '，订单金额￥' . $fee . '。';
                break;

            // 茶舍取消茶艺师短信
            case self::TYPE_TEAROOM_CANCEL_WITH_OPERATOR:
                $template = $customer . $gender . ' ' . $mobile . ' 已取消预约 ' . $datetime . ' 安定门茶舍空间－' . $name . '，订单金额￥' . $fee . '。';
                break;

            // 体验店预约用户短信
            case self::TYPE_EXPERIENCE_BOOKING_WITH_USER:
                $template = '您已预订安吉体验中心 ' . $room . ' 房，入住日期' . $checkin . '，退房日期' . $checkout . '，订单金额¥' . $price . '，含双早。办理入住需在下午14:00后，退房在中午12:00前。地址：浙江省湖州市安吉县报福镇深溪三亩田村［了如三舍］（大石浪景区附近），联系电话：' . $mobile . '。';
                break;

            // 体验店预约支付完成用户短信
            case self::TYPE_EXPERIENCE_PAID_WITH_USER:
                $template = '您已成功支付安吉体验中心 ' . $room . ' 房，入住日期' . $checkin . '，退房日期' . $checkout . '，支付金额¥' . $price . '，含双早。联系电话：' . $mobile . '。';
                break;

            // 体验店预约运营短信
            case self::TYPE_EXPERIENCE_BOOKING_WITH_OPERATOR:
                $template = $customer . $sex . $user_mobile . '预订了安吉体验中心 ' . $room . ' 房，入住日期' . $checkin . '，退房日期' . $checkout . '，订单金额¥' . $price . '，请注意登录后台查看';
                break;

            // 体验店预约完成支付运营短信
            case self::TYPE_EXPERIENCE_PAID_WITH_OPERATOR:
                $template = $customer . $sex . $user_mobile . '支付了安吉体验中心 ' . $room . ' 房，入住日期' . $checkin . '，退房日期' . $checkout . '，支付金额¥' . $price . '，请注意登录后台查看';
                break;

            // 体验店取消预约用户短信
            case self::TYPE_EXPERIENCE_CANCEL_WITH_USER:
                $template = '您已取消安吉体验中心 ' . $room . ' 房，入住日期' . $checkin . '，退房日期' . $checkout . '，订单金额¥' . $price . '，含双早。欢迎再次预订，谢谢！';
                break;

            // 体验店取消预约运营短信
            case self::TYPE_EXPERIENCE_CANCEL_WITH_OPERATOR:
                $template = $customer . $sex . $user_mobile . '取消安吉体验中心 ' . $room . ' 房，入住日期' . $checkin . '，退房日期' . $checkout . '，订单金额¥' . $price . '，请注意登录后台查看';
                break;

            // 体验店特殊房间预约用户短信
            case static::TYPE_EXPERIENCE_SPECIAL_BOOKING_WITH_USER:
                $template = "您已预订{$date}了如三舍安吉体验中心－{$room}，订单金额{$price}。地址：浙江省湖州市安吉县报福镇深溪三亩田村［了如三舍］（大石浪景区附近），联系电话:{$mobile}。";
                break;
            // 体验店特殊房间预约完成支付用户短信
            case static::TYPE_EXPERIENCE_SPECIAL_BOOKING_PAID_WITH_USER:
                $template = "您已支付{$date}了如三舍安吉体验中心－{$room}，订单金额{$price}。地址：浙江省湖州市安吉县报福镇深溪三亩田村［了如三舍］（大石浪景区附近），联系电话:{$mobile}。";
                break;

            // 体验店特殊房间取消预约用户短信
            case static::TYPE_EXPERIENCE_SPECIAL_BOOKING_CANCEL_WITH_USER:
                $template = "您已取消{$date}了如三舍安吉体验中心－{$room}，订单金额{$price}。地址：浙江省湖州市安吉县报福镇深溪三亩田村［了如三舍］（大石浪景区附近），联系电话:{$mobile}。";
                break;
            // 体验店特殊房间预约运营短信
            case static::TYPE_EXPERIENCE_SPECIAL_BOOKING_WITH_OPERATOR:
                $template = "{$customer}{$sex}{$mobile}预订了{$date}了如三舍安吉体验中心－{$room}，订单金额{$price}。请注意登录后台查看。";
                break;
            // 体验店特殊房间预约完成支付运营短信
            case static::TYPE_EXPERIENCE_SPECIAL_BOOKING_PAID_WITH_OPERATOR:
                $template = "{$customer}{$sex}{$mobile}支付了{$date}了如三舍安吉体验中心－{$room}，订单金额{$price}。请注意登录后台查看。";
                break;

            // 体验店特殊房间取消预约运营短信
            case static::TYPE_EXPERIENCE_SPECIAL_BOOKING_CANCEL_WITH_OPERATOR:
                $template = "{$customer}{$sex}{$mobile}取消了{$date}了如三舍安吉体验中心－{$room}，订单金额{$price}。请注意登录后台查看。";
                break;

            default:
                $template = '';
                break;
        }
        return $template;
    }

    /**
     * 发送短信
     *
     * @param  string $mobile 手机号
     * @param  string $content 内容
     * @param integer $type 参考常量 TYPE_xxxx
     * @return boolean
     */
    public static function send( $mobile, $content, $type = self::TYPE_OTHER )
    {
        if (!$mobile || !$content)
            return false;

        // 开发环境不发送
//        if (\App::environment()=='develop') {
//            $result = self::store(
//                [
//                    'mobile'  => $mobile,
//                    'content' => $content,
//                    'type'    => $type,
//                    'status'  => -1,
//                ]
//            );
//            return true;
//        }

        // 示远科技短信接口
        $params = [
            'account' => self::SMS_ACCOUNT,
            'psåwd'    => self::SMS_PASSWORD,
            'mobile'  => (string)$mobile,
            'msg'     => $content,
        ];
        $client=new Client(['timeout'=>2,'base_uri'=>'http://send.18sms.com']);
       $res= $client->post('/msg/HttpBatchSendSM',$params);

//        $result = \HttpResponse::post(self::SMS_REQUEST, http_build_query($params));
//        $result = explode(',', $result);
       dd($res->getBody());
        // 保存短信记录
//        self::store(
//            [
//                'mobile'  => $mobile,
//                'content' => $content,
//                'type'    => $type,
//                'status'  => $result[ 1 ] ? -2 : 0,
//            ]
//        );
//        return !(bool)$result[ 1 ];
    }

    /**
     * 发送手机验证码
     *
     * @param  string $mobile 手机号
     * @return true：发送成功，-1：发送过于频繁， -2：发送次数过多，false：发送失败
     */
    public static function sendCaptcha( $mobile )
    {
        if (!$mobile)
            return false;

        $time = $_SERVER[ 'REQUEST_TIME' ];

        // 半小时内发送过的验证码短信
        $logs = self::select('id', 'created_at')
                    ->where('mobile', $mobile)
                    ->whereBetween('created_at', [ $time - 1800, $time ])
                    ->where('type', self::TYPE_CAPTCHA)
                    ->orderBy('created_at', 'DESC')
                    ->get()
        ;

        if (count($logs)) {
            // 发送过于频繁
            if ($time - $logs[ 0 ]->created_at < self::CAPTCHA_DELAY) {
                return -1;
            }
            // 发送次数过多
            if (count($logs) > self::CAPTCHA_LIMIT) {
                return -2;
            }
        }

        $captcha = self::createCaptcha();

        // 验证码存储到Redis
        if (!self::saveCaptcha($mobile, $captcha))
            return false;

        $template = self::template(self::TYPE_CAPTCHA, [ 'captcha' => $captcha ]);
        return self::send($mobile, $template, self::TYPE_CAPTCHA);
    }

    /**
     * 验证验证码
     *
     * @param  string $mobile 手机
     * @param  string $captcha 验证码
     * @return boolean
     */
    public static function validateCaptcha( $mobile, $captcha )
    {
        // 开发环境不验证
        if (YAF_ENVIRON == 'develop') {
            return true;
        }

        if (!$data = \Cache::get('mobileCaptcha' . $mobile))
            return false;

        return $data == $captcha;
    }

    /**
     * 保存验证码
     *
     * @param  string $mobile 手机
     * @param  string $captcha 验证码
     * @return boolean
     */
    public static function saveCaptcha( $mobile, $captcha )
    {
        return \Cache::set('mobileCaptcha' . $mobile, $captcha, self::CAPTCHA_EXPIRY);
    }

    /**
     * 生成验证码
     *
     * @return string
     */
    public static function createCaptcha()
    {
        $seed    = '0123456789';
        $captcha = '';
        for ( $i = 0; $i < 6; ++$i ) {
            $captcha .= $seed[ mt_rand(0, 9) ];
        }
        return $captcha;
    }

    /**
     * 存储数据
     *
     * @param  array $data [...]
     * @return boolean
     */
    public static function store( array $data )
    {
        if (!$data)
            return false;

        $data[ 'created_at' ] = date('Y-m-d H:i:s');
        return self::create($data);
    }
}
