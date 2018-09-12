<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Nicolaslopezj\Searchable\SearchableTrait;
use Reliese\Database\Eloquent\Model as Eloquent;
use Repositories\ExperienceRoomBookingRepository;

/**
 * Class ExperienceBooking
 *
 * @property int $id
 * @property int $user_id
 * @property \Carbon\Carbon $checkin
 * @property \Carbon\Carbon $checkout
 * @property int $price
 * @property int $balance
 * @property int $real_price
 * @property int $pay_mode
 * @property string $customer
 * @property int $gender
 * @property string $mobile
 * @property int $people
 * @property string $requirements
 * @property string $remark
 * @property int $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @property int $is_comment
 * @property string $partner
 *
 * @property \App\Models\User $user
 * @property \Illuminate\Database\Eloquent\Collection $experience_booking_rooms
 *
 * @package App\Models
 */
class ExperienceBooking extends Eloquent
{
    use ExperienceRoomBookingTrait,
        \Illuminate\Database\Eloquent\SoftDeletes;
    use Notifiable;
    use ModelTrait;
    protected $table = 'experience_booking';

    protected $casts
        = [
            'user_id'    => 'int',
            'price'      => 'int',
            'balance'    => 'int',
            'real_price' => 'int',
            'pay_mode'   => 'int',
            'gender'     => 'int',
            'people'     => 'int',
            'status'     => 'int',
            'is_comment' => 'int',
            'is_prepay'  => 'int',
        ];

    /**
     * 订单状态
     */
    const STATUS_UNPAID   = 0;    // 待支付
    const STATUS_PAID     = 1;    // 已支付
    const STATUS_CHECKIN  = 2;    // 已入住
    const STATUS_COMPLETE = 10;   // 已完成
    const STATUS_CANCEL   = -10;  // 已取消
    const STATUS_DEL      = -100;  // 已删除
    const STATUS_SUCCESS_REFUND      = -11;  // 完成用户退款

    /**
     * 支付方式
     */
    const PAY_MODE_OFFLINE = 0;    // 线下支付
    const PAY_MODE_WECHAT  = 1;    // 微信支付
    const PAY_MODE_BALANCE = 10;   // 余额支付

    /**
     * 是否已退款
     */
    const STATUS_NO_REFUND = 0;    // 不用退款
    const STATUS_UNREFUND  = 1;    // 未退款
    const STATUS_REFUNDED =2 ;   // 已退款

    protected $dates
        = [
            'checkin',
            'checkout',
        ];

    protected $searchable
        = [
            /**
             * Columns and their priority in search results.
             * Columns with higher values are more important.
             * Columns with equal values have equal importance.
             *
             * @var array
             */
            'columns' => [
                'experience_booking.id'           => 10,
                'experience_booking.checkin'      => 5,
                'experience_booking.checkout'     => 5,
                'experience_booking.price'        => 5,
                'experience_booking.real_price'   => 5,
                'experience_booking.customer'     => 5,
                'experience_booking.user_id'      => 5,
                'experience_booking.mobile'       => 5,
                'experience_booking.requirements' => 20,
                'experience_booking.remark'       => 20,
                'user.nickname'                   => 5,
                'user.mobile'                     => 5,
                'experience_room.name'            => 5,

            ],
            'joins'   => [
                'user'                    => [ 'user.id', 'experience_booking.user_id' ],
                'experience_booking_room' => [ 'experience_booking_room.experience_booking_id', 'experience_booking.id' ],
                'experience_room'         => [
                    'experience_room.id', 'experience_booking_room.experience_room_id',
                ],
            ],
        ];

    protected $fillable
        = [
            'user_id',
            'checkin',
            'checkout',
            'price',
            'balance',
            'real_price',
            'pay_mode',
            'customer',
            'gender',
            'mobile',
            'people',
            'requirements',
            'remark',
            'status',
            'is_comment',
            'partner',
            'source',
            'is_prepay',
            "is_refund"
        ];


    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function experience_booking_rooms()
    {
        return $this->belongsToMany(ExperienceRoom::class, 'experience_booking_room', 'experience_booking_id', 'experience_room_id');
    }

    public function experience_booking_requirements()
    {
        return $this->hasMany(ExperienceBookingRequirement::class, 'booking_id');
    }

    public function rooms()
    {
        return $this->belongsToMany(ExperienceRoom::class, 'experience_booking_room', 'experience_booking_id', 'experience_room_id');
    }

    public function comments()
    {
        $this->hasMany(ExperienceBookingComment::class);
    }


    /**
     * @param Request $request
     * @return
     * 数据处理放在观察者 ExperienceRoomBookingObserver 里
     */
    public static function store( Request $request )
    {

        if ($model = static::query()->create($request->all())) {
            $model->rooms()->attach($request->rooms);
            return $model;
        }

        return false;
    }

    /**
     * Set attribute with balance
     *
     * @param  string $value
     * @return void
     */
    public function setBalanceAttribute( $value )
    {
        $this->attributes[ 'balance' ] = intval($value * 100);
    }

    /**
     * Get attribute with balance
     *
     * @param  string $value
     * @return int
     */
    public function getBalanceAttribute( $value )
    {
        return doubleval($value / 100);
    }
    /**
     * 计算费用
     *
     * @param  [type] $checkin  [description]
     * @param  [type] $checkout [description]
     * @param  [type] $isPrepay [是否是使用预付金]
     * @param  array $room [description]
     * @return [type]           [description]
     */
    public static function calculateFee( $checkin, $checkout, $room = [], $isPrepay = false )
    {
        $total = 0;
        $ds    = ExperienceRoomBookingRepository::_splitDate($checkin, $checkout);

        // 房间金额
        foreach ( $room as $v ) {
            $r = ExperienceRoom::query()->select('id', 'price', 'type', 'prepay_percent')->find($v);

            foreach ( $ds as $date ) {
                // 节日价
                //预付金
                $prepay  = $isPrepay ? ($r->prepay_percent / 100) : 1;

                $special = ExperienceSpecialPrice::query()->where('experience_room_id', $r->id)->where('date', $date)->value('price');
                //加上预付金
                $total += ($special === null ? $r->price * $prepay : $special * $prepay);
            }
        }

        return $total;
    }


    /**
     * @param $id
     * @param $status
     * @param bool $systemOption 是不是系统自动操作
     * @return bool
     */
    public static function changeBookingOrder( $id, $status, $systemOption = false )
    {

        if (!$booking = static::query()->find($id))
            return false;

        //删除订单
        if ($status == static::STATUS_DEL) {
            static::destroy($id);
            return true;
        }

        if ($booking->status == $status) {
            return true;
        }

        //用户发起的取消请求，只有完成支付的订单，才能取消订单====这一步很重要的
        if ($status == static::STATUS_CANCEL) {
           // if (!$systemOption && ($booking->status != static::STATUS_PAID)) return false;
        }

        if($booking->is_refund==static::STATUS_REFUNDED){
            $booking->status=static::STATUS_CANCEL;
        }else{
            $booking->status = $status;
        }

        return $booking->save();
    }


    /**
     * @param $room
     * @return array 获取已经下单的房间的日子
     */
    public static function getOneRoomOrderDateApi( $room )
    {

        if (!$room) {
            return [];
        }

        $already = \DB::table('experience_booking')
                      ->leftJoin('experience_booking_room', 'id', 'experience_booking_room.experience_booking_id')
                      ->select('checkin', 'checkout')
                      ->where('status', '<>', self::STATUS_CANCEL)
                      ->where('experience_booking_room.experience_room_id', $room)
                      ->where('checkin', '>=', date('Ymd'))->where('deleted_at', null)->orwhere('checkout', date('Ymd'))
                      ->groupBy('id')->get()
        ;
        //不要get  就可以打印出来
        // dd($already->toSql());

        $date = [];
        if ($already) {
            foreach ( $already as $v ) {
                $date = array_merge($date, static::calculateDate($v->checkin, $v->checkout));
            }
        }

        return $date;
    }


    public static function calculateDate( $start, $end )
    {
        $ds    = [];
        $start = strtotime($start);
        $end   = strtotime($end);

        while ( $start <= $end ) {
            $ds[]  = date('Y-m-d', $start);
            $start = strtotime('+1 day', $start);
        }

        return $ds;
    }


}
