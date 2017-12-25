<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArtShow extends Model
{
    protected $table    = 'art_show';
    protected $fillable = [ 'name', 'desc', 'cover', 'attr', 'introduction' ];

    public function comments()
    {
        return $this->hasMany(ArtShowComment::class);
    }

}
