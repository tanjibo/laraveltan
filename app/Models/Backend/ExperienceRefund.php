<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 06 Dec 2017 15:59:04 +0800.
 */

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class ExperienceRefund
 * 
 * @property int $id
 * @property string $out_trade_no
 * @property string $out_refund_no
 * @property int $refund_fee
 * @property string $refund_id
 * @property string $transaction_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package App\Models
 */
class ExperienceRefund extends Eloquent
{
	protected $table = 'experience_refund';

	protected $casts = [
		'refund_fee' => 'int'
	];

	protected $fillable = [
		'out_trade_no',
		'out_refund_no',
		'refund_fee',
		'refund_id',
		'transaction_id'
	];
}
