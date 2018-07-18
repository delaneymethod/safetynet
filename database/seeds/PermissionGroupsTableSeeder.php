<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PermissionGroupsTableSeeder extends Seeder
{
	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		$now = Carbon::now()->format('Y-m-d H:i:s');
		
		$permissionGroups = [
			[	
				'title' => 'Users',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[	
				'title' => 'Permissions',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[	
				'title' => 'Assets',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[	
				'title' => 'Statuses',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[	
				'title' => 'Roles',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Globals',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Departments',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Sectors',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Categories',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Content Types',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Products',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Models',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Experts',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Locations',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Events',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Ideas',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'IP Library',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Team Members',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Designer Hub',
				'created_at' => $now,
				'updated_at' => $now,
			],
		];
		
		DB::table('permission_groups')->delete();
		
		DB::table('permission_groups')->insert($permissionGroups);
	}
}
