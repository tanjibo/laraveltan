<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Sm
 * 
 * @property int $id
 * @property string $mobile
 * @property string $content
 * @property int $type
 * @property int $status
 * @property \Carbon\Carbon $created_at
 *
 * @package App\Models
 */
class Sm extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'type' => 'int',
		'status' => 'int'
	];

	protected $fillable = [
		'mobile',
		'content',
		'type',
		'status',
        'created_at'
	];
}
