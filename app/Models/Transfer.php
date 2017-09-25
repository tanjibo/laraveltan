<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Transfer
 * 
 * @property int $id
 * @property int $org_warehouse_id
 * @property int $new_warehouse_id
 * @property string $number
 * @property string $handler
 * @property \Carbon\Carbon $transfer_at
 * @property \Carbon\Carbon $received_at
 * @property string $receiver
 * @property string $remark
 * @property int $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \App\Models\Warehouse $warehouse
 * @property \Illuminate\Database\Eloquent\Collection $stocks
 *
 * @package App\Models
 */
class Transfer extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;
	protected $table = 'transfer';

	protected $casts = [
		'org_warehouse_id' => 'int',
		'new_warehouse_id' => 'int',
		'status' => 'int'
	];

	protected $dates = [
		'transfer_at',
		'received_at'
	];

	protected $fillable = [
		'org_warehouse_id',
		'new_warehouse_id',
		'number',
		'handler',
		'transfer_at',
		'received_at',
		'receiver',
		'remark',
		'status'
	];

	public function warehouse()
	{
		return $this->belongsTo(\App\Models\Warehouse::class, 'new_warehouse_id');
	}

	public function stocks()
	{
		return $this->belongsToMany(\App\Models\Stock::class, 'transfer_stock', 'transfer_id', 'new_stock_id')
					->withPivot('org_stock_id', 'quantity');
	}
}
