<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Review
 * 
 * @property int $id
 * @property int $user_id
 * @property int $order_id
 * @property int $item_id
 * @property int $type
 * @property string $content
 * @property int $anonymous
 * @property int $recommend
 * @property int $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \App\Models\User $user
 *
 * @package App\Models
 */
class Review extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'user_id' => 'int',
		'order_id' => 'int',
		'item_id' => 'int',
		'type' => 'int',
		'anonymous' => 'int',
		'recommend' => 'int',
		'status' => 'int'
	];

	protected $fillable = [
		'user_id',
		'order_id',
		'item_id',
		'type',
		'content',
		'anonymous',
		'recommend',
		'status'
	];

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class);
	}
}
