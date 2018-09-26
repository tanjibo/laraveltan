<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 7/8/2018
 * Time: 3:18 PM
 */

namespace Repositories;


use App\Models\Tearoom;
use App\Models\TearoomPrice;
use App\Models\TearoomSchedule;

class TearoomScheduleRepository
{

    public function getTimeTableApi( $price_id, $date )
    {
        $date = $date ? date('Y-m-d', strtotime($date)) : date('Y-m-d');
        return $this->getTimetable($price_id, $date);
    }

    public function getTimetable( $price_id, $date )
    {
        $price = TearoomPrice::query()->find($price_id);
        $space = $price->tearoom;
        //包场夜场
        if ($price->durations < 0) {
            $space->start_point = $space->end_point + 2;
            $space->end_point   = $space->start_point - $price->durations - 2;
        }


        if ($space->type == Tearoom::TYPE_ALL) {
            $schedule  = $this->getInitSchedule();
            $schedules = TearoomSchedule::query()->where('date', $date)->pluck('points');

            foreach ( $schedules as $v ) {
                $schedule = $schedule & $v;
            }

            $schedule = substr($schedule, 0, $space->end_point + 2);
        }
        else {
            $schedule = TearoomSchedule::query()->where('tearoom_id', $space->id)->where('date', $date)->value('points');

            $schedule = $schedule ? $schedule : $this->getInitSchedule();

            //全部的房间
            $all = TearoomSchedule::query()->where('date', $date)->where('tearoom_id', TearoomSchedule::All_YARD_ID)->value('points');

            if ($all)
                $schedule = $schedule & $all;

            $schedule = substr($schedule, 0, $space->end_point + 2);

        }


        $timetable = [];
        $durations = abs($price->durations); //时间段
        //判断$date<date('Y-m-d') 如果传过来时间小于当前的时间，代表补过去时间的订单，
        $time = $date < date('Y-m-d') ? strtotime($date) : time()+4*3600;

        for ( $i = $space->start_point; $i <= $space->end_point; ++$i ) {
            $range     = substr($schedule, $i, $durations);
            $available = 1;

            if ($time > strtotime($date . ' ' . TearoomSchedule::$timetable[ $i - 2 ]) || (strstr($range, '0') !== false || strlen($range) < $durations || !$schedule[ $i - 1 ])) {
                $available = 0;
            }

            $timetable[] = [
                'point'     => $i,
                'time'      => TearoomSchedule::$timetable[ $i ],
                'available' => $available,
            ];
        }

        return $timetable;
    }

    /**
     * 锁定时间
     *
     * @param  int $tearoom_id 茶舍空间ID
     * @param  string $date 日期
     * @param  int $start_point 开始时间点
     * @param  int $end_point 结束时间点
     */
    public function lockTime( int $tearoom_id, string $date, int $start_point, int $end_point )
    {
        $type = Tearoom::query()->where('id', $tearoom_id)->value('type');

        if ($type == Tearoom::TYPE_ALL) {
            $spaces = Tearoom::query()->where('type', Tearoom::TYPE_SINGLE)->pluck('id');

            foreach ( $spaces as $v ) {
                if (!$this->updatePoints($v, $date, $start_point, $end_point, 0))
                    return false;
            }
            return true;
        }
        else {
            return $this->updatePoints($tearoom_id, $date, $start_point, $end_point, 0);
        }
    }

    /**
     * 解锁时间
     *
     * @param  int $tearoom_id 茶舍空间ID
     * @param  string $date 日期
     * @param  int $start_point 开始时间点
     * @param  int $end_point 结束时间点
     */
    public function unlockTime( int $tearoom_id, string $date, int $start_point, int $end_point )
    {
        return $this->updatePoints($tearoom_id, $date, $start_point, $end_point, 1);
    }


    /**
     * 更新时间点
     *
     * @param  int $tearoom_id 茶舍空间ID
     * @param  string $date 日期
     * @param  int $start_point 开始时间点
     * @param  int $end_point 结束时间点
     * @param  int|integer $sign 标识
     */
    public function updatePoints( int $tearoom_id, string $date, int $start_point, int $end_point, int $sign = 0 )
    {
        $schedule = TearoomSchedule::query()->where('tearoom_id', $tearoom_id)->where('date', $date)->value('points');

        $points = $schedule ? $schedule : $this->getInitSchedule();

        for ( $i = $start_point; $i <= $end_point; ++$i ) {
            $points[ $i ] = $sign;
        }

        if ($schedule) {
            return TearoomSchedule::query()->where('tearoom_id', $tearoom_id)->where('date', $date)->update([ 'points' => (string)$points ]);
        }
        else {
            return TearoomSchedule::query()->create(
                [
                    'tearoom_id' => $tearoom_id,
                    'date'       => $date,
                    'points'     => $points,
                ]
            )
                ;
        }
    }

    /**
     * 获取初始化时间点字符串
     *
     * @return string
     */
    public function getInitSchedule()
    {
        return str_pad('', count(TearoomSchedule::$timetable), '1');
    }
}