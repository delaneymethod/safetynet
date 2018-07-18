<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Http\Traits;

use App\Models\Status;
use Illuminate\Database\Eloquent\Collection as CollectionResponse;

trait StatusTrait
{
	/**
	 * Get the specified status based on id.
	 *
	 * @param 	int 		$id
	 * @return 	Object
	 */
	public function getStatus(int $id) : Status
	{
		return Status::findOrFail($id);
	}
	
	/**
	 * Get the specified status based on title.
	 *
	 * @param 	string 		$title
	 * @return 	Object
	 */
	public function getStatusByTitle(string $title) : Status
	{
		return Status::where('title', $title)->firstOrFail();
	}

	/**
	 * Get all the statuses.
	 *
	 * @return 	Response
	 */
	public function getStatuses() : CollectionResponse
	{
		return Status::all();
	}
}
