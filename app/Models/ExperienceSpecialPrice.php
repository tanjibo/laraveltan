<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ExperienceSpecialPrice
 *
 * @property int $experience_room_id
 * @property \Carbon\Carbon $date
 * @property int $price
 * @property string $type
 *
 * @property \App\Models\ExperienceRoom $experience_room
 *
 * @package App\Models
 */
class ExperienceSpecialPrice extends Eloquent
{
    use ModelTrait;
    protected $table        = 'experience_special_price';
    public    $incrementing = false;
    public    $timestamps   = false;

    protected $casts
        = [
            'experience_room_id' => 'int',
            'price'              => 'int',
        ];

    protected $dates
        = [
            'date',
        ];

    protected $fillable
        = [
            'experience_room_id',
            'date',
            'price',
            'type',
        ];


    public function experience_room()
    {
        return $this->belongsTo(\App\Models\ExperienceRoom::class);
    }


}
