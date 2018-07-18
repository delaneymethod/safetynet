<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		$now = Carbon::now()->format('Y-m-d H:i:s');
		
		$permissions = [
			[
				'title' => 'view_users',
				'permission_group_id' => 1,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'create_users',
				'permission_group_id' => 1,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'edit_users',
				'permission_group_id' => 1,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'edit_passwords_users',
				'permission_group_id' => 1,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'delete_users',
				'permission_group_id' => 1,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'view_permissions',
				'permission_group_id' => 2,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'create_permissions',
				'permission_group_id' => 2,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'edit_permissions',
				'permission_group_id' => 2,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'delete_permissions',
				'permission_group_id' => 2,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'view_assets',
				'permission_group_id' => 3,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'upload_assets',
				'permission_group_id' => 3,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'edit_assets',
				'permission_group_id' => 3,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'move_assets',
				'permission_group_id' => 3,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'delete_assets',
				'permission_group_id' => 3,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'view_statuses',
				'permission_group_id' => 4,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'create_statuses',
				'permission_group_id' => 4,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'edit_statuses',
				'permission_group_id' => 4,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'delete_statuses',
				'permission_group_id' => 4,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'view_roles',
				'permission_group_id' => 5,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'create_roles',
				'permission_group_id' => 5,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'edit_roles',
				'permission_group_id' => 5,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'delete_roles',
				'permission_group_id' => 5,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'view_globals',
				'permission_group_id' => 6,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'create_globals',
				'permission_group_id' => 6,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'edit_globals',
				'permission_group_id' => 6,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'delete_globals',
				'permission_group_id' => 6,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'view_departments',
				'permission_group_id' => 7,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'create_departments',
				'permission_group_id' => 7,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'edit_departments',
				'permission_group_id' => 7,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'delete_departments',
				'permission_group_id' => 7,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'view_sectors',
				'permission_group_id' => 8,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'create_sectors',
				'permission_group_id' => 8,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'edit_sectors',
				'permission_group_id' => 8,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'delete_sectors',
				'permission_group_id' => 8,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'view_categories',
				'permission_group_id' => 9,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'create_categories',
				'permission_group_id' => 9,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'edit_categories',
				'permission_group_id' => 9,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'delete_categories',
				'permission_group_id' => 9,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'view_content_types',
				'permission_group_id' => 10,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'create_content_types',
				'permission_group_id' => 10,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'edit_content_types',
				'permission_group_id' => 10,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'delete_content_types',
				'permission_group_id' => 10,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'view_products',
				'permission_group_id' => 11,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'create_products',
				'permission_group_id' => 11,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'edit_products',
				'permission_group_id' => 11,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'delete_products',
				'permission_group_id' => 11,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'view_models',
				'permission_group_id' => 12,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'create_models',
				'permission_group_id' => 12,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'edit_models',
				'permission_group_id' => 12,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'delete_models',
				'permission_group_id' => 12,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'view_experts',
				'permission_group_id' => 13,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'create_experts',
				'permission_group_id' => 13,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'edit_experts',
				'permission_group_id' => 13,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'delete_experts',
				'permission_group_id' => 13,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'view_locations',
				'permission_group_id' => 14,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'create_locations',
				'permission_group_id' => 14,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'edit_locations',
				'permission_group_id' => 14,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'delete_locations',
				'permission_group_id' => 14,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'view_events',
				'permission_group_id' => 15,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'create_events',
				'permission_group_id' => 15,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'edit_events',
				'permission_group_id' => 15,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'delete_events',
				'permission_group_id' => 15,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'view_ideas',
				'permission_group_id' => 16,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'create_ideas',
				'permission_group_id' => 16,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'edit_ideas',
				'permission_group_id' => 16,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'delete_ideas',
				'permission_group_id' => 16,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'view_ip_library',
				'permission_group_id' => 17,
				'created_at' => $now,
				'updated_at' => $now,
			],
			/*
			[
				'title' => 'create_ip_library',
				'permission_group_id' => 17,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'edit_ip_library',
				'permission_group_id' => 17,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'delete_ip_library',
				'permission_group_id' => 17,
				'created_at' => $now,
				'updated_at' => $now,
			],
			*/
			[
				'title' => 'view_team_members',
				'permission_group_id' => 18,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'create_team_members',
				'permission_group_id' => 18,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'edit_team_members',
				'permission_group_id' => 18,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'delete_team_members',
				'permission_group_id' => 18,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'view_designer_hub',
				'permission_group_id' => 19,
				'created_at' => $now,
				'updated_at' => $now,
			],
		];
				
		DB::table('permissions')->delete();
				
		DB::table('permissions')->insert($permissions);
	}
}
