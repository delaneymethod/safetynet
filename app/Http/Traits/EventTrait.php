<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Http\Traits;

use App\Models\Event;
use Illuminate\Database\Eloquent\Collection as CollectionResponse;

trait EventTrait
{
	/**
	 * Get the specified event based on id.
	 *
	 * @param 	int 		$id
	 * @return 	Object
	 */
	public function getEvent(int $id) : Event
	{
		return Event::findOrFail($id);
	}

	/**
	 * Get all the events.
	 *
	 * @return 	Response
	 */
	public function getEvents() : CollectionResponse
	{
		return Event::all();
	}
}
