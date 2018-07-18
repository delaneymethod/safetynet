<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class StatusesTableSeeder extends Seeder
{
	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		$now = Carbon::now()->format('Y-m-d H:i:s');
		
		$statuses = [
			[
				'title' => 'Active',
				'description' => NULL,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Inactive',
				'description' => NULL,
				'created_at' => $now,
				'updated_at' => $now,
			],
		];
		
		DB::table('statuses')->delete();
		
		DB::table('statuses')->insert($statuses);	
	}
}
