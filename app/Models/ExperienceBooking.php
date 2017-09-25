<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Nicolaslopezj\Searchable\SearchableTrait;
use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ExperienceBooking
 *
 * @property int $id
 * @property int $user_id
 * @property \Carbon\Carbon $checkin
 * @property \Carbon\Carbon $checkout
 * @property int $price
 * @property int $balance
 * @property int $real_price
 * @property int $pay_mode
 * @property string $customer
 * @property int $gender
 * @property string $mobile
 * @property int $people
 * @property string $requirements
 * @property string $remark
 * @property int $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @property int $is_comment
 * @property string $partner
 *
 * @property \App\Models\User $user
 * @property \Illuminate\Database\Eloquent\Collection $experience_booking_rooms
 *
 * @package App\Models
 */
class ExperienceBooking extends Eloquent
{
    use \Illuminate\Database\Eloquent\SoftDeletes;
    use SearchableTrait;
    protected $table = 'experience_booking';

    protected $casts
        = [
            'user_id'    => 'int',
            'price'      => 'int',
            'balance'    => 'int',
            'real_price' => 'int',
            'pay_mode'   => 'int',
            'gender'     => 'int',
            'people'     => 'int',
            'status'     => 'int',
            'is_comment' => 'int',
        ];

    protected $dates
        = [
            'checkin',
            'checkout',
        ];

    protected $searchable
        = [
            /**
             * Columns and their priority in search results.
             * Columns with higher values are more important.
             * Columns with equal values have equal importance.
             *
             * @var array
             */
            'columns' => [
               'experience_booking.id'       => 10,
             'experience_booking.checkin'  => 5,
                'experience_booking.checkout' => 5,
                'experience_booking.price'    => 5,
                'experience_booking.real_price'    => 5,
               'experience_booking.customer' => 5,
              'experience_booking.user_id'  =>5,
                'experience_booking.mobile'   => 5,
                'experience_booking.requirements'   => 20,
                'experience_booking.remark'   =>20,
                'user.nickname'=>5,
                'user.mobile'=>5,
                'experience_room.name'=>5,

            ],
            'joins'   => [
                'user' => [ 'user.id', 'experience_booking.user_id' ],
                'experience_booking_room' => [ 'experience_booking_room.experience_booking_id', 'experience_booking.id' ],
                'experience_room'=>[
                    'experience_room.id','experience_booking_room.experience_room_id'
                ]
            ],
        ];

    protected $fillable
        = [
            'user_id',
            'checkin',
            'checkout',
            'price',
            'balance',
            'real_price',
            'pay_mode',
            'customer',
            'gender',
            'mobile',
            'people',
            'requirements',
            'remark',
            'status',
            'is_comment',
            'partner',
        ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function experience_booking_rooms()
    {
        return $this->hasMany(\App\Models\ExperienceBookingRoom::class);
    }
}
