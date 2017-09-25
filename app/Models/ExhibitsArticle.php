<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ExhibitsArticle
 * 
 * @property int $id
 * @property string $title
 * @property string $cover
 * @property string $content
 * @property int $sort
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 *
 * @package App\Models
 */
class ExhibitsArticle extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;
	protected $table = 'exhibits_article';

	protected $casts = [
		'sort' => 'int'
	];

	protected $fillable = [
		'title',
		'cover',
		'content',
		'sort'
	];
}
