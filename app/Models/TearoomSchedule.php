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

}
