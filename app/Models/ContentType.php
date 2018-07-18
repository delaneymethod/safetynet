<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\{Status, Product, Category};
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany};

class ContentType extends Model
{
	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'content_types';
    
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
	];
	
	/**
	 * Get the status record associated with the content type.
	 */
	public function status() : BelongsTo
	{
		return $this->belongsTo(Status::class);
	}
	
	/**
	 * Get the category records associated with the content type.
	 */
	public function categories() : BelongsToMany
	{
		return $this->belongsToMany(Category::class);
	}
	
	/**
	 * Get the products records associated with the content type.
	 */
	public function products() : BelongsToMany
	{
		return $this->belongsToMany(Product::class);
	}
	
	/**
	 * Set categories for the content type.
	 *
	 * $param 	array 	$categories
	 */
	public function setCategories(array $categories)
	{
		return $this->categories()->sync($categories);
	}
	
	/**
	 * Set products for the content type.
	 *
	 * $param 	array 	$products
	 */
	public function setProducts(array $products)
	{
		return $this->products()->sync($products);
	}
	
	/**
	 * Checks if content type is active.
	 *
	 * @return bool
	 */
	public function isActive() : bool
	{
		return $this->status_id == 1;
	}
	
	/**
	 * Checks if content type is in active.
	 *
	 * @return bool
	 */
	public function isInactive() : bool
	{
		return $this->status_id == 2;
	}
}
