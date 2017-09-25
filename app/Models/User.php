<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

/**
 * Class User
 *
 * @property int $id
 * @property string $mobile
 * @property string $nickname
 * @property string $username
 * @property string $avatar
 * @property int $gender
 * @property string $email
 * @property int $total_credit
 * @property int $surplus_credit
 * @property int $balance
 * @property int $device
 * @property string $open_id
 * @property int $source
 * @property int $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @property string $partner
 *
 * @property \Illuminate\Database\Eloquent\Collection $account_records
 * @property \Illuminate\Database\Eloquent\Collection $addresses
 * @property \App\Models\Cart $cart
 * @property \App\Models\CreditLog $credit_log
 * @property \Illuminate\Database\Eloquent\Collection $experience_bookings
 * @property \Illuminate\Database\Eloquent\Collection $experience_special_room_booking_xinyueges
 * @property \Illuminate\Database\Eloquent\Collection $favorites
 * @property \Illuminate\Database\Eloquent\Collection $reviews
 * @property \App\Models\SignIn $sign_in
 * @property \Illuminate\Database\Eloquent\Collection $tearoom_bookings
 * @property \Illuminate\Database\Eloquent\Collection $user_other_informations
 * @property \Illuminate\Database\Eloquent\Collection $wallet_recharges
 *
 * @package App\Models
 */
class User extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes;

    protected $table = 'user';

//    protected $casts
//        = [
//            'gender'         => 'int',
//            'total_credit'   => 'int',
//            'surplus_credit' => 'int',
//            'balance'        => 'int',
//            'device'         => 'int',
//            'source'         => 'int',
//            'status'         => 'int',
//        ];

    protected $fillable
        = [
            'mobile',
            'nickname',
            'username',
            'avatar',
            'gender',
            'email',
            'total_credit',
            'surplus_credit',
            'balance',
            'device',
            'open_id',
            'source',
            'status',
            'partner',
        ];

    public function account_records()
    {
        return $this->hasMany(\App\Models\AccountRecord::class);
    }

    public function addresses()
    {
        return $this->hasMany(\App\Models\Address::class);
    }

    public function cart()
    {
        return $this->hasOne(\App\Models\Cart::class);
    }

    public function credit_log()
    {
        return $this->hasOne(\App\Models\CreditLog::class);
    }

    public function experience_bookings()
    {
        return $this->hasMany(\App\Models\ExperienceBooking::class);
    }

    public function experience_special_room_booking_xinyueges()
    {
        return $this->hasMany(\App\Models\ExperienceSpecialRoomBookingXinyuege::class);
    }

    public function favorites()
    {
        return $this->hasMany(\App\Models\Favorite::class);
    }

    public function reviews()
    {
        return $this->hasMany(\App\Models\Review::class);
    }

    public function sign_in()
    {
        return $this->hasOne(\App\Models\SignIn::class);
    }

    public function tearoom_bookings()
    {
        return $this->hasMany(\App\Models\TearoomBooking::class);
    }

    public function user_other_informations()
    {
        return $this->hasMany(\App\Models\UserOtherInformation::class);
    }

    public function wallet_recharges()
    {
        return $this->hasMany(\App\Models\WalletRecharge::class);
    }

    public function findForPassport($username){

        return $this->orWhere('mobile',$username)->orWhere('nickname',$username)->orWhere('username',$username)->first();
    }

    public function validateForPassportPasswordGrant($password){

      return true;
    }
}
