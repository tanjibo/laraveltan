<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Supplier
 * 
 * @property int $id
 * @property string $name
 * @property string $label
 * @property string $contact
 * @property string $phone
 * @property string $address
 * @property string $remark
 * @property int $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 *
 * @package App\Models
 */
class Supplier extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;
	protected $table = 'supplier';

	protected $casts = [
		'status' => 'int'
	];

	protected $fillable = [
		'name',
		'label',
		'contact',
		'phone',
		'address',
		'remark',
		'status'
	];
}
