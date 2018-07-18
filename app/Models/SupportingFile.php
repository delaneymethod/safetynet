<?php
/**
 * @link	  https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license	  https://www.delaneymethod.com/cms/license
 */

namespace App\Models;

use Spatie\MediaLibrary\Media;

class SupportingFile extends Media
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'supporting_files';
	
	protected $characterSet = 'UTF-8';
	
	protected $flags = ENT_QUOTES;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'id',
		'model',
		'collection_name',
		'name',
		'file_name',
		'mime_type',
		'disk',
		'size',
		'manipulations',
		'custom_properties',
		'order_column',
	];
}
