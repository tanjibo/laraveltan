<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 30/9/2017
 * Time: 10:31 AM
 */

namespace App\Models;


trait ExperienceRoomBookingTrait
{
    public static function status2text( $status )
    {
        switch ( $status ) {
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
     * @param $gender
     * @return string
     * 性别
     */
    public static function gender( $gender ): string
    {
        switch ( $gender ) {
            case 1:
                return '先生';
            case 2:
                return '女士';
            default:
                return '先生/女士';
        }

    }

    /**
     * 支付方式转文字
     *
     * @param  integer $payMode self const PAY_MODE_*
     * @return string
     */
    public static function paymode2text( $payMode )
    {
        switch ( $payMode ) {
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
}