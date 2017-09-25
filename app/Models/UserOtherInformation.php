<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class UserOtherInformation
 * 
 * @property int $id
 * @property int $user_id
 * @property int $age
 * @property string $nationality
 * @property string $profession
 * @property string $education
 * @property string $id_card
 * @property \Carbon\Carbon $birthday
 * @property string $wechat
 * @property string $intention
 * @property string $remark
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \App\Models\User $user
 *
 * @package App\Models
 */
class UserOtherInformation extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;
	protected $table = 'user_other_information';

	protected $casts = [
		'user_id' => 'int',
		'age' => 'int'
	];

	protected $dates = [
		'birthday'
	];

	protected $fillable = [
		'user_id',
		'age',
		'nationality',
		'profession',
		'education',
		'id_card',
		'birthday',
		'wechat',
		'intention',
		'remark'
	];

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class);
	}
}
