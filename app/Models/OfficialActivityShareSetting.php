<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfficialActivityShareSetting extends Model
{
    protected  $table="official_activity_share_setting";

    protected  $fillable=[
        'title',
        'desc',
        'link_url',
        'cover_img',
        'type',
        'qrcode_url'
    ];
}
