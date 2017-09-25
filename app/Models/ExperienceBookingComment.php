<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ExperienceBookingComment
 * 
 * @property int $id
 * @property int $user_id
 * @property string $content
 * @property int $commentable_id
 * @property string $commentable_type
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @property int $score
 * @property int $parent_id
 * @property int $level
 * @property int $status
 * @property int $is_reply
 *
 * @package App\Models
 */
class ExperienceBookingComment extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;
	protected $table = 'experience_booking_comment';

	protected $casts = [
		'user_id' => 'int',
		'commentable_id' => 'int',
		'score' => 'int',
		'parent_id' => 'int',
		'level' => 'int',
		'status' => 'int',
		'is_reply' => 'int'
	];

	protected $fillable = [
		'user_id',
		'content',
		'commentable_id',
		'commentable_type',
		'score',
		'parent_id',
		'level',
		'status',
		'is_reply'
	];
}
