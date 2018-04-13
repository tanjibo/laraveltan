<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerUser extends Model
{
    protected $table='experience_partner_user';
    protected $fillable=[
        'user_id',
        'experience_partner_id'
    ];
}
