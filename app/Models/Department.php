<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\{Idea, Status, Sector, Product};
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany};

class Department extends Model
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'departments';
	
	protected $characterSet = 'UTF-8';
	
	protected $flags = ENT_QUOTES;
	
	private $segments = [];

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
	];
	
	/**
     * Attributes that get appended on serialization
     *
     * @var array
     */
	protected $appends = [
		'url',
	];
	
	/**
	 * Get the status record associated with the department.
	 */
	public function status() : BelongsTo
	{
		return $this->belongsTo(Status::class);
	}
	
	/**
	 * Get the sector records associated with the department.
	 */
	public function sectors() : BelongsToMany
	{
		return $this->belongsToMany(Sector::class);
	}
	
	/**
	 * Get the products records associated with the department.
	 */
	public function products() : BelongsToMany
	{
		return $this->belongsToMany(Product::class);
	}
	
	/**
	 * Get the idea records associated with the department.
	 */
	public function ideas() : BelongsToMany
	{
		return $this->belongsToMany(Idea::class);
	}
	
	/**
	 * Set sectors for the department.
	 *
	 * $param 	array 	$sectors
	 */
	public function setSectors(array $sectors)
	{
		return $this->sectors()->sync($sectors);
	}
	
	/**
	 * Set products for the department.
	 *
	 * $param 	array 	$products
	 */
	public function setProducts(array $products)
	{
		return $this->products()->sync($products);
	}
	
	/**
	 * Set ideas for the department.
	 *
	 * $param 	array 	$ideas
	 */
	public function setIdeas(array $ideas)
	{
		return $this->ideas()->sync($ideas);
	}
	
	/**
	 * Builds url.
	 *
	 * @return string
	 */
	public function getUrlAttribute() : string
	{
		$this->getSlug($this);
		
		// Add a blank segment to create first /
		array_push($this->segments, '');
		
		$this->segments = array_reverse($this->segments);
		
		return implode(DIRECTORY_SEPARATOR, $this->segments);
	}
	
	/**
	 * Sets a slug in the segments array.
	 *
	 * @return void
	 */
	private function getSlug($model)
	{
		array_push($this->segments, $model->slug);
	}
	
	/**
	 * Checks if department is active.
	 *
	 * @return bool
	 */
	public function isActive() : bool
	{
		return $this->status_id == 1;
	}
	
	/**
	 * Checks if department is in active.
	 *
	 * @return bool
	 */
	public function isInactive() : bool
	{
		return $this->status_id == 2;
	}
}
