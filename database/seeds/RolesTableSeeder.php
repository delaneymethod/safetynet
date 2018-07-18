<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		$now = Carbon::now()->format('Y-m-d H:i:s');
		
		$roles = [
			[
				'title' => 'Super Administrator',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Administrator',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Design Engineer',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Sales',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Marketing',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Customer Service',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Distributor',
				'created_at' => $now,
				'updated_at' => $now,
			],
		];
		
		DB::table('roles')->delete();
		
		DB::table('roles')->insert($roles);
	}
}
