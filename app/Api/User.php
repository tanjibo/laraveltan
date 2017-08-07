<?php

namespace App\Api;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{

    use \Illuminate\Database\Eloquent\SoftDeletes;

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
    const SOURCE_TEAROOM        = 2;  // 茶社
    const SOURCE_ALL            = 100;  // 全部
    const SOURCE_NOUSER         = 888888;  // 没有

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

    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable
        = [
            'mobile',
            'nickname',
            'username',
            'email',
            'avatar',
            'gender',
            'total_credit',
            'surplus_credit',
            'balance',
            'device',
            'open_id',
            'source',
            'status',
        ];

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
     * @return void
     */
    public function getBalanceAttribute( $value )
    {
        return doubleval($value / 100);
    }

    /**
     * 获取账户信息
     *
     * @param  integer $user_id 用户ID
     * @return Array
     */
    public static function accountInfo( $user_id )
    {
        if (!$credit = self::select('balance', 'total_credit', 'surplus_credit')->find($user_id)) {
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
     * @return string
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
}
