<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfficialAccountDefaultSetting extends Model
{
    protected  $table="official_account_default_setting";

    protected $fillable=[
        'default_welcome',
        'be_recommend_welcome',
        'auto_reply_welcome',
        'menu_json'

    ];


    static function officialAccountHasDefaultSetting(){
      return   self::query()->first();
    }
}
