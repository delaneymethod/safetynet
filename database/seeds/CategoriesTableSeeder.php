<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		$now = Carbon::now()->format('Y-m-d H:i:s');
		
		$categories = [
			[
				'title' => 'Walls',
				'slug' => 'walls',
				'description' => 'Walls',
				'banner' => NULL,
				'image' => 'http://via.placeholder.com/800x800?text=Test%20Image',
				'status_id' => 1,
				'order' => NULL,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'TCCC',
				'slug' => 'tccc',
				'description' => 'TCCC',
				'banner' => NULL,
				'image' => 'http://via.placeholder.com/800x800?text=Test%20Image',
				'status_id' => 1,
				'order' => NULL,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Liferafts',
				'slug' => 'liferafts',
				'description' => 'Liferafts',
				'banner' => NULL,
				'image' => 'http://via.placeholder.com/800x800?text=Test%20Image',
				'status_id' => 1,
				'order' => NULL,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Life Jackets',
				'slug' => 'life-jackets',
				'description' => 'Life Jackets',
				'banner' => NULL,
				'image' => 'http://via.placeholder.com/800x800?text=Test%20Image',
				'status_id' => 1,
				'order' => NULL,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Boats',
				'slug' => 'boats',
				'description' => 'Boats',
				'banner' => NULL,
				'image' => 'http://via.placeholder.com/800x800?text=Test%20Image',
				'status_id' => 1,
				'order' => NULL,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Services',
				'slug' => 'services',
				'description' => 'Services',
				'banner' => NULL,
				'image' => 'http://via.placeholder.com/800x800?text=Test%20Image',
				'status_id' => 1,
				'order' => NULL,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Sonics',
				'slug' => 'sonics',
				'description' => 'Sonics',
				'banner' => NULL,
				'image' => 'http://via.placeholder.com/800x800?text=Test%20Image',
				'status_id' => 1,
				'order' => NULL,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'SEIE',
				'slug' => 'seie',
				'description' => 'SEIE',
				'banner' => NULL,
				'image' => 'http://via.placeholder.com/800x800?text=Test%20Image',
				'status_id' => 1,
				'order' => NULL,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'PFE',
				'slug' => 'pfe',
				'description' => 'PFE',
				'banner' => NULL,
				'image' => 'http://via.placeholder.com/800x800?text=Test%20Image',
				'status_id' => 1,
				'order' => NULL,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Aviation Liferafts',
				'slug' => 'aviation-liferafts',
				'description' => 'Aviation Liferafts',
				'banner' => NULL,
				'image' => 'http://via.placeholder.com/800x800?text=Test%20Image',
				'status_id' => 1,
				'order' => NULL,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Commercial Aviation Lifejackets',
				'slug' => 'commercial-aviation-lifejackets',
				'description' => 'Commercial Aviation Lifejackets',
				'banner' => NULL,
				'image' => 'http://via.placeholder.com/800x800?text=Test%20Image',
				'status_id' => 1,
				'order' => NULL,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Military Boats',
				'slug' => 'military-boats',
				'description' => 'Military Boats',
				'banner' => NULL,
				'image' => 'http://via.placeholder.com/800x800?text=Test%20Image',
				'status_id' => 1,
				'order' => NULL,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Military Lifejackets',
				'slug' => 'military-lifejackets',
				'description' => 'Military Lifejackets',
				'banner' => NULL,
				'image' => 'http://via.placeholder.com/800x800?text=Test%20Image',
				'status_id' => 1,
				'order' => NULL,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Pilot Flight Equipment',
				'slug' => 'pilot-flight-equipment',
				'description' => 'Pilot Flight Equipment',
				'banner' => NULL,
				'image' => 'http://via.placeholder.com/800x800?text=Test%20Image',
				'status_id' => 1,
				'order' => NULL,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Military Liferafts',
				'slug' => 'military-liferafts',
				'description' => 'Military Liferafts',
				'banner' => NULL,
				'image' => 'http://via.placeholder.com/800x800?text=Test%20Image',
				'status_id' => 1,
				'order' => NULL,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Aviation Lifejackets',
				'slug' => 'aviation-lifejackets',
				'description' => 'Aviation Lifejackets',
				'banner' => NULL,
				'image' => 'http://via.placeholder.com/800x800?text=Test%20Image',
				'status_id' => 1,
				'order' => NULL,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Aviation Immersion & Survival Suits',
				'slug' => 'aviation-immersion-and-survival-suits',
				'description' => 'Aviation Immersion & Survival Suits',
				'banner' => NULL,
				'image' => 'http://via.placeholder.com/800x800?text=Test%20Image',
				'status_id' => 1,
				'order' => NULL,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Pre-Hospital Medical Equipment',
				'slug' => 'pre-hospital-medical-equipment',
				'description' => 'Pre-Hospital Medical Equipment',
				'banner' => NULL,
				'image' => 'http://via.placeholder.com/800x800?text=Test%20Image',
				'status_id' => 1,
				'order' => NULL,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Inflatable Walls',
				'slug' => 'inflatable-walls',
				'description' => 'Inflatable Walls',
				'banner' => NULL,
				'image' => 'http://via.placeholder.com/800x800?text=Test%20Image',
				'status_id' => 1,
				'order' => NULL,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Submarine Escape Equipment',
				'slug' => 'submarine-escape-equipment',
				'description' => 'Submarine Escape Equipment',
				'banner' => NULL,
				'image' => 'http://via.placeholder.com/800x800?text=Test%20Image',
				'status_id' => 1,
				'order' => NULL,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Defence Immersion & Survival Suits',
				'slug' => 'defence-immersion-and-survival-suits',
				'description' => 'Defence Immersion & Survival Suits',
				'banner' => NULL,
				'image' => 'http://via.placeholder.com/800x800?text=Test%20Image',
				'status_id' => 1,
				'order' => NULL,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'OnTarget',
				'slug' => 'ontarget',
				'description' => 'OnTarget',
				'banner' => NULL,
				'image' => 'http://via.placeholder.com/800x800?text=Test%20Image',
				'status_id' => 1,
				'order' => NULL,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'OnBoard',
				'slug' => 'onboard',
				'description' => 'OnBoard',
				'banner' => NULL,
				'image' => 'http://via.placeholder.com/800x800?text=Test%20Image',
				'status_id' => 1,
				'order' => NULL,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Offshore Wind Update',
				'slug' => 'offshore-wind-update',
				'description' => 'Offshore Wind Update',
				'banner' => NULL,
				'image' => 'http://via.placeholder.com/800x800?text=Test%20Image',
				'status_id' => 1,
				'order' => NULL,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'New Horizons',
				'slug' => 'new-horizons',
				'description' => 'New Horizons',
				'banner' => NULL,
				'image' => 'http://via.placeholder.com/800x800?text=Test%20Image',
				'status_id' => 1,
				'order' => NULL,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'View our Strategy',
				'slug' => 'view-our-strategy',
				'description' => 'View our Strategy',
				'banner' => NULL,
				'image' => 'http://via.placeholder.com/800x800?text=Test%20Image',
				'status_id' => 1,
				'order' => NULL,
				'created_at' => $now,
				'updated_at' => $now,
			],
		];
		
		DB::table('categories')->delete();
		
		DB::table('categories')->insert($categories);
	}
}
