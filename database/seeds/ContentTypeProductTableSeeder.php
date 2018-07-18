<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

use Illuminate\Database\Seeder;

class ContentTypeProductTableSeeder extends Seeder
{
	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		$contentTypeProducts = [
			[
				'content_type_id' => 2,
				'product_id' => 1,
			],
			[
				'content_type_id' => 2,
				'product_id' => 2,
			],
			[
				'content_type_id' => 3,
				'product_id' => 3,
			],
			[
				'content_type_id' => 1,
				'product_id' => 6,
			],
		];
		
		DB::table('content_type_product')->delete();
		
		DB::table('content_type_product')->insert($contentTypeProducts);
	}
}
