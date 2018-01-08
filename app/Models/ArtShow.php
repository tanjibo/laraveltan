<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArtShow extends Model
{
    use SoftDeletes;
    protected $table    = 'art_show';
    protected $fillable = [ 'name', 'desc', 'cover', 'attr', 'introduction','status','mini_route','sorting' ];

    protected $casts=[
        'status'=>'string'
    ];

    public function comments()
    {
        return $this->hasMany(ArtShowComment::class);
    }

    public function likes(){
        return $this->morphMany(ArtShowLike::class, 'likable');
    }

    public function collections(){
        return $this->hasMany(ArtShowCollection::class);
    }



}
