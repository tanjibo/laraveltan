<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ExperienceRoomQuestion
 * 
 * @property int $id
 * @property string $question
 * @property string $answer
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @property string $handler
 * @property int $status
 *
 * @package App\Models
 */
class ExperienceRoomQuestion extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'status' => 'int'
	];

	protected $fillable = [
		'question',
		'answer',
		'handler',
		'status'
	];
}
