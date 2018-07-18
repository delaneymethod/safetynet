<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Http\Traits;

use App\Models\Expert;
use Illuminate\Database\Eloquent\Collection as CollectionResponse;

trait ExpertTrait
{
	/**
	 * Get the specified expert based on id.
	 *
	 * @param 	int 		$id
	 * @return 	Object
	 */
	public function getExpert(int $id) : Expert
	{
		return Expert::findOrFail($id);
	}

	/**
	 * Get all the experts.
	 *
	 * @return 	Response
	 */
	public function getExperts() : CollectionResponse
	{
		return Expert::all();
	}
}
