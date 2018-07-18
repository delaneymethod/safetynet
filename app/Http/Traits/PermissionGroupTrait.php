<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Http\Traits;

use App\Models\PermissionGroup;
use Illuminate\Database\Eloquent\Collection as CollectionResponse;

trait PermissionGroupTrait
{
	/**
	 * Get the specified permission group based on id.
	 *
	 * @param 	int 		$id
	 * @return 	Object
	 */
	public function getPermissionGroup(int $id) : PermissionGroup
	{
		return PermissionGroup::findOrFail($id);
	}

	/**
	 * Get all the permission groups.
	 *
	 * @return 	Response
	 */
	public function getPermissionGroups() : CollectionResponse
	{
		return PermissionGroup::all();
	}
}
