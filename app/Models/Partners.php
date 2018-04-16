<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Partners extends Model
{
    use SoftDeletes;
    protected $table = "experience_partners";
    protected $fillable
                     = [
            'id',
            'name',
            'token',
            'mini_url',
        ];

    static public function store( array $arr )
    {
        return static::query()->create($arr);
    }

    public function scopeToken( $query, $token )
    {
        return $query->where('token', $token);
    }

    public static function partnerId( $token )
    {
        return static::query()->token($token)->value('id');
    }

    /**
     * 添加第三方来源用户
     */
    public static function addPartnerUser()
    {
        if ($experience_partner_id = static::partnerId(request()->partnerToken?:"")) {
            $arr = [
                "user_id"               => auth()->id() ?: 165,
                'experience_partner_id' => $experience_partner_id,
            ];
            PartnerUser::query()->firstOrCreate($arr);
        }
    }
}
