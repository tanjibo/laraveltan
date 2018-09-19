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


    const TYPE_TEAROOM_BOOKING_WITH_USER     = 10;    // 茶舍预约用户短信
    const TYPE_TEAROOM_BOOKING_WITH_OPERATOR = 11;    // 茶舍预约茶艺师短信
    const TYPE_TEAROOM_CANCEL_WITH_OPERATOR  = 19;    // 茶舍取消茶艺师短信


    const TYPE_EXPERIENCE_PAID_WITH_USER       = 21;   // 体验店预约完成支付用户短信
    const TYPE_EXPERIENCE_PAID_WITH_OPERATOR   = 23;   // 体验店预约完成支付运营短信
    const TYPE_EXPERIENCE_CANCEL_WITH_USER     = 24;   // 体验店取消预约用户短信
    const TYPE_EXPERIENCE_CANCEL_WITH_OPERATOR = 25;   // 体验店取消预约运营短信
    const TYPE_EXPERIENCE_UNPAY_WITH_USER      = 20;   // 体验店预约用户短信
    const TYPE_EXPERIENCE_UNPAY_WITH_OPERATOR  = 22;   // 体验店预约运营短信


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
     * @return string
     * 单独发送短信
     */
    public static function singleSendSmTemplate(){
        $room_arr_id=[];
        $rooms       = collect(\request()->rooms)->map(
            function( $item ) use(&$room_arr_id) {
                $arr=explode(":",$item);
                array_push($room_arr_id,$arr[1]);
                return '【' . current($arr) . '】';
            }
        )->implode('和')
        ;
        $checkin     = \request()->checkin;
        $checkout    = \request()->checkout;
        $price       = ExperienceBooking::calculateFee($checkin,$checkout,$room_arr_id);
        $mobile      = '13051747151'; //客服电话

        switch ( \request()->smStatus ) {


            // 体验店预约未支付/支付定金用户短信-------------------------------------------------
            case self::TYPE_EXPERIENCE_UNPAY_WITH_USER:
                return '您已预订安吉体验中心 ' . $rooms . ' 房，入住日期' . $checkin . '，退房日期' . $checkout . '，订单金额¥' . $price . '，含双早。办理入住需在下午14:00后，退房在中午12:00前。地址：浙江省湖州市安吉县报福镇深溪三亩田村［了如三舍］（大石浪景区附近），联系电话：' . $mobile . '。';

            // 体验店预约支付完成用户短信----------------------------------------------------------
            case self::TYPE_EXPERIENCE_PAID_WITH_USER:

                return '您已成功支付安吉体验中心 ' . $rooms . ' 房，入住日期' . $checkin . '，退房日期' . $checkout . '，支付金额¥' . $price . '，含双早。联系电话：' . $mobile . '。';


            // 体验店取消预约用户短信------------------------------------------------------------
            case self::TYPE_EXPERIENCE_CANCEL_WITH_USER:
                return '您已取消安吉体验中心 ' . $rooms . ' 房，入住日期' . $checkin . '，退房日期' . $checkout . '，订单金额¥' . $price . '，含双早。欢迎再次预订，谢谢！';
            default:
                return '';

        }
    }
    /**
     * 短信模板
     *
     * @param integer $type 模板类型，参考常量 TYPE_xxxx
     * @param string
     */
    public static function template( $type, ExperienceBooking $booking )
    {
        $rooms       = $booking->rooms->pluck('name')->map(
            function( $item ) {
                return '【' . $item . '】';
            }
        )->implode('和')
        ;
        $checkin     = $booking->checkin;
        $checkout    = $booking->checkout;
        $price       = $booking->real_price;
        $mobile      = '13051747151'; //客服电话
        $customer    = $booking->customer;
        $user_mobile = $booking->mobile;
        $sex         = $booking->gender == 1 ? '先生' : '女士';

        switch ( $type ) {


            // 体验店预约未支付/支付定金用户短信-------------------------------------------------
            case self::TYPE_EXPERIENCE_UNPAY_WITH_USER:
                return '您已预订安吉体验中心 ' . $rooms . ' 房，入住日期' . $checkin . '，退房日期' . $checkout . '，订单金额¥' . $price . '，含双早。办理入住需在下午14:00后，退房在中午12:00前。地址：浙江省湖州市安吉县报福镇深溪三亩田村［了如三舍］（大石浪景区附近），联系电话：' . $mobile . '。';

            // 体验店预约运营短信
            case self::TYPE_EXPERIENCE_UNPAY_WITH_OPERATOR:
                return $customer . $sex . $user_mobile . '预订了安吉体验中心 ' . $rooms . ' 房，入住日期' . $checkin . '，退房日期' . $checkout . '，订单金额¥' . $price . '，请注意登录后台查看';

            // 体验店预约支付完成用户短信----------------------------------------------------------
            case self::TYPE_EXPERIENCE_PAID_WITH_USER:

                return '您已成功支付安吉体验中心 ' . $rooms . ' 房，入住日期' . $checkin . '，退房日期' . $checkout . '，支付金额¥' . $price . '，含双早。联系电话：' . $mobile . '。';


            // 体验店预约完成支付运营短信
            case self::TYPE_EXPERIENCE_PAID_WITH_OPERATOR:
                return $customer . $sex . $user_mobile . '支付了安吉体验中心 ' . $rooms . ' 房，入住日期' . $checkin . '，退房日期' . $checkout . '，支付金额¥' . $price . '，请注意登录后台查看';


            // 体验店取消预约用户短信------------------------------------------------------------
            case self::TYPE_EXPERIENCE_CANCEL_WITH_USER:
                return '您已取消安吉体验中心 ' . $rooms . ' 房，入住日期' . $checkin . '，退房日期' . $checkout . '，订单金额¥' . $price . '，含双早。欢迎再次预订，谢谢！';


            // 体验店取消预约运营短信
            case self::TYPE_EXPERIENCE_CANCEL_WITH_OPERATOR:
                return $customer . $sex . $user_mobile . '取消安吉体验中心 ' . $rooms . ' 房，入住日期' . $checkin . '，退房日期' . $checkout . '，订单金额¥' . $price . '，请注意登录后台查看';

            default:
                return '';

        }

    }

    /**
     * @param $type
     * @param TearoomBooking $booking
     * @return string
     * 茶社模板
     */
    public static function tearoomTemplate( $type, TearoomBooking $booking )
    {


        $datetime = $booking->date . ' ' . $booking->time;
        $name     = $booking->tearoom->name;
        $customer = $booking->customer;
        $gender   = $booking->gender ? '先生' : '女士';
        $mobile   = $booking->mobile;
        $fee      = $booking->fee;

        switch ( $type ) {

            // 茶舍预约用户短信
            case self::TYPE_TEAROOM_BOOKING_WITH_USER:
                return '您已成功预约 ' . $datetime . ' 安定门茶舍空间-' . $name . '，订单金额￥' . $fee . '。茶舍地址：北京市东城区安定门西大街9号。请您按时前往，消费后在店内微信扫码支付即可，谢谢！';

            // 茶舍预约茶艺师短信
            case self::TYPE_TEAROOM_BOOKING_WITH_OPERATOR:
                return $customer . $gender . ' ' . $mobile . ' 成功预约 ' . $datetime . ' 安定门茶舍空间 －' . $name . '，订单金额￥' . $fee . '。';


            // 茶舍取消茶艺师短信
            case self::TYPE_TEAROOM_CANCEL_WITH_OPERATOR:
                return $customer . $gender . ' ' . $mobile . ' 已取消预约 ' . $datetime . ' 安定门茶舍空间－' . $name . '，订单金额￥' . $fee . '。';

        }

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
                'status'  => $result[ 1 ] ?? 0,
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
