<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Advertising
 * 
 * @property int $id
 * @property string $name
 * @property string $image
 * @property int $position
 * @property string $link
 * @property int $sort
 * @property int $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 *
 * @package App\Models
 */
class Advertising extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;
	protected $table = 'advertising';

	protected $casts = [
		'position' => 'int',
		'sort' => 'int',
		'status' => 'int'
	];

	protected $fillable = [
		'name',
		'image',
		'position',
		'link',
		'sort',
		'status'
	];
}
