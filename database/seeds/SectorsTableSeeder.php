<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SectorsTableSeeder extends Seeder
{
	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		$now = Carbon::now()->format('Y-m-d H:i:s');
		
		$sectors = [
			[
				'title' => 'Marine',
				'slug' => 'marine',
				'description' => 'Marine',
				'banner' => NULL,
				'image' => 'marine.png',
				'yammer' => '13172895',
				'stream' => '30ae7639-646f-45ff-bee1-9ff172682c0b',
				'status_id' => 1,
				'order' => NULL,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Aviation',
				'slug' => 'aviation',
				'description' => 'Aviation',
				'banner' => NULL,
				'image' => 'aviation.png',
				'yammer' => NULL,
				'stream' => NULL,
				'status_id' => 1,
				'order' => NULL,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Defence',
				'slug' => 'defence',
				'description' => 'Defence',
				'banner' => NULL,
				'image' => 'defence.png',
				'yammer' => NULL,
				'stream' => NULL,
				'status_id' => 1,
				'order' => NULL,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Offshore',
				'slug' => 'offshore',
				'description' => 'Offshore',
				'banner' => NULL,
				'image' => 'offshore.png',
				'yammer' => NULL,
				'stream' => NULL,
				'status_id' => 1,
				'order' => NULL,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'New Product Development',
				'slug' => 'new-product-development',
				'description' => 'New Product Development',
				'banner' => NULL,
				'image' => 'http://via.placeholder.com/800x800?text=Test%20Image',
				'yammer' => NULL,
				'stream' => NULL,
				'status_id' => 1,
				'order' => NULL,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Existing Products',
				'slug' => 'existing-products',
				'description' => 'Existing Products',
				'banner' => NULL,
				'image' => 'http://via.placeholder.com/800x800?text=Test%20Image',
				'yammer' => NULL,
				'stream' => NULL,
				'status_id' => 1,
				'order' => NULL,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'IP Library',
				'slug' => 'ip-library',
				'description' => 'IP Library',
				'banner' => NULL,
				'image' => 'http://via.placeholder.com/800x800?text=Test%20Image',
				'yammer' => NULL,
				'stream' => NULL,
				'status_id' => 1,
				'order' => NULL,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Designer Hub',
				'slug' => 'designer-hub',
				'description' => 'Designer Hub',
				'banner' => NULL,
				'image' => 'http://via.placeholder.com/800x800?text=Test%20Image',
				'yammer' => NULL,
				'stream' => NULL,
				'status_id' => 1,
				'order' => NULL,
				'created_at' => $now,
				'updated_at' => $now,
			],
		];
		
		DB::table('sectors')->delete();
		
		DB::table('sectors')->insert($sectors);
	}
}
