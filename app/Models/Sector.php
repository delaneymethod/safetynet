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
use App\Models\{Event, Status, Product, Category, Department};
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany};

class Sector extends Model implements Sortable
{
	use SortableTrait;
	
	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sectors';
    
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
		'yammer',
		'stream',
		'status_id',
		'order',
		'colour',
	];
	
	public $sortable = [
		'order_column_name' => 'order',
		'sort_when_creating' => true,
	];
	
	/**
	 * Get the status record associated with the sector.
	 */
	public function status() : BelongsTo
	{
		return $this->belongsTo(Status::class);
	}
	
	/**
	 * Get the event records associated with the sector.
	 */
	public function events() : BelongsToMany
	{
		return $this->belongsToMany(Event::class);
	}
	
	/**
	 * Get the department records associated with the sector.
	 */
	public function departments() : BelongsToMany
	{
		return $this->belongsToMany(Department::class);
	}
	
	/**
	 * Get the category records associated with the sector.
	 */
	public function categories() : BelongsToMany
	{
		return $this->belongsToMany(Category::class);
	}
	
	/**
	 * Get the product records associated with the sector.
	 */
	public function products() : BelongsToMany
	{
		return $this->belongsToMany(Product::class);
	}
	
	/**
	 * Set events for the sector.
	 *
	 * $param 	array 	$events
	 */
	public function setEvents(array $events)
	{
		return $this->events()->sync($events);
	}
	
	/**
	 * Set departments for the sector.
	 *
	 * $param 	array 	$departments
	 */
	public function setDepartments(array $departments)
	{
		return $this->departments()->sync($departments);
	}
	
	/**
	 * Set categories for the sector.
	 *
	 * $param 	array 	$categories
	 */
	public function setCategories(array $categories)
	{
		return $this->categories()->sync($categories);
	}
	
	/**
	 * Checks if sector is active.
	 *
	 * @return bool
	 */
	public function isActive() : bool
	{
		return $this->status_id == 1;
	}
	
	/**
	 * Checks if sector is in active.
	 *
	 * @return bool
	 */
	public function isInactive() : bool
	{
		return $this->status_id == 2;
	}
}
