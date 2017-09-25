<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ExperienceBookingCommentReplyPic
 * 
 * @property int $id
 * @property int $reply_id
 * @property string $pic_url
 *
 * @package App\Models
 */
class ExperienceBookingCommentReplyPic extends Eloquent
{
	protected $table = 'experience_booking_comment_reply_pic';
	public $timestamps = false;

	protected $casts = [
		'reply_id' => 'int'
	];

	protected $fillable = [
		'reply_id',
		'pic_url'
	];
}
