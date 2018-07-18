<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ModelsTableSeeder extends Seeder
{
	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		$now = Carbon::now()->format('Y-m-d H:i:s');
		
		$models = [
			[
				'title' => 'F7R',
				'slug' => 'f7r',
				'minimum_number_of_units' => NULL,
				'status_id' => 1,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'F10R',
				'slug' => 'f10r',
				'minimum_number_of_units' => NULL,
				'status_id' => 1,
				'created_at' => $now,
				'updated_at' => $now,
			],
		];
		
		DB::table('models')->delete();
		
		DB::table('models')->insert($models);
	}
}
