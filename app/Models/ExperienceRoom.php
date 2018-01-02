<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


/**
 * Class ExperienceRoom
 *
 * @property int $id
 * @property string $name
 * @property int $price
 * @property string $intro
 * @property string $cover
 * @property int $type
 * @property string $attach
 * @property int $sort
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @property string $attach_url
 * @property string $design_concept
 *
 * @property \Illuminate\Database\Eloquent\Collection $experience_booking_rooms
 * @property \App\Models\ExperienceSpecialPrice $experience_special_price
 *
 * @package App\Models
 */
class ExperienceRoom extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;
    protected $table = 'experience_room';

    use ModelTrait;
    /**
     * 类型
     */
    const TYPE_SINGLE = 1;
    const TYPE_ALL    = 2;
    const TYPE_SHAN   = 3;
    const TYPE_XING   = 4;

    const ROOM_ID_YARD = 1; //全院
    const ROOM_ID_RU   = 3;  //如
    const ROOM_ID_ZHI  = 8;  //之未庐(包含房间如和星月阁)


    protected $casts
        = [
            'price'      => 'int',
            'type'       => 'string',
            'sort'       => 'int',
            'attach_url' => 'array',
            'is_prepay'=>'int'
        ];

    protected $fillable
        = [
            'name',
            'price',
            'intro',
            'cover',
            'type',
            'attach',
            'sort',
            'attach_url',
            'design_concept',
            'is_prepay',
            'prepay_percent'
        ];


    protected $hidden
        = [
            'deleted_at',
        ];



    public function experience_booking_rooms()
    {
        return $this->hasMany(\App\Models\ExperienceBookingRoom::class);
    }

    public function experience_special_price()
    {
        return $this->hasMany(\App\Models\ExperienceSpecialPrice::class, 'experience_room_id');
    }

    public function experience_room_sliders()
    {
        return $this->hasMany(ExperienceRoomSlider::class, 'room_id');
    }

    public function comments()
    {
        return $this->hasMany(ExperienceBookingComment::class, 'commentable_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function bookings()
    {
        return $this->belongsToMany(ExperienceBooking::class, 'experience_booking_room', 'experience_room_id', 'experience_booking_id');
    }

    /**
     * @return mixed
     *获取价格
     */
    public function price()
    {
        $specialPrice          = $this->experience_special_price()->first();
        $item[ 'today_price' ] = "￥" . ($this->experience_special_price()->where('date', date('Y-m-d'))->value('price') ?: $this->price);

        if ($specialPrice)
            $item[ 'new_price' ] = "￥" . $this->price . '/￥' . $specialPrice->price . (isset($specialPrice->type) ? "({$specialPrice->type})" : '');


        switch ( $this->type ) {
            case static::TYPE_SHAN:
                $item[ 'new_price' ] = $item[ 'today_price' ] = '2000-2500/小时';

                break;
            case static::TYPE_XING:
                $item[ 'new_price' ] = $item[ 'today_price' ] = '1000/次';
                break;
            default:
                break;
        }
        return $item;
    }


}
