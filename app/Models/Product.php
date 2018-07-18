<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Models;

use Spatie\MediaLibrary\Media;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia; // Conversions;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany};
use App\Models\{Model, Status, Sector, Category, ContentType, Department};

class Product extends EloquentModel implements HasMedia // Conversions
{
	use HasMediaTrait;
	
	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'products';
    
	protected $characterSet = 'UTF-8';
	
	protected $flags = ENT_QUOTES;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'id',
		'title',
		'slug',
		'description',
		'banner',
		'image',
		'video',
		'overview',
		'due_date',
		'npd_feedback_recipient',
		'ex_feedback_recipient',
		'status_id',
	];
	
	/**
	 * Get the status record associated with the product.
	 */
	public function status() : BelongsTo
	{
		return $this->belongsTo(Status::class);
	}
	
	/**
	 * Get the department record associated with the product.
	 */
	public function departments() : BelongsToMany
	{
		return $this->belongsToMany(Department::class);
	}
	
	/**
	 * Get the sector record associated with the product.
	 */
	public function sectors() : BelongsToMany
	{
		return $this->belongsToMany(Sector::class);
	}
	
	/**
	 * Get the category record associated with the product.
	 */
	public function categories() : BelongsToMany
	{
		return $this->belongsToMany(Category::class);
	}
	
	/**
	 * Get the content type record associated with the product.
	 */
	public function contentTypes() : BelongsToMany
	{
		return $this->belongsToMany(ContentType::class);
	}
	
	/**
	 * Get the model record associated with the product.
	 */
	public function models() : BelongsToMany
	{
		return $this->belongsToMany(Model::class);
	}
	
	/**
	 * Set departments for the product.
	 *
	 * $param 	array 	$departments
	 */
	public function setDepartments(array $departments)
	{
		return $this->departments()->sync($departments);
	}
	
	/**
	 * Set sectors for the product.
	 *
	 * $param 	array 	$sectors
	 */
	public function setSectors(array $sectors)
	{
		return $this->sectors()->sync($sectors);
	}
	
	/**
	 * Set categories for the product.
	 *
	 * $param 	array 	$categories
	 */
	public function setCategories(array $categories)
	{
		return $this->categories()->sync($categories);
	}
	
	/**
	 * Set content types for the product.
	 *
	 * $param 	array 	$contentTypes
	 */
	public function setContentTypes(array $contentTypes)
	{
		return $this->contentTypes()->sync($contentTypes);
	}
	
	/**
	 * Checks if product is active.
	 *
	 * @return bool
	 */
	public function isActive() : bool
	{
		return $this->status_id == 1;
	}
	
	/**
	 * Checks if product is in active.
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
