<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ExperienceReplyTpl
 * 
 * @property int $id
 * @property string $tpl
 * @property string $username
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package App\Models
 */
class ExperienceReplyTpl extends Eloquent
{
	protected $table = 'experience_reply_tpl';

	protected $fillable = [
		'tpl',
		'username'
	];
}
