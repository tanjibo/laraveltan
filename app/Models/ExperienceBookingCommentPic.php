<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ExperienceBookingCommentPic
 * 
 * @property int $id
 * @property string $pic_url
 * @property int $comment_id
 *
 * @package App\Models
 */
class ExperienceBookingCommentPic extends Eloquent
{
	protected $table = 'experience_booking_comment_pic';
	public $timestamps = false;

	protected $casts = [
		'comment_id' => 'int'
	];

	protected $fillable = [
		'pic_url',
		'comment_id'
	];
}
