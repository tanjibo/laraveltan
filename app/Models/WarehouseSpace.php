<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class WarehouseSpace
 * 
 * @property int $id
 * @property int $warehouse_id
 * @property string $name
 * @property string $remark
 * @property int $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \App\Models\Warehouse $warehouse
 *
 * @package App\Models
 */
class WarehouseSpace extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;
	protected $table = 'warehouse_space';

	protected $casts = [
		'warehouse_id' => 'int',
		'status' => 'int'
	];

	protected $fillable = [
		'warehouse_id',
		'name',
		'remark',
		'status'
	];

	public function warehouse()
	{
		return $this->belongsTo(\App\Models\Warehouse::class);
	}
}
