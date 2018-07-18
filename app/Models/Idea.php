<?php
/**
 * @link	  https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license	  https://www.delaneymethod.com/cms/license
 */

namespace App\Models;

use Spatie\MediaLibrary\Media;
use App\Models\{Status, Department};
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia; // Conversions;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany};

class Idea extends EloquentModel implements HasMedia // Conversions
{
	use HasMediaTrait;
	
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'ideas';
	
	protected $characterSet = 'UTF-8';
	
	protected $flags = ENT_QUOTES;
	
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'id',
		'title' ,
		'slug',
		'description',
		'image',
		'status_id',
	];
	
	/**
	 * Get the status record associated with the idea and business case.
	 */
	public function status() : BelongsTo
	{
		return $this->belongsTo(Status::class);
	}
	
	/**
	 * Get the department record associated with the update.
	 */
	public function departments() : BelongsToMany
	{
		return $this->belongsToMany(Department::class);
	}
	
	/**
	 * Set departments for the idea or business case.
	 *
	 * $param 	array 	$departments
	 */
	public function setDepartments(array $departments)
	{
		return $this->departments()->sync($departments);
	}
	
	/**
	 * Checks if idea and business case is active.
	 *
	 * @return bool
	 */
	public function isActive() : bool
	{
		return $this->status_id == 1;
	}
	
	/**
	 * Checks if idea and business case is in active.
	 *
	 * @return bool
	 */
	public function isInactive() : bool
	{
		return $this->status_id == 2;
	}
	
	public function registerMediaConversions(Media $media = null)
	{
		$this->addMediaConversion('thumbnail')->width(200)->height(200)->sharpen(10)->nonQueued();
	}
}
