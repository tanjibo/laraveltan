<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArtShowCollection extends Model
{
    protected $table = 'art_show_collection';

    protected $fillable = [ 'user_id', 'art_show_id' ];


    function art_show()
    {
        return $this->belongsTo(ArtShow::class,'art_show_id');
    }

    public function scopeByWho($query,$user_id){
        return $query->where('user_id','=',$user_id);
    }



}
