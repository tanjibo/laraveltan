<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class H5
 * 
 * @property int $id
 * @property string $name
 * @property string $image
 * @property string $dirname
 * @property int $visit_volume
 * @property int $share_volume
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 *
 * @package App\Models
 */
class H5 extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;
	protected $table = 'h5';

	protected $casts = [
		'visit_volume' => 'int',
		'share_volume' => 'int'
	];

	protected $fillable = [
		'name',
		'image',
		'dirname',
		'visit_volume',
		'share_volume'
	];
}
