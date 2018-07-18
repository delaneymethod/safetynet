<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Http\Traits;

use App\Models\Globals;
use Illuminate\Database\Eloquent\Collection as CollectionResponse;

trait GlobalTrait
{
	/**
	 * Get the specified global based on id.
	 *
	 * @param 	int 		$id
	 * @return 	Object
	 */
	public function getGlobal(int $id) : Globals
	{
		return Globals::findOrFail($id);
	}
	
	/**
	 * Get all the globals.
	 *
	 * @return 	Response
	 */
	public function getGlobals() : CollectionResponse
	{
		return Globals::all();
	}
}
