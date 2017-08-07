<?php
/**
 * |--------------------------------------------------------------------------
 * | 处理订单特殊订单房间 山云荟 星月阁
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 30/6/2017
 * Time: 3:31 PM
 */

namespace Repositories;


class ExperienceBookingSpecialRepository
{
    /**
     * redis 前缀key
     */
    const BOOKING_SPECIAL_DATE_REDIS_KEY = 'booking_special_';


    /**
     * 解锁时间点
     */
    static function unlockDot( $booking )
    {

        //处理集合
        $redis = static::getInstance();
        //获得集合
        $arr = explode(',', $booking->dot);
        //  $arr = [ '10', '09', '15', '16', '11' ];
        $key = static::BOOKING_SPECIAL_DATE_REDIS_KEY . $booking->date;
        if ($redis->exists($key)) {

            $redis->sRem($key, ...$arr);
            //删除key
            if (!$redis->sCard($key)) $redis->expire($key, time() - 1);
        }

    }

    public static function getInstance()
    {
        if (self::$redis === null) {
            $config      = \Helper::config('redis');
            self::$redis = new \Predis\Client($config->toArray());
        }

        return self::$redis;
    }


}