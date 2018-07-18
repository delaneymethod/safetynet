<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Models;

use Spatie\EloquentSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\SortableTrait;
use App\Models\{Expert, Status, Sector, Product, ContentType};
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany};

class Category extends Model implements Sortable
{
	use SortableTrait;
	
	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'categories';
    
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
		'status_id',
		'order',
	];
	
	public $sortable = [
		'order_column_name' => 'order',
		'sort_when_creating' => true,
	];
	
	/**
	 * Get the status record associated with the category.
	 */
	public function status() : BelongsTo
	{
		return $this->belongsTo(Status::class);
	}
	
	/**
	 * Get the expert records associated with the category.
	 */
	public function experts() : BelongsToMany
	{
		return $this->belongsToMany(Expert::class);
	}
	
	/**
	 * Get the sector records associated with the category.
	 */
	public function sectors() : BelongsToMany
	{
		return $this->belongsToMany(Sector::class);
	}
	
	/**
	 * Get the content type records associated with the category.
	 */
	public function contentTypes() : BelongsToMany
	{
		return $this->belongsToMany(ContentType::class);
	}
	
	/**
	 * Get the products records associated with the category.
	 */
	public function products() : BelongsToMany
	{
		return $this->belongsToMany(Product::class);
	}
	
	/**
	 * Set experts for the category.
	 *
	 * $param 	array 	$experts
	 */
	public function setExperts(array $experts)
	{
		return $this->experts()->sync($experts);
	}
	
	/**
	 * Set sectors for the category.
	 *
	 * $param 	array 	$sectors
	 */
	public function setSectors(array $sectors)
	{
		return $this->sectors()->sync($sectors);
	}
	
	/**
	 * Set content types for the category.
	 *
	 * $param 	array 	$contentTypes
	 */
	public function setContentTypes(array $contentTypes)
	{
		return $this->contentTypes()->sync($contentTypes);
	}
	
	/**
	 * Checks if category is active.
	 *
	 * @return bool
	 */
	public function isActive() : bool
	{
		return $this->status_id == 1;
	}
	
	/**
	 * Checks if category is in active.
	 *
	 * @return bool
	 */
	public function isInactive() : bool
	{
		return $this->status_id == 2;
	}
}
