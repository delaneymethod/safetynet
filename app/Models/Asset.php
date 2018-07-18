<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'assets';
    
	protected $characterSet = 'UTF-8';
	
	protected $flags = ENT_QUOTES;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'id',
		'name',
		'file_name',
		'mime_type',
		'focus_point',
		'disk',
		'size',
		'extension',
		'path',
		'url',
		'width',
		'height',
	];
}
