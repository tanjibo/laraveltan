<?php

namespace App\Api;

use Illuminate\Database\Eloquent\Model;

class ExperienceBooking extends Model
{

    use \Illuminate\Database\Eloquent\SoftDeletes;

    /**
     * 订单状态
     */
    const STATUS_UNPAID   = 0;    // 待支付
    const STATUS_PAID     = 1;    // 已支付
    const STATUS_CHECKIN  = 2;    // 已入住
    const STATUS_COMPLETE = 10;   // 已完成
    const STATUS_CANCEL   = -10;  // 已取消

    /**
     * 支付方式
     */
    const PAY_MODE_OFFLINE  = 0;    // 线下支付
    const PAY_MODE_WECHAT   = 1;    // 微信支付
    const PAY_MODE_BALANCE  = 10;   // 余额支付

    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'experience_booking';

    /**
     * Hidden fields
     *
     * @var array
     */
    protected $hidden = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
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
        'status'
    ];

    /**
     * Set attribute with price
     *
     * @param  string  $value
     * @return void
     */
    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = intval($value * 100);
    }

    /**
     * Get attribute with price
     *
     * @param  string  $value
     * @return void
     */
    public function getPriceAttribute($value)
    {
        return doubleval($value / 100);
    }

    /**
     * Set attribute with real_price
     *
     * @param  string  $value
     * @return void
     */
    public function setRealPriceAttribute($value)
    {
        $this->attributes['real_price'] = intval($value * 100);
    }

    /**
     * Get attribute with real_price
     *
     * @param  string  $value
     * @return void
     */
    public function getRealPriceAttribute($value)
    {
        return doubleval($value / 100);
    }

    /**
     * Relation to ExperienceRoomModel
     *
     * @return object
     */
    public function rooms()
    {
        return $this->belongsToMany('\common\models\ExperienceRoomModel', 'experience_booking_room', 'experience_booking_id', 'experience_room_id');
    }

    /**
     * 状态转文字
     *
     * @param  integer  $status  self const STATUS_*
     * @return string
     */
    public static function status2text($status)
    {
        switch ($status)
        {
            case self::STATUS_UNPAID:
                return '待支付';

            case self::STATUS_PAID:
                return '已支付';

            case self::STATUS_CHECKIN:
                return '已入住';

            case self::STATUS_COMPLETE:
                return '已完成';

            case self::STATUS_CANCEL:
                return '已取消';

            default:
                return '未知';
        }
    }

    /**
     * 支付方式转文字
     *
     * @param  integer  $payMode  self const PAY_MODE_*
     * @return string
     */
    public static function paymode2text($payMode)
    {
        switch ($payMode)
        {
            case self::PAY_MODE_OFFLINE:
                return '线下支付';

            case self::PAY_MODE_WECHAT:
                return '微信支付';

            case self::PAY_MODE_BALANCE:
                return '余额支付';

            default:
                return '未知';
        }
    }



    /**
     * 修改状态
     *
     * @param  integer     $id     [description]
     * @param  integer     $status [description]
     * @return boolean
     */
    public static function changeStatus($id, $status)
    {
        if (!$booking = self::find($id))
            return false;

        if ($booking->status == $status)
            return true;

        switch ($status)
        {


            // 已取消
            case self::STATUS_CANCEL:
                // 账户记录
                if ($booking->balance > 0)
                {
                    // 返还余额
                    $user = User::select('id', 'balance')->find($booking->user_id);
                    $user->balance += $booking->balance;
                    $user->save();

                }

                $room = '';
                foreach ($booking->rooms as $v)
                {
                    $room .= '[' . $v->name . ']';
                }


            default:

                break;
        }

        $booking->status = $status;
        return $booking->save();
    }

}
