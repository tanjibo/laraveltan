<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class TearoomSchedule
 * 
 * @property int $tearoom_id
 * @property \Carbon\Carbon $date
 * @property string $points
 * 
 * @property \App\Models\Tearoom $tearoom
 *
 * @package App\Models
 */
class TearoomSchedule extends Eloquent
{
	protected $table = 'tearoom_schedule';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'tearoom_id' => 'int'
	];

	protected $dates = [
		'date'
	];

	protected $fillable = [
		'tearoom_id',
		'date',
		'points'
	];

	public function tearoom()
	{
		return $this->belongsTo(\App\Models\Tearoom::class);
	}


    static public  $timetable
        = [
            0  => '00:00',
            1  => '00:30',
            2  => '01:00',
            3  => '01:30',
            4  => '02:00',
            5  => '02:30',
            6  => '03:00',
            7  => '03:30',
            8  => '04:00',
            9  => '04:30',
            10 => '05:00',
            11 => '05:30',
            12 => '06:00',
            13 => '06:30',
            14 => '07:00',
            15 => '07:30',
            16 => '08:00',
            17 => '08:30',
            18 => '09:00',
            19 => '09:30',
            20 => '10:00',
            21 => '10:30',
            22 => '11:00',
            23 => '11:30',
            24 => '12:00',
            25 => '12:30',
            26 => '13:00',
            27 => '13:30',
            28 => '14:00',
            29 => '14:30',
            30 => '15:00',
            31 => '15:30',
            32 => '16:00',
            33 => '16:30',
            34 => '17:00',
            35 => '17:30',
            36 => '18:00',
            37 => '18:30',
            38 => '19:00',
            39 => '19:30',
            40 => '20:00',
            41 => '20:30',
            42 => '21:00',
            43 => '21:30',
            44 => '22:00',
            45 => '22:30',
            46 => '23:00',
            47 => '23:30',
        ];

    const All_YARD_ID = 6;


    public function setDateAttribute( $val )
    {
        $this->attributes[ 'date' ] = date('Y-m-d', strtotime($val));;
    }

    public function getDateAttribute( $val )
    {
        return date('Y-m-d', strtotime($val));
    }


    public static function getTimetable( $price_id, $date )
    {
        $price = TearoomPrice::query()->find($price_id);
        $space = $price->tearoom;

        //包场夜场
        if ($price->durations < 0) {
            $space->start_point = $space->end_point + 2;
            $space->end_point   = $space->start_point - $price->durations - 2;
        }


        if ($space->type == Tearoom::TYPE_ALL) {
            $schedule  = static::getInitSchedule();
            $schedules = static::query()->where('date', $date)->pluck('points');

            foreach ( $schedules as $v ) {
                $schedule = $schedule & $v;
            }

            $schedule = substr($schedule, 0, $space->end_point + 2);
        }
        else {
            $schedule = static::query()->where('tearoom_id', $space->id)->where('date', $date)->value('points');

            $schedule = $schedule ? $schedule : static::getInitSchedule();

            //全部的房间
            $all = static::query()->where('date', $date)->where('tearoom_id', static::All_YARD_ID)->value('points');

            if ($all)
                $schedule = $schedule & $all;

            $schedule = substr($schedule, 0, $space->end_point + 2);

        }


        $timetable = [];
        $durations = abs($price->durations); //时间段
        $time      = time();
        for ( $i = $space->start_point; $i <= $space->end_point; ++$i ) {
            $range     = substr($schedule, $i, $durations);
            $available = 1;

            if ($time > strtotime($date . ' ' . self::$timetable[ $i - 2 ]) || (strstr($range, '0') !== false || strlen($range) < $durations || !$schedule[ $i - 1 ])) {
                $available = 0;
            }

            $timetable[] = [
                'point'     => $i,
                'time'      => self::$timetable[ $i ],
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
     * @return boolean
     */
    public static function lockTime( int $tearoom_id, string $date, int $start_point, int $end_point )
    {
        $type = Tearoom::query()->where('id', $tearoom_id)->value('type');

        if ($type == Tearoom::TYPE_ALL) {
            $spaces = Tearoom::query()->where('type', Tearoom::TYPE_SINGLE)->pluck('id');

            foreach ( $spaces as $v ) {
                if (!self::updatePoints($v, $date, $start_point, $end_point, 0))
                    return false;
            }
            return true;
        }
        else {
            return self::updatePoints($tearoom_id, $date, $start_point, $end_point, 0);
        }
    }

    /**
     * 解锁时间
     *
     * @param  int $tearoom_id 茶舍空间ID
     * @param  string $date 日期
     * @param  int $start_point 开始时间点
     * @param  int $end_point 结束时间点
     * @return boolean
     */
    public static function unlockTime( int $tearoom_id, string $date, int $start_point, int $end_point )
    {
        return self::updatePoints($tearoom_id, $date, $start_point, $end_point, 1);
    }


    /**
     * 更新时间点
     *
     * @param  int $tearoom_id 茶舍空间ID
     * @param  string $date 日期
     * @param  int $start_point 开始时间点
     * @param  int $end_point 结束时间点
     * @param  int|integer $sign 标识
     * @return boolean
     */
    public static function updatePoints( int $tearoom_id, string $date, int $start_point, int $end_point, int $sign = 0 )
    {
        $schedule = self::query()->where('tearoom_id', $tearoom_id)->where('date', $date)->value('points');

        $points = $schedule ? $schedule : self::getInitSchedule();

        for ( $i = $start_point; $i <= $end_point; ++$i ) {
            $points[ $i ] = $sign;
        }

        if ($schedule) {
            return self::where('tearoom_id', $tearoom_id)->where('date', $date)->update([ 'points' => (string)$points ]);
        }
        else {
            return self::create(
                [
                    'tearoom_id' => $tearoom_id,
                    'date'       => $date,
                    'points'     => $points,
                ]
            );
        }
    }

    /**
     * 获取初始化时间点字符串
     *
     * @return string
     */
    public static function getInitSchedule()
    {
        return str_pad('', count(self::$timetable), '1');
    }
}
