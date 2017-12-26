<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArtShow extends Model
{
    use SoftDeletes;
    protected $table    = 'art_show';
    protected $fillable = [ 'name', 'desc', 'cover', 'attr', 'introduction' ];

    public function comments()
    {
        return $this->hasMany(ArtShowComment::class);
    }

}
