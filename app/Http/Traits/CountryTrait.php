<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Http\Traits;

use App\Models\Country;
use Illuminate\Database\Eloquent\Collection as CollectionResponse;

trait CountryTrait
{
	/**
	 * Get the specified country based on id.
	 *
	 * @param 	int 		$id
	 * @return 	Object
	 */
	public function getCountry(int $id) : Country
	{
		return Country::findOrFail($id);
	}

	/**
	 * Get all the countries.
	 *
	 * @return 	Collection
	 */
	public function getCountries() : CollectionResponse
	{
		return Country::all();
	}
}
