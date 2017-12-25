<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 06 Dec 2017 15:59:04 +0800.
 */

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Spatie\Activitylog\Traits\LogsActivity;

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
    use LogsActivity;
	protected $casts = [
		'comment_id' => 'int',
		'admin_id' => 'int'
	];

	protected $hidden = [
		'deleted_at'
	];

	protected $fillable = [
		'comment_id',
		'admin_id',
		'reply',
		'username'
	];
}
