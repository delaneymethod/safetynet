<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Models;

use App\Models\{County, Location};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'countries';
    
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
	];

	/**
	 * Get the location records associated with the country.
	 */
	public function locations() : HasMany
	{
		return $this->hasMany(Location::class);
	}
	
	/**
	 * Get the county records associated with the country.
	 */
	public function counties() : HasMany
	{
		return $this->hasMany(County::class);
	}
}
