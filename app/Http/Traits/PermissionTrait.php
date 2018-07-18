<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Http\Traits;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Collection as CollectionResponse;

trait PermissionTrait
{
	/**
	 * Get the specified permission based on id.
	 *
	 * @param 	int 		$id
	 * @return 	Object
	 */
	public function getPermission(int $id) : Permission
	{
		return Permission::findOrFail($id);
	}

	/**
	 * Get all the permissions.
	 *
	 * @return 	Response
	 */
	public function getPermissions() : CollectionResponse
	{
		return Permission::all();
	}
}
