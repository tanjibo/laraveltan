<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Warehouse
 * 
 * @property int $id
 * @property string $name
 * @property string $address
 * @property string $remark
 * @property int $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection $entries
 * @property \Illuminate\Database\Eloquent\Collection $stocks
 * @property \Illuminate\Database\Eloquent\Collection $transfers
 * @property \Illuminate\Database\Eloquent\Collection $warehouse_spaces
 *
 * @package App\Models
 */
class Warehouse extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;
	protected $table = 'warehouse';

	protected $casts = [
		'status' => 'int'
	];

	protected $fillable = [
		'name',
		'address',
		'remark',
		'status'
	];

	public function entries()
	{
		return $this->hasMany(\App\Models\Entry::class);
	}

	public function stocks()
	{
		return $this->hasMany(\App\Models\Stock::class);
	}

	public function transfers()
	{
		return $this->hasMany(\App\Models\Transfer::class, 'new_warehouse_id');
	}

	public function warehouse_spaces()
	{
		return $this->hasMany(\App\Models\WarehouseSpace::class);
	}
}
