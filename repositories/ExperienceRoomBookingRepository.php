<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 28/9/2017
 * Time: 6:40 PM
 */

namespace Repositories;

use App\Http\Resources\Experience\ExperienceRoomBookingResource;
use App\Http\Resources\Experience\ExperienceRoomResource;
use App\Models\ExperienceBooking;
use App\Models\ExperienceRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;


class ExperienceRoomBookingRepository
{

    public $request = null;

    public function __construct( Request $request )
    {
        $this->request = $request;
    }

    public function checkRoomCheckInIsUsed( $val, $checkin )
    {
        $val = is_array($val) ? $val : Arr::wrap($val);
        foreach ( $val as $v ) {
            if ($this->RoomCheckinDisableApi($v)->contains($checkin)) return true;
        }
        return false;
    }

    /**
     * 一个房间不可选的入住时间
     * @param $room_id
     *
     */
    public function roomCheckinDisableApi( int $room_id = null )
    {

        return static::_roomCheckinDisable($room_id ?: $this->request->room_id);
    }


    static private function _roomCheckinDisable( $room_id )
    {

        $checkinAndCheckout = static::_oneRoomOrderDate($room_id);

        //后台锁定的时间------------------------
        $lockDate = array_filter(ExperienceRoomLockDateRepository::initLockDate($room_id));

        $checkinAndCheckout = $checkinAndCheckout ?: collect([]);

        return collect($checkinAndCheckout)->map(
            function( $item ) {
                return splitDate($item[ 'checkin' ], $item[ 'checkout' ]);
            }
        )->merge(collect($lockDate))->flatten()->unique()->filter(
            function( $item ) {
                return $item >= date('Y-m-d');
            }
        )
            ;

    }


    /**
     * 一个房间不能退房的时间
     */
    function roomCheckoutDisableApi()
    {
        return static::_roomCheckoutDisable($this->request->room_id, $this->request->checkin);

    }

    static private function _roomCheckoutDisable( $room_id, $checkin )
    {
        //不可入住的日期
        $checkinDisable = static::_roomCheckinDisable($room_id);


        //本房间的所有的入住开始日期，按道理来说，入住开始时间，也是一个订单的退房时间，这两个不冲突
        $startDate = static::_oneRoomOrderAllStartDate();

        $leftDisable = $checkinDisable->diff($startDate)->all();

        array_push($leftDisable, $checkin);

        //把checkin 之前的时间禁用
        $dateToSelect = [];
        if ($checkin > date('Y-m-d'))
            $dateToSelect = splitDate(date('Y-m-d'), $checkin);



        //已经有的退房时间
        $endDate = static::_oneRoomOrderEndDate();


        //今天到预定开始时间段 ---- 合并 ---- 已经预定的时间
        return collect([])->merge($leftDisable)->merge($endDate)->merge($dateToSelect)->unique()->filter(
            function( $item ) {
                return $item >= date('Y-m-d');
            }
        )
            ;
    }

    /**
     * 获取一个房间所有入住的开始时间
     * @return array
     */
    private static function _oneRoomOrderAllStartDate()
    {

        return collect(static::_oneRoomOrderDate(request()->room_id) ?: [])->pluck('checkin')->map(
            function( $item ) {
                return date('Y-m-d', strtotime($item));
            }
        )->all()
            ;

    }

    /**
     * 获取一个房间所有入住的结束时间
     * @return array
     */
    private static function _oneRoomOrderEndDate()
    {

        return collect(static::_oneRoomOrderDate(request()->room_id) ?: [])->pluck('checkout')->map(
            function( $item ) {
                return date('Y-m-d', strtotime($item));
            }
        )->all()
            ;
    }

    /**
     * @param $room_id
     * 获取一个房间订房的入住时间和退房时间
     */
    private static function _oneRoomOrderDate( $room_id )
    {
        //排除订单
        $booking_id = request()->booking_id ?: '';

        return ExperienceBooking::query()->leftJoin('experience_booking_room', 'id', 'experience_booking_room.experience_booking_id')
                                ->select('checkin', 'checkout')
                                ->where(
                                    function( $query ) use ( $room_id ) {
                                        if ($room_id != ExperienceRoom::ROOM_ID_YARD) {
                                            $rooms = [ $room_id ];
                                            //3 和 8 需要处理
                                            if ($room_id == ExperienceRoom::ROOM_ID_RU || $room_id == ExperienceRoom::ROOM_ID_ZHI) {
                                                $rooms = [ ExperienceRoom::ROOM_ID_RU, ExperienceRoom::ROOM_ID_ZHI ];
                                            }
                                            $query->orWhereIn('experience_booking_room.experience_room_id', $rooms);
                                            $query->orWhere('experience_booking_room.experience_room_id', ExperienceRoom::ROOM_ID_YARD);
                                        }
                                    }
                                )
                                ->where('checkout', '>=', date('Y-m-d'))
                                ->where('status', '<>', -10)
                                ->when(
                                    $booking_id, function( $query ) use ( $booking_id ) {
                                    return $query->where('id', "<>", $booking_id);
                                }
                                )
                                ->groupBy('id')->get()
            ;

    }


    /**
     * 获取两个日期间的日期
     * @param $start [开始时间]
     * @param $end [结束时间]
     * @return array
     */
    public static function _splitDate( $start, $end )
    {
        $ds    = [];
        $start = strtotime($start);
        $end   = strtotime($end);

        while ( $start < $end ) {
            $ds[]  = date('Y-m-d', $start);
            $start = strtotime('+1 day', $start);
        }

        return $ds;
    }


    public function leftCheckinRoomApi()
    {
        //获取当前预订时间段
        $list = splitDate($this->request->checkin, $this->request->checkout);

        //获取所有剩下房间不可预订的时间段
        $all = static::_allLeftRoomDisableDate($this->request->room_id);

        //判断当前时间段和自己以前定过的时间段是否有冲突

        if (array_intersect($list, $all[ $this->request->room_id ]->toArray())) return false;

        $canCheckInRoom = [];

        unset($all[ $this->request->room_id ]);

        //获取剩下可以预定的时间
        foreach ( $all as $key => $item ) {

            if (!array_intersect($list, $item->toArray())) {

                array_push($canCheckInRoom, $key);
            }
        }
        return ExperienceRoomResource::collection(ExperienceRoom::query()->find($canCheckInRoom));
    }


    private static function _allLeftRoomDisableDate( $room_id )
    {

        $room = static::_getLeftExperienceRoom($room_id);

        //获取不可入住数组
        $disableQueue = [];
        $room->map(
            function( $item ) use ( &$disableQueue ) {

                $disableQueue[ $item->id ] = static::_roomCheckinDisable($item->id);
            }
        );

        return $disableQueue;
    }


    /**
     * @param $room_id
     * @return \Illuminate\Support\Collection
     *  获取剩下可以预定的房间id
     */
    private static function _getLeftExperienceRoom( $room_id )
    {
        //3，和 8 不能同时出现,3和8 就是一样的，任何时候只可能出现一次

        switch ( $room_id ) {
            case ExperienceRoom::ROOM_ID_YARD:
                $condition = [ 2, 3, 4, 5, 6, 7, 8 ];
                break;
            case ExperienceRoom::ROOM_ID_RU:
                $condition = [ 1, 8 ];
                break;
            case ExperienceRoom::ROOM_ID_ZHI:
                $condition = [ 1, 3 ];
                break;
            default:
                $condition = [ 1, 8 ];
                break;
        }

        return ExperienceRoom::query()->whereIn(
            'type', [ ExperienceRoom::TYPE_ALL, ExperienceRoom::TYPE_SINGLE ]
        )->whereNotIn('id', $condition)->select('id', 'name')->get()
            ;
    }


    public function orderListApi()
    {
        //正常订单

        $model = ExperienceBooking::query()->where('user_id', Auth::id() ?: 165)->when(
            isset($this->request->orderStatus), function( $query ) {
            if ($this->request->orderStatus)
                $query->where('status', $this->request->orderStatus);
        }
        )
        ;

        $common = ExperienceRoomBookingResource::collection($model->orderBy('created_at', 'desc')->get());
        //山云荟订单
//        $san = ExperienceRoomBookingSanResource::collection(ExperienceSpecialRoomBooking::query()->where('user_id', Auth::id() ?: 165)->orderBy('created_at', 'desc')->get());
        //星月阁订单
//        $xing = ExperienceRoomBookingXingResource::collection(ExperienceSpecialRoomBookingXinyuege::query()->where('user_id', Auth::id() ?: 165)->orderBy('created_at', 'desc')->get());

        return $common->sortByDesc('created_at')->values()->all();

    }

}