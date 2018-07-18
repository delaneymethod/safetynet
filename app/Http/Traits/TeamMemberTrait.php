<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Http\Traits;

use App\Models\TeamMember;
use Illuminate\Database\Eloquent\Collection as CollectionResponse;

trait TeamMemberTrait
{
	/**
	 * Get the specified team member based on id.
	 *
	 * @param 	int 		$id
	 * @return 	Object
	 */
	public function getTeamMember(int $id) : TeamMember
	{
		return TeamMember::findOrFail($id);
	}

	/**
	 * Get all the team members.
	 *
	 * @return 	Response
	 */
	public function getTeamMembers() : CollectionResponse
	{
		return TeamMember::all();
	}
	
	/**
	 * Get all the team members ordered.
	 */
	public function getTeamMembersByOrder() : CollectionResponse
	{
		return TeamMember::ordered()->get();
	}
}
