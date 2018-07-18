<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Models;

use App\Models\{Status, Category};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany};

class Expert extends Model
{
	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'experts';
    
	protected $characterSet = 'UTF-8';
	
	protected $flags = ENT_QUOTES;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'id',
		'full_name',
		'email',
		'position',
		'image',
		'status_id',
	];
	
	/**
	 * Get the status record associated with the expert.
	 */
	public function status() : BelongsTo
	{
		return $this->belongsTo(Status::class);
	}
	
	/**
	 * Get the categories records associated with the expert.
	 */
	public function categories() : BelongsToMany
	{
		return $this->belongsToMany(Category::class);
	}
	
	/**
	 * Set categories for the expert.
	 *
	 * $param 	array 	$categories
	 */
	public function setCategories(array $categories)
	{
		return $this->categories()->sync($categories);
	}
	
	/**
	 * Checks if expert is active.
	 *
	 * @return bool
	 */
	public function isActive() : bool
	{
		return $this->status_id == 1;
	}
	
	/**
	 * Checks if expert is in active.
	 *
	 * @return bool
	 */
	public function isInactive() : bool
	{
		return $this->status_id == 2;
	}
}
