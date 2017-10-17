<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\App;
use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Sm
 *
 * @property int $id
 * @property string $mobile
 * @property string $content
 * @property int $type
 * @property int $status
 * @property \Carbon\Carbon $created_at
 *
 * @package App\Models
 */
class Sm extends Eloquent
{


    const TYPE_EXPERIENCE_PAID_WITH_USER       = 21;   // 体验店预约完成支付用户短信
    const TYPE_EXPERIENCE_PAID_WITH_OPERATOR   = 23;   // 体验店预约完成支付运营短信
    const TYPE_EXPERIENCE_CANCEL_WITH_USER     = 24;   // 体验店取消预约用户短信
    const TYPE_EXPERIENCE_CANCEL_WITH_OPERATOR = 25;   // 体验店取消预约运营短信


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
    public static function template( $type, ExperienceBooking $booking )
    {
        $rooms = $booking->rooms->pluck('name')->map(
            function( $item ) {
                return '【' . $item . '】';
            }
        )->implode('和')
        ;
        $checkin     = $booking->checkin->toDateString();
        $checkout    = $booking->checkout->toDateString();
        $price       = $booking->real_price;
        $mobile      = '13051747151'; //客服电话
        $customer    = $booking->customer;
        $user_mobile = $booking->mobile;
        $sex         = $booking->gender == 1 ? '先生' : '女士';

        switch ( $type ) {

            // 体验店预约支付完成用户短信
            case self::TYPE_EXPERIENCE_PAID_WITH_USER:

                $template = '您已成功支付安吉体验中心 ' . $rooms . ' 房，入住日期' . $checkin . '，退房日期' . $checkout . '，支付金额¥' . $price . '，含双早。联系电话：' . $mobile . '。';
                break;


            // 体验店预约完成支付运营短信
            case self::TYPE_EXPERIENCE_PAID_WITH_OPERATOR:
                $template = $customer . $sex . $user_mobile . '支付了安吉体验中心 ' . $rooms . ' 房，入住日期' . $checkin . '，退房日期' . $checkout . '，支付金额¥' . $price . '，请注意登录后台查看';
                break;

            // 体验店取消预约用户短信
            case self::TYPE_EXPERIENCE_CANCEL_WITH_USER:
                $template = '您已取消安吉体验中心 ' . $rooms . ' 房，入住日期' . $checkin . '，退房日期' . $checkout . '，订单金额¥' . $price . '，含双早。欢迎再次预订，谢谢！';
                break;

            // 体验店取消预约运营短信
            case self::TYPE_EXPERIENCE_CANCEL_WITH_OPERATOR:
                $template = $customer . $sex . $user_mobile . '取消安吉体验中心 ' . $rooms . ' 房，入住日期' . $checkin . '，退房日期' . $checkout . '，订单金额¥' . $price . '，请注意登录后台查看';
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
//        if (App::environment() == 'develop') {
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
            'pswd'    => self::SMS_PASSWORD,
            'mobile'  => (string)$mobile,
            'msg'     => $content,
        ];
        //使用guzzle 发送http
        $client  = new Client([ 'timeout' => 2 ]);
        $request = new Request('POST', self::SMS_REQUEST);
        $res     = $client->send($request, [ 'form_params' => $params ]);

        $result = explode(',', $res->getBody()->getContents());

        // 保存短信记录
        self::store(
            [
                'mobile'  => $mobile,
                'content' => $content,
                'type'    => $type,
                'status'  => $result[ 1 ] ? -2 : 0,
            ]
        );

        return !(bool)$result[ 1 ];
    }


    /**
     * 存储数据
     *
     * @param  array $data [...]
     */
    public static function store( array $data )
    {
        if (!$data)
            return false;

        $data[ 'created_at' ] = date('Y-m-d H:i:s');
        return static::query()->create($data);
    }
}
