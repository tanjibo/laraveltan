<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArtShowSuggestion extends Model
{
    use SoftDeletes;
    protected $table='art_show_suggestion';

    protected $fillable=[
        'user_id',
        'content',
        'mobile'
    ];

    function user(){
        return $this->belongsTo(User::class);
    }
}
