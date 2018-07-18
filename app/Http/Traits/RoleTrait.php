<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Http\Traits;

use App\Models\Role;
use Illuminate\Database\Eloquent\Collection as CollectionResponse;

trait RoleTrait
{
	/**
	 * Get the specified role based on id.
	 *
	 * @param 	int 		$id
	 * @return 	Object
	 */
	public function getRole(int $id) : Role
	{
		return Role::findOrFail($id);
	}

	/**
	 * Get all the roles.
	 *
	 * @return 	Response
	 */
	public function getRoles() : CollectionResponse
	{
		return Role::all();
	}
}
