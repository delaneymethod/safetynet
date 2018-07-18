<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Http\Traits;

use App\Models\Model;
use Illuminate\Database\Eloquent\Collection as CollectionResponse;

trait ModelTrait
{
	/**
	 * Get the specified model based on id.
	 *
	 * @param 	int 		$id
	 * @return 	Object
	 */
	public function getModel(int $id) : Model
	{
		return Model::findOrFail($id);
	}

	/**
	 * Get all the models.
	 *
	 * @return 	Response
	 */
	public function getModels() : CollectionResponse
	{
		return Model::all();
	}
}
