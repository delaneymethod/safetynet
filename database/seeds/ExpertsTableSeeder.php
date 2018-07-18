<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ExpertsTableSeeder extends Seeder
{
	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		$now = Carbon::now()->format('Y-m-d H:i:s');
		
		$experts = [
			[
				'full_name' => 'David Stelling',
				'email' => 'communications@survitecgroup.com',
				'position' => 'Global Category Manager',
				'image' => 'http://via.placeholder.com/800x800?text=Test%20Image',
				'status_id' => 1,
				'created_at' => $now,
				'updated_at' => $now,
			],
		];
		
		DB::table('experts')->delete();
		
		DB::table('experts')->insert($experts);
	}
}
