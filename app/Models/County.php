<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Models;

use App\Models\{Country, Location};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{HasMany, BelongsTo};

class County extends Model
{
	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'counties';
    
	protected $characterSet = 'UTF-8';
	
	protected $flags = ENT_QUOTES;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'id',
		'country_id',
		'title',
	];

	/**
	 * Get the location records associated with the county.
	 */
	public function locations() : HasMany
	{
		return $this->hasMany(Location::class);
	}
	
	/**
	 * Get the country record associated with the county.
	 */
	public function country() : BelongsTo
	{
		return $this->belongsTo(Country::class);
	}
}
