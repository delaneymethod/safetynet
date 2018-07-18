<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

use Illuminate\Database\Seeder;
use App\Http\Traits\PermissionTrait;

class RolePermissionTableSeeder extends Seeder
{
	use PermissionTrait;
	
	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('role_permission')->delete();
		
		$permissions = $this->getPermissions();
		
		// Super Admin Permissions
		$permissions->each(function ($permission) {
			$rolePermission = [
				'role_id' => 1,
				'permission_id' => $permission->id,
			];
			
			DB::table('role_permission')->insert($rolePermission);
		});
	}
}
