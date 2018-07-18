<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Http\Traits;

use App\Models\Department;
use Illuminate\Database\Eloquent\Collection as CollectionResponse;

trait DepartmentTrait
{
	/**
	 * Get the specified department based on id.
	 *
	 * @param 	int 		$id
	 * @return 	Object
	 */
	public function getDepartment(int $id) : Department
	{
		return Department::findOrFail($id);
	}
	
	/**
	 * Get all the departments.
	 *
	 * @return 	Response
	 */
	public function getDepartments() : CollectionResponse
	{
		return Department::all();
	}
	
	/**
	 * Get the department record by their slug.
	 */
	public function getDepartmentBySlug(string $slug) : Department
	{
		return Department::where('slug', $slug)->where('status_id', 1)->firstOrFail();
	}
}
