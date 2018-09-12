<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MiniCommonSettings extends Model
{
    protected $table = 'mini_common_settings';

    protected $fillable
        = [
            'mini_type',
            'navigation_bar_color',
            'banner_url',
            'common_color',
            'text',
        ];
    const MINI_TYPE_EXPERIENCE = 1;
    const MINI_TYPE_TEAROOM    = 2;

    static function experienceMiniSetting()
    {
        return static::query()->where('mini_type', static::MINI_TYPE_EXPERIENCE)->first();
    }

    static function store( $data )
    {
        return static::query()->updateOrCreate([ 'mini_type' => static::MINI_TYPE_EXPERIENCE ], $data);
    }

}
