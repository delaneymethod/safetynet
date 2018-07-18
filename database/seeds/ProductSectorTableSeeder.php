<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

use Illuminate\Database\Seeder;

class ProductSectorTableSeeder extends Seeder
{
	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		$productSectors = [
			[
				'product_id' => 1,
				'sector_id' => 1,
			],
			[
				'product_id' => 2,
				'sector_id' => 1,
			],
			[
				'product_id' => 3,
				'sector_id' => 1,
			],
			[
				'product_id' => 4,
				'sector_id' => 5,
			],
			[
				'product_id' => 5,
				'sector_id' => 1,
			],
			[
				'product_id' => 6,
				'sector_id' => 1,
			],
			[
				'product_id' => 7,
				'sector_id' => 6,
			],		
		];
		
		DB::table('product_sector')->delete();
		
		DB::table('product_sector')->insert($productSectors);
	}
}
