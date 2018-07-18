<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Models;

use App\Models\{Status, Product};
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany};

class Model extends EloquentModel
{
	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'models';
    
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
		'minimum_number_of_units',
		'status_id',
	];
	
	/**
	 * Get the status record associated with the model.
	 */
	public function status() : BelongsTo
	{
		return $this->belongsTo(Status::class);
	}
	
	/**
	 * Get the products records associated with the model.
	 */
	public function products() : BelongsToMany
	{
		return $this->belongsToMany(Product::class);
	}
	
	/**
	 * Set products for the model.
	 *
	 * $param 	array 	$products
	 */
	public function setProducts(array $products)
	{
		return $this->products()->sync($products);
	}
	
	/**
	 * Checks if model is active.
	 *
	 * @return bool
	 */
	public function isActive() : bool
	{
		return $this->status_id == 1;
	}
	
	/**
	 * Checks if model is in active.
	 *
	 * @return bool
	 */
	public function isInactive() : bool
	{
		return $this->status_id == 2;
	}
}
