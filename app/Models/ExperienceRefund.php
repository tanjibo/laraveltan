<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExperienceRefund extends Model
{
    protected $fillable=[
      'out_trade_no',
      'out_refund_no',
      'refund_id',
      'transation_id',
      'refund_fee'
    ];
    protected  $table='experience_refund';
}
