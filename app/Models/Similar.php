<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Similar
 * 
 * @property int $item_id
 * @property int $similar_id
 * @property int $type
 *
 * @package App\Models
 */
class Similar extends Eloquent
{
	protected $table = 'similar';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'item_id' => 'int',
		'similar_id' => 'int',
		'type' => 'int'
	];

	protected $fillable = [
		'item_id',
		'similar_id',
		'type'
	];
}
