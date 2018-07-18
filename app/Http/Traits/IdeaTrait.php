<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Http\Traits;

use App\Models\Idea;
use Illuminate\Database\Eloquent\Collection as CollectionResponse;

trait IdeaTrait
{
	/**
	 * Get the specified idea based on id.
	 *
	 * @param 	int 		$id
	 * @return 	Object
	 */
	public function getIdea(int $id) : Idea
	{
		return Idea::findOrFail($id);
	}

	/**
	 * Get all the ideas.
	 *
	 * @return 	Response
	 */
	public function getIdeas() : CollectionResponse
	{
		return Idea::all();
	}
}
