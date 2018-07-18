<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		$now = Carbon::now()->format('Y-m-d H:i:s');
		
		$products = [
			[
				'title' => 'Two Man Inflatable Boats - Image 1',
				'slug' => 'two-man-inflatable-boats-image-1',
				'description' => 'Two Man Inflatable Boats - Image 1',
				'banner' => NULL,
				'image' => 'http://via.placeholder.com/800x800?text=Test%20Image',
				'video' => NULL,
				'overview' => NULL,
				'due_date' => NULL,
				'npd_feedback_recipient' => NULL,
				'ex_feedback_recipient' => NULL,
				'minimum_number_of_units' => NULL,
				'status_id' => 1,
				'created_at' => $now,
				'updated_at' => $now,
			],
		];
		
		DB::table('products')->delete();
		
		DB::table('products')->insert($products);
	}
}
