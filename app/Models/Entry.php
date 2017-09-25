<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Entry
 * 
 * @property int $id
 * @property int $warehouse_id
 * @property string $number
 * @property string $handler
 * @property \Carbon\Carbon $entry_at
 * @property \Carbon\Carbon $received_at
 * @property string $receiver
 * @property string $remark
 * @property int $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \App\Models\Warehouse $warehouse
 * @property \Illuminate\Database\Eloquent\Collection $cargos
 *
 * @package App\Models
 */
class Entry extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;
	protected $table = 'entry';

	protected $casts = [
		'warehouse_id' => 'int',
		'status' => 'int'
	];

	protected $dates = [
		'entry_at',
		'received_at'
	];

	protected $fillable = [
		'warehouse_id',
		'number',
		'handler',
		'entry_at',
		'received_at',
		'receiver',
		'remark',
		'status'
	];

	public function warehouse()
	{
		return $this->belongsTo(\App\Models\Warehouse::class);
	}

	public function cargos()
	{
		return $this->belongsToMany(\App\Models\Cargo::class, 'entry_cargo')
					->withPivot('product_id', 'warehouse_space_id', 'quantity');
	}
}
