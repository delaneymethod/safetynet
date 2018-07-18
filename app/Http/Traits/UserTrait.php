<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Http\Traits;

use App\User;
use Illuminate\Support\Collection as SupportCollectionResponse;
use Illuminate\Database\Eloquent\Collection as EloquentCollectionResponse;

trait UserTrait
{
	/**
	 * Get the specified user based on id.
	 *
	 * @param 	int 		$id
	 * @return 	Object
	 */
	public function getUser(int $id) : User
	{
		return User::findOrFail($id);
	}
	
	/**
	 * Get all the users.
	 *
	 * @return 	Collection
	 */
	public function getUsers() : SupportCollectionResponse
	{
		return User::latest()->get();
	}
	
	/**
	 * Get the user record by their id - mainly used for user activation since we dont want to filter the users array in getUser above..
	 */
	public function getUserById(int $id) : User
	{
		return User::where('id', $id)->first();
	}

	/**
	 * Get the user record by their email.
	 */
	public function getUserByEmail(string $email) : User
	{
		return User::where('email', $email)->first();
	}
	
	/**
	 * Get the super admin user records
	 *
	 * @return 	Response
	 */
	public function getSuperAdmins() : EloquentCollectionResponse
	{
		return User::where('role_id', 1)->get();
	}
	
	/**
	 * Get the admin user records
	 *
	 * @return 	Response
	 */
	public function getAdmins() : EloquentCollectionResponse
	{
		return User::where('role_id', 2)->get();
	}
	
	/**
	 * Get the user record by their email off the User Model.
	 */
	public static function getByEmail(string $email) : User
	{
		return self::where('email', $email)->first();
	}
}
