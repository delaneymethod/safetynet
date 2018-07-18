<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Http\Traits;

use App\Models\EventDateTime;
use Illuminate\Database\Eloquent\Collection as CollectionResponse;

trait EventDateTimeTrait
{
	/**
	 * Get the specified event date time based on id.
	 *
	 * @param 	int 		$id
	 * @return 	Object
	 */
	public function getEventDateTime(int $id) : EventDateTime
	{
		return EventDateTime::findOrFail($id);
	}

	/**
	 * Get all the events.
	 *
	 * @return 	Response
	 */
	public function getEventDatesTimes() : CollectionResponse
	{
		return EventDateTime::all();
	}
}
