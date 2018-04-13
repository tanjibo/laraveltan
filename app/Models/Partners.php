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

    public function scopeToken($query,$token)
    {
        return $query->where('token',$token);
    }

    public static function partnerId(string $token){
       return  static::query()->token($token)->value('id');
    }
}
