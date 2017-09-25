<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ComboDetail
 * 
 * @property int $combo_id
 * @property string $link
 * @property string $images
 * @property string $poster
 * @property string $intro
 * @property string $remark
 * 
 * @property \App\Models\Combo $combo
 *
 * @package App\Models
 */
class ComboDetail extends Eloquent
{
	protected $table = 'combo_detail';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'combo_id' => 'int'
	];

	protected $fillable = [
		'combo_id',
		'link',
		'images',
		'poster',
		'intro',
		'remark'
	];

	public function combo()
	{
		return $this->belongsTo(\App\Models\Combo::class);
	}
}
