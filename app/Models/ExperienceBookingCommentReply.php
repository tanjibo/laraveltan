<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ExperienceBookingCommentReply
 * 
 * @property int $id
 * @property int $comment_id
 * @property int $admin_id
 * @property string $reply
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @property string $username
 *
 * @package App\Models
 */
class ExperienceBookingCommentReply extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;
	protected $table = 'experience_booking_comment_reply';

	protected $casts = [
		'comment_id' => 'int',
		'admin_id' => 'int'
	];

	protected $fillable = [
		'comment_id',
		'admin_id',
		'reply',
		'username'
	];
}
