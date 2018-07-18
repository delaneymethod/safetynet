<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Http\Traits;

use App\Models\Sector;
use Illuminate\Database\Eloquent\Collection as CollectionResponse;

trait SectorTrait
{
	/**
	 * Get the specified sector based on id.
	 *
	 * @param 	int 		$id
	 * @return 	Object
	 */
	public function getSector(int $id) : Sector
	{
		return Sector::findOrFail($id);
	}
	
	/**
	 * Get all the sectors.
	 *
	 * @return 	Response
	 */
	public function getSectors() : CollectionResponse
	{
		return Sector::all();
	}
	
	/**
	 * Get all the sectors ordered.
	 */
	public function getSectorsByOrder() : CollectionResponse
	{
		return Sector::ordered()->get();
	}
	
	/**
	 * Get the sector record by their slug.
	 */
	public function getSectorBySlug(string $slug) : Sector
	{
		return Sector::where('slug', $slug)->where('status_id', 1)->firstOrFail();
	}
}
