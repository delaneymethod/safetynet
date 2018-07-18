<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Http\Traits;

use App\Models\Location;
use Illuminate\Support\Collection as SupportCollectionResponse;

trait LocationTrait
{
	/**
	 * Get the specified location based on id.
	 *
	 * @param 	int 		$id
	 * @return 	Object
	 */
	public function getLocation(int $id) : Location
	{
		return Location::findOrFail($id);
	}

	/**
	 * Get all the locations.
	 *
	 * @return 	Response
	 */
	public function getLocations() : SupportCollectionResponse
	{
		return Location::orderBy('title')->get();
	}
}
