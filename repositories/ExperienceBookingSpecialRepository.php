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


use App\Models\ExperienceBooking;
use App\Models\ExperienceRoom;
use App\Models\ExperienceSpecialRoomBookingXinyuege;
use App\Models\ExperienceSpecialRoomTimePoint;
use Illuminate\Support\Facades\Redis;

class ExperienceBookingSpecialRepository  extends BaseRepository
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

        //获得集合
        $arr = explode(',', $booking->dot);
        //  $arr = [ '10', '09', '15', '16', '11' ];
        $key = static::BOOKING_SPECIAL_DATE_REDIS_KEY . $booking->date;
        if (Redis::exists($key)) {

            Redis::sRem($key, ...$arr);
            //删除key
            if (!Redis::sCard($key)) Redis::expire($key, time() - 1);
        }

    }



    static function getDisableDate(int $room_id){

        //$room_id=9;//山云荟
        $model = ExperienceRoom::query()->find($room_id);
        //获得全院已经入定的日期
        $bigDate = ExperienceBooking::getOneRoomOrderDateApi(1)??[];

        if ($model->type == ExperienceRoom::TYPE_XING) {
            $zhiweilv = ExperienceBooking::getOneRoomOrderDateApi(8)??[];
            $date     = ExperienceSpecialRoomBookingXinyuege::getOneRoomOrderDateApi(10)??[];
            $tmp = array_merge_recursive($bigDate, $zhiweilv, $date);
        }
        else {
            //山云荟
            //$date = ExperienceSpecialRoomBookingModel::getOrderDate(9)??[];
            $tmp = array_merge($bigDate, []);
        }
        $tmp && $tmp = array_unique($tmp);

        //后台锁定的时间
        $lockDate=ExperienceRoomLockDateRepository::initLockDate($room_id);
        $lockDate=collect($lockDate)->flatten(10)->unique()->toArray();
        $tmp=array_unique(array_merge_recursive($tmp,$lockDate));
        return $tmp;
    }


    /**
     * 获取可以定的时间段
     */
    public static function getCanBookingSchedule( $data )
    {

        $date = date('Y-m-d', strtotime(array_shift($data)));  //获得预约日期

        $room  =ExperienceSpecialRoomTimePoint::query()->with('schedule')->where($data)->first();

        //获得集合
        $smember = Redis::sMembers(static::BOOKING_SPECIAL_DATE_REDIS_KEY . $date);
        //当前时间不能选
        if($date==date('Y-m-d'))
            $currentHour=array_map(function($item){
                return $item>=10?(string)$item:'0'.$item;
            },range(0,date('H')));
        else
            $currentHour=[];
        $smember=array_merge($smember,$currentHour);
        // $arr=['09','10','11'];
        // $redis->sAdd($key,...$arr);
        $tmp = $room->schedule->toArray();

        foreach ( $tmp as $key => $v ) {

            array_intersect(explode(',', $v[ 'dot' ]), $smember) ?
                $tmp[ $key ][ 'available' ] = 0 :
                $tmp[ $key ][ 'available' ] = 1;

        }

        return $tmp;

    }

}