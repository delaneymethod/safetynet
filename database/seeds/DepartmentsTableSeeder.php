<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DepartmentsTableSeeder extends Seeder
{
	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		$now = Carbon::now()->format('Y-m-d H:i:s');
		
		$departments = [
			[
				'title' => 'Source',
				'slug' => 'source',
				'description' => 'Your source for information, sales guidance and strategy.',
				'banner' => 'banner-images/source-banner.png',
				'image' => 'source-logo.png',
				'yammer' => '13172895',
				'stream' => '30ae7639-646f-45ff-bee1-9ff172682c0b',
				'status_id' => 1,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Nexo',
				'slug' => 'nexo',
				'description' => 'Explore and have your say on new and existing Survitec products.',
				'banner' => NULL,
				'image' => 'nexo-logo.png',
				'yammer' => NULL,
				'stream' => NULL,
				'status_id' => 1,
				'created_at' => $now,
				'updated_at' => $now,
			],
		];
		
		DB::table('departments')->delete();
		
		DB::table('departments')->insert($departments);
	}
}
