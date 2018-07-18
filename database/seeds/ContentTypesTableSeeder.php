<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ContentTypesTableSeeder extends Seeder
{
	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		$now = Carbon::now()->format('Y-m-d H:i:s');
		
		$contentTypes = [
			[
				'title' => 'Product Offering',
				'slug' => 'product-offering',
				'description' => 'Product Offering',
				'banner' => NULL,
				'image' => 'http://via.placeholder.com/800x800?text=Test%20Image',
				'status_id' => 1,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Images',
				'slug' => 'images',
				'description' => 'Images',
				'banner' => NULL,
				'image' => 'http://via.placeholder.com/800x800?text=Test%20Image',
				'status_id' => 1,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Videos',
				'slug' => 'videos',
				'description' => 'Videos',
				'banner' => NULL,
				'image' => 'http://via.placeholder.com/800x800?text=Test%20Image',
				'status_id' => 1,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Literature',
				'slug' => 'literature',
				'description' => 'Literature',
				'banner' => NULL,
				'image' => 'http://via.placeholder.com/800x800?text=Test%20Image',
				'status_id' => 1,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Market Intel',
				'slug' => 'market-intel',
				'description' => 'Market Intel',
				'banner' => NULL,
				'image' => 'http://via.placeholder.com/800x800?text=Test%20Image',
				'status_id' => 1,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Top Level Facts',
				'slug' => 'top-level-facts',
				'description' => 'Top Level Facts',
				'banner' => NULL,
				'image' => 'http://via.placeholder.com/800x800?text=Test%20Image',
				'status_id' => 1,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Customer Communication',
				'slug' => 'customer-communication',
				'description' => 'Customer Communication',
				'banner' => NULL,
				'image' => 'http://via.placeholder.com/800x800?text=Test%20Image',
				'status_id' => 1,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Customer Reference List',
				'slug' => 'customer-reference-list',
				'description' => 'Customer Reference List',
				'banner' => NULL,
				'image' => 'http://via.placeholder.com/800x800?text=Test%20Image',
				'status_id' => 1,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Ask an Expert',
				'slug' => 'ask-an-expert',
				'description' => 'Ask an Expert',
				'banner' => NULL,
				'image' => 'http://via.placeholder.com/800x800?text=Test%20Image',
				'status_id' => 1,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Approvals',
				'slug' => 'approvals',
				'description' => 'Approvals',
				'banner' => NULL,
				'image' => 'http://via.placeholder.com/800x800?text=Test%20Image',
				'status_id' => 1,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Manuals',
				'slug' => 'manuals',
				'description' => 'Manuals',
				'banner' => NULL,
				'image' => 'http://via.placeholder.com/800x800?text=Test%20Image',
				'status_id' => 1,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Sales Case Studies',
				'slug' => 'sales-case-studies',
				'description' => 'Sales Case Studies',
				'banner' => NULL,
				'image' => 'http://via.placeholder.com/800x800?text=Test%20Image',
				'status_id' => 1,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Exhibitions',
				'slug' => 'exhibitions',
				'description' => 'Exhibitions',
				'banner' => NULL,
				'image' => 'http://via.placeholder.com/800x800?text=Test%20Image',
				'status_id' => 1,
				'created_at' => $now,
				'updated_at' => $now,
			],
		];
		
		DB::table('content_types')->delete();
		
		DB::table('content_types')->insert($contentTypes);
	}
}
