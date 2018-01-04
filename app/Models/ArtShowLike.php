<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArtShowLike extends Model
{

    protected $fillable = [ 'user_id', 'likable_id', 'likable_type', 'is' ];

    public function likable()
    {
        return $this->morphTo();
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function scopeByWho($query,$user_id){
        return $query->where('user_id','=',$user_id);
    }
    
    public function scopeWithType($query,$type){
        return $query->where('is','=',$type);
    }
}
