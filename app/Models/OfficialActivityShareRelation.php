<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class OfficialActivityShareRelation extends Model
{
    protected $table = "official_activity_share_relation";

    protected $fillable
        = [
            "open_id",
            "parent_open_id",
            'official_activity_id'
        ];


    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('official_activity_id', function(Builder $builder) {
            $builder->where('official_activity_id',getActivityId() );
        });
    }

    /**
     * @param string $open_id
     * 判断是否通过其他用户分享过来的
     */
    public static function isComingFromByUser( string $open_id )
    {
        return self::query()->where("open_id", $open_id)->first();
    }

    /**
     * @param string $open_id
     * @param string $parent_open_id
     * 添加分享过来的用户
     */
    public static function add( string $open_id, string $parent_open_id )
    {
        $parent = OfficialActivityUser::query()->where("open_id", $parent_open_id)->first();

        if (!$parent || !$parent->draw_number_count) {
            return false;
        }

        //已经添加过了
        if (self::query()->where('open_id', $open_id)->where("parent_open_id", $parent_open_id)->first())
            return false;


        if ($open_id != $parent_open_id) {
            $share = [
                'open_id'        => $open_id,
                'parent_open_id' => $parent_open_id,
                'official_activity_id'=>getActivityId()
            ];
            self::query()->updateOrCreate(['open_id'=>$open_id, 'official_activity_id'=>getActivityId()],$share);
        }
    }
}
