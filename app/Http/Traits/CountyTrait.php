<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Http\Traits;

use App\Models\County;
use Illuminate\Database\Eloquent\Collection;

trait CountyTrait
{
	/**
	 * Get the specified county based on id.
	 *
	 * @param 	int 		$id
	 * @return 	Object
	 */
	public function getCounty(int $id) : County
	{
		return County::findOrFail($id);
	}

	/**
	 * Get all the counties or the specified counties.
	 *
	 * Example:
	 * 	- $this->getCountyWhere([$field => $value])->first()
	 *
	 * @return 	Collection
	 */
	public function getCountiesWhere(array $params) : County
	{
		return County::where($params)->get()->first();
	}

	/**
	 * Get all the counties.
	 *
	 * @return 	Collection
	 */
	public function getCounties() : Collection
	{
		return County::all();
	}
}
