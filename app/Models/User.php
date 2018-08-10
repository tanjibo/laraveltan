<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;


use App\Exceptions\InternalException;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;


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
    use HasRoles {
        hasRole as public fatherHasRole;
    }

    use Notifiable {
        notify as protected fatherNotify;

    }
    protected $table = 'user';

    /**
     * 性别
     */
    const GENDER_UNKNOWN = 0;   // 未知
    const GENDER_MALE    = 1;   // 男
    const GENDER_FEMALE  = 2;   // 女

    const USER_STATUS_ON  = 1;  //状态
    const USER_STATUS_OFF = 0;
    /**
     * 来源
     */
    const SOURCE_DEFAULT        = 0;   // 商城
    const SOURCE_TRAVELZOO      = 11;  // 旅游族
    const SOURCE_EXPERIENCEROOM = 1;  //安吉体验中心
    const SOURCE_ARTSHOW        = 12;  //空间展示
    const SOURCE_TEAROOM        = 2;  // 茶社
    const SOURCE_ALL            = 100;  // 全部
    const SOURCE_NOUSER         = 888888;  // 没有
    const SOURCE_DRAW           = 5;  //抽奖
    const SOURCE_MEMBER_CARD    = 6;  //会员卡

    /**
     * 终端
     */
    const DEVICE_WECHAT  = 0;   // 微信
    const DEVICE_IOS     = 1;   // iOS
    const DEVICE_ANDROID = 2;   // Android
    const DEVICE_WEB     = 3;   // 网页
    const DEVICE_OTHER   = 9;   // 其他

    /**
     * 积分
     */
    const CREDIT_LV1 = 1;       // 普通会员
    const CREDIT_LV2 = 2000;    // 银牌会员
    const CREDIT_LV3 = 5000;    // 金牌会员
    const CREDIT_LV4 = 10000;   // 铂金会员
    const CREDIT_LV5 = 20000;   // 终身会员

    protected $casts
        = [
            'total_credit'   => 'int',
            'surplus_credit' => 'int',
            'balance'        => 'int',
            'device'         => 'string',
            'source'         => 'string',
            'status'         => 'string',
            'is_lrss_staff'  => 'bool',
            'gender'         => 'string',
            'age'            => 'int',
            'intention'      => 'array',
        ];

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
            'union_id',
            'source',
            'status',
            'partner',
            'mini_open_id',
            'level_title',
            'signature',
            'password',
            'is_receive_email',
            'last_login_ip',
            'is_lrss_staff',
            'age',
            'birthday',
            'profession',
            'nationality',
            'education',
            'id_card',
            'qq',
            'weibo',
            'wechat',
            'remark',
            'intention',
            'terminal',
            'art_open_id',
            'tearoom_open_id'
        ];


    protected $dates
        = [
            'birthday',
        ];

    protected $hidden
        = [
            'deleted_at',
            'password',
        ];


    public function setPasswordAttribute( $value )
    {
        $this->attributes[ 'password' ] = bcrypt($value);
    }


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

    public function findForPassport( $username )
    {

        return $this->orWhere('mobile', $username)->orWhere('union_id', $username)->first();
    }

    public function validateForPassportPasswordGrant( $password )
    {

        return true;
    }


    public function getAuthPassword()
    {
        return $this->attributes[ 'password' ];
    }

    /**
     * Set attribute with balance
     *
     * @param  string $value
     * @return void
     */
    public function setBalanceAttribute( $value )
    {
        $this->attributes[ 'balance' ] = intval($value * 100);
    }

    /**
     * Get attribute with balance
     *
     * @param  string $value
     * @return int
     */
    public function getBalanceAttribute( $value )
    {
        return doubleval($value / 100);
    }


    /**
     * 获取账户信息
     *
     * @param  integer $user_id 用户ID
     * @return []
     */
    public static function accountInfo( $user_id )
    {
        if (!$credit = self::query()->select('balance', 'total_credit', 'surplus_credit')->find($user_id)) {
            return false;
        }

        $level = self::credit2level($credit->total_credit);
        return array_merge($credit->toArray(), $level);
    }

    /**
     * 消费转换积分
     *
     * @param  Double $amount 消费金额
     * @return integer
     */
    public static function consume2credit( $amount )
    {
        return $amount * 0.1;
    }

    /**
     * 积发转换等级
     *
     * @param  integer $credit 当前积分
     * @return []
     */
    public static function credit2level( $credit )
    {
        switch ( true ) {
            case $credit >= self::CREDIT_LV5:
                return [ 'level' => 5, 'name' => '终身会员', 'discount' => 0.8 ];

            case $credit >= self::CREDIT_LV4:
                return [ 'level' => 4, 'name' => '铂金会员', 'discount' => 0.85 ];

            case $credit >= self::CREDIT_LV3:
                return [ 'level' => 3, 'name' => '金牌会员', 'discount' => 0.9 ];

            case $credit >= self::CREDIT_LV2:
                return [ 'level' => 2, 'name' => '银牌会员', 'discount' => 0.95 ];

            default:
                return [ 'level' => 1, 'name' => '普通会员', 'discount' => 1 ];
        }
    }

    /**
     * 性别转文字
     *
     * @param  integer $gender
     * @return string
     */
    public static function gender2text( $gender )
    {
        switch ( $gender ) {
            case self::GENDER_MALE:
                return '男';

            case self::GENDER_FEMALE:
                return '女';

            default:
                return '未知';
        }
    }

    /**
     * 来源转文字
     *
     * @param  integer $source
     * @return string
     */
    public static function source2text( $source )
    {
        switch ( $source ) {
            case self::SOURCE_TRAVELZOO:
                return '旅游族';
            case self::SOURCE_EXPERIENCEROOM:
                return '安吉体验中心';
            case self::SOURCE_TEAROOM:
                return '茶社';
            case self::SOURCE_DEFAULT:
                return '商城';
            default:
                return '商城';
        }
    }

    public function notify( $instance )
    {
        // 如果要通知的人是当前用户，就不必通知了！
        if ($this->id == auth()->id()) {
            return;
        }
        $this->increment('notification_count');
        $this->fatherNotify($instance);
    }


    public function markAsRead()
    {
        $this->notification_count = 0;
        $this->save();
        $this->unreadNotifications->markAsRead();
    }

    public function updateUser( Request $request )
    {

        if (!$request->password) unset($request[ 'password' ]);

        return $this->update($request->all());
    }

    /**
     * @param $ability
     * @return bool
     * 判断是不是超级管理员
     */
    public function hasPermissionTo( $ability )
    {
        if ($this->is_superadmin) {
            return true;
        }
        return $this->getAllPermissions()->pluck('name')->contains($ability);
    }


    public function hasRole( $roles )
    {

        if ($this->is_superadmin) {
            return true;
        }
        return $this->fatherHasRole($roles);
    }

    /**
     * 用户总订单数量
     */
    public function totalOrderNum()
    {
        return $this->experience_bookings->count() + $this->tearoom_booking->count();
    }

    public function tearoom_booking()
    {
        return $this->hasMany(TearoomBooking::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * 个人收藏的中式空间
     */
    public function art_show()
    {
        return $this->belongsToMany(ArtShow::class, 'art_show_collection');
    }

    public function draw()
    {
        return $this->hasOne(OfficialActivityUser::class, 'open_id', 'open_id');
    }

    public function draw_number()
    {
        return $this->hasMany(OfficialActivityNumber::class, "open_id", "open_id");
    }


    /**
     * 添加用户海报
     */
    public function addUserPoster()
    {
        $this->draw->update($this->makePoster());

    }


    public static function addUserByMemberCard( $wxUserInfo )
    {

        $userData = [
            'avatar'   => $wxUserInfo[ "headimgurl" ],
            'nickname' => $wxUserInfo[ "nickname" ],
            'gender'   => $wxUserInfo[ "sex" ],
            'open_id'  => $wxUserInfo[ "openid" ],
            'status'   => User::USER_STATUS_ON,
        ];

        if ( isset($wxUserInfo[ 'username' ])) {
            $userData[ 'username' ] = $wxUserInfo[ 'username' ];
        }


        if (isset($wxUserInfo[ 'email' ])) {
            $userData[ 'email' ] = $wxUserInfo[ 'email' ];
        }

        if (isset($wxUserInfo[ 'birthday' ])) {
            $userData[ 'birthday' ] = $wxUserInfo[ 'birthday' ];
        }

        if (!in_array(app()->environment(), [ 'local', 'test' ])) {
            $userData[ 'union_id' ] = $wxUserInfo[ "unionid" ];
            $user                   = User::query()->where('union_id', $wxUserInfo[ "unionid" ])->first();
        }
        else {
            $user = User::query()->where('open_id', $wxUserInfo[ "openid" ])->first();
        }


        if ($user) {
            //之前就没有电话号码
            if (!$user->mobile) {
                if (static::findUserByMobile($wxUserInfo[ 'mobile' ])) {
                    throw new InternalException('手机号已经被注册了');
                }
                $userData[ "mobile" ] = $wxUserInfo[ 'mobile' ];

            }
            else {
                if ($wxUserInfo != $user->mobile) {

                    $if = static::query()->where('mobile', $wxUserInfo['mobile'])->whereKeyNot($user->id)->first();
                    if ($if) throw new InternalException('手机号已经被注册了');
                    $userData[ "mobile" ] = $wxUserInfo[ 'mobile' ];
                }
            }
            $user->update($userData);

        }
        else {
            //用户来源
            if (static::findUserByMobileOrUnionId($wxUserInfo[ 'mobile' ])) {
                throw new InternalException('手机号已经被注册了');
            }
            $userData[ 'source' ] = User::SOURCE_MEMBER_CARD;
            $userData[ "mobile" ] = $wxUserInfo[ 'mobile' ];
            $user                 = User::query()->create($userData);
        }
        return $user;
    }


    static  function storeUser(){
        $userData = [
            'avatar'       => $avatarUrl,
            'nickname'     => $nickName,
            'union_id'     => $unionId,
            'gender'       => $gender,
            'status'       => User::USER_STATUS_ON,

        ];

        if ($user = User::query()->where('union_id', $unionId)->first()) {

            $user->update($userData);

        }
        else {
            //用户来源
            $userData[ 'source' ] = User::SOURCE_EXPERIENCEROOM;
            User::query()->create($userData);

        }
    }



    public static function findUserByMobileOrUnionId( $val )
    {
        return static::query()->where('mobile', $val)->orWhere('union_id',$val)->first();
    }

    public function findUserByMobile(){

    }

    public static function findUserByUnionId(string $union_id){

        return static::query()->where('union_id',$union_id)->first();
    }
}
