<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class TearoomPrice
 * 
 * @property int $id
 * @property int $tearoom_id
 * @property int $durations
 * @property int $fee
 * @property int $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \App\Models\Tearoom $tearoom
 *
 * @package App\Models
 */
class TearoomPrice extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;
	protected $table = 'tearoom_price';
	use ModelTrait;

	protected $casts = [
		'tearoom_id' => 'int',
		'durations' => 'int',
		'fee' => 'int',
		'status' => 'string'
	];

	protected $fillable = [
		'tearoom_id',
		'durations',
		'fee',
		'status'
	];

	public function tearoom()
	{
		return $this->belongsTo(\App\Models\Tearoom::class);
	}
}
