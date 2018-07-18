<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

use Illuminate\Database\Seeder;

class CategoryProductTableSeeder extends Seeder
{
	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		$categoryProducts = [
			[
				'category_id' => 1,
				'product_id' => 1,
			],
			[
				'category_id' => 1,
				'product_id' => 2,
			],
			[
				'category_id' => 1,
				'product_id' => 3,
			],
			[
				'category_id' => 10,
				'product_id' => 7,
			],
		];
		
		DB::table('category_product')->insert($categoryProducts);
	}
}
