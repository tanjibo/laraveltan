<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class RbacItem
 * 
 * @property int $id
 * @property string $name
 * @property string $title
 * @property int $pid
 * @property int $type
 * @property int $sort
 * @property int $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 *
 * @package App\Models
 */
class RbacItem extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;
	protected $table = 'rbac_item';

	protected $casts = [
		'pid' => 'int',
		'type' => 'int',
		'sort' => 'int',
		'status' => 'int'
	];

	protected $fillable = [
		'name',
		'title',
		'pid',
		'type',
		'sort',
		'status'
	];
}
