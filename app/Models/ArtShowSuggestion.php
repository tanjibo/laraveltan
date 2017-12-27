<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArtShowSuggestion extends Model
{
    protected $table='art_show_suggestion';

    protected $fillable=[
        'content',
        'mobile'
    ];

    function user(){
        return $this->belongsTo(User::class);
    }
}
