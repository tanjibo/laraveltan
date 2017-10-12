<?php

namespace App\Http\ApiControllers\Front;

use App\Http\ApiControllers\ApiController;
use App\Models\ExperienceRoom;
use App\Models\ExperienceSpecialRoomBooking;
use App\Models\ExperienceSpecialRoomBookingXinyuege;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Repositories\ExperienceBookingSpecialRepository;

class ExperienceBookingSanAndXingController extends ApiController
{

    public function RoomCheckinDisableBy( Request $request )
    {

        //获取不可预定日子
        $data                  = [];
        $data[ 'disableDate' ] = ExperienceBookingSpecialRepository::getDisableDate($request->room_id);
        //获得时间选择器当前选择的日子
        $data[ 'currentDate' ] = $this->_getLastestCanCheckInDay($data[ 'disableDate' ]);
        //山云荟
        if ($request->room_id == ExperienceRoom::ROOM_ID_SHAN) {

            $date       = $request->date ?: $data[ 'currentDate' ];
            $time_point = $request->time_point ?: 2;
            $room_id    = $request->room_id ?: ExperienceRoom::ROOM_ID_SHAN;

            $data[ 'schedule' ] = $this->_getCanBookingSchedule($date, $time_point, $room_id);
        }

        return $this->success($data);

    }

    /**
     * 获得最近一天可以入住的日子
     */
    private function _getLastestCanCheckInDay( $data )
    {
        $begin = time();  //当天
        $end   = strtotime('+180 day');  //向后推6个月
        while ( $begin < $end ) {
            if (!in_array(date('Y-m-d', $begin), $data))
                return date('Y-m-d', $begin);
            $begin = strtotime('+1 day', $begin);

        }
        return '';
    }

    /**
     * @param $date
     * @param $time_point
     * @param $roo_id
     * @return mixed  私有方法
     */
    private function _getCanBookingSchedule( $date, $time_point, $roo_id )
    {
        //山云荟
        $params = [
            'date'       => $date,
            'time_point' => $time_point,
            'room_id'    => $roo_id,
        ];

        $data = ExperienceBookingSpecialRepository::getCanBookingSchedule($params);

        return $data;

    }


    /**
     * 获得时间段
     */
    public function getQuanTum( Request $request )
    {
        $date       = $request->date ?: date('Y-m-d');
        $time_point = $request->time_point??2;
        $room_id    = $request->room_id ?: ExperienceRoom::ROOM_ID_SHAN;
        return $this->success($this->_getCanBookingSchedule($date, $time_point, $room_id));
    }

    /**
     * @param Request $request
     * @return mixed
     *  计算价格，这个只是个山云荟使用，星月阁写死了就是1000大洋
     */
    public function totalFee( Request $request )
    {
        return $this->success(ExperienceSpecialRoomBooking::countPrice($request->dot));
    }


    /**
     * @param Request $request
     * 创建订单
     */
    public function createSpecialOrder( Request $request )
    {

        $data              = $request->all();
        $data[ 'user_id' ] = Auth::user()->id ?: 1;

        //根据这个判断是星月阁还是山云荟
        $type  = $request->type;

        $class = ($type == ExperienceRoom::TYPE_SHAN ? ExperienceSpecialRoomBooking::class : ExperienceSpecialRoomBookingXinyuege::class);

        return $class::store($data) ? $this->success([]) : $this->internalError('fail');


    }

}
