<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class RbacAccess
 * 
 * @property int $access_id
 * @property int $item_id
 * @property int $type
 *
 * @package App\Models
 */
class RbacAccess extends Eloquent
{
	protected $table = 'rbac_access';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'access_id' => 'int',
		'item_id' => 'int',
		'type' => 'int'
	];

	protected $fillable = [
		'access_id',
		'item_id',
		'type'
	];
}
