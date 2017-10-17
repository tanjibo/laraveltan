<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class PaymentLog
 *
 * @property int $id
 * @property string $order_number
 * @property string $trade_number
 * @property int $fee
 * @property int $type
 * @property \Carbon\Carbon $created_at
 *
 * @package App\Models
 */
class PaymentLog extends Eloquent
{
    protected $table      = 'payment_log';
    public    $timestamps = false;
    const TYPE_MINI = 'mini';
    protected $casts
        = [
            'fee'  => 'int',
            'type' => 'int',
        ];

    protected $fillable
        = [
            'order_number',
            'trade_number',
            'fee',
            'type',
            'created_at'
        ];
}
