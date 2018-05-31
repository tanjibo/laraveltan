<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 24/5/2018
 * Time: 1:19 PM
 */

namespace App\Models;



use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;



class OfficialActivityUser extends Model
{
    protected $table = "official_activity_user";

    protected $fillable
        = [
            "draw_number_count",
            "phone",
            "open_id",
            "parent_open_id",
            "status",
            'poster_url',
            "poster_media_id",
            'official_activity_id',
        ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('official_activity_id', function(Builder $builder) {

            $builder->where('official_activity_id',getActivityId() );
        });
    }


    public function user()
    {
        return $this->belongsTo(User::class,'open_id','open_id');
    }



    /**
     * 排行榜
     */
    static public function ranking()
    {
        return self::query()->with("user")->orderByDesc("draw_number_count")->get();
    }


    /**
     * @param string $params
     * 判断用户是否已经参加过了
     */
    public static function isJoin( string $params )
    {
        return static::query()->where("phone", $params)->orWhere("open_id", $params)->first();
    }

    /**
     * 用户参加活动
     */
    public static function attendDraw( $user, $activity)
    {

        $isShareByUser = OfficialActivityShareRelation::isComingFromByUser($user->open_id);

        return self::query()->create(
            [
                "open_id"   => $user->open_id,
                'phone'     => request()->phone,
                "parent_open_id" => $isShareByUser ? $isShareByUser->parent_open_id : '',
                'official_activity_id'=>$activity->id
            ]
        )
            ;
    }

    /**
     * @param $user_id
     * @return int
     */
    public static function AddDrawCount( string  $open_id)
    {
        return static::query()->where("open_id", $open_id)->increment("draw_number_count");
    }


}