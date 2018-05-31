<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 24/5/2018
 * Time: 2:01 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class OfficialActivityNumber extends Model
{
    protected $table="official_activity_number";
    protected  $fillable=[
        'draw_number',
        "open_id",
        "children_open_id",
        'official_activity_number',
        'official_activity_id',
    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('official_activity_id', function(Builder $builder) {

            $builder->where('official_activity_id',getActivityId() );
        });
    }



}