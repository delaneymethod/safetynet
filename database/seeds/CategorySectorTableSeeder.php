<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

use Illuminate\Database\Seeder;

class CategorySectorTableSeeder extends Seeder
{
	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		$categorySectors = [
			[
				'category_id' => 1,
				'sector_id' => 1,
			],
			[
				'category_id' => 2,
				'sector_id' => 1,
			],
			[
				'category_id' => 3,
				'sector_id' => 1,
			],
			[
				'category_id' => 4,
				'sector_id' => 1,
			],
			[
				'category_id' => 5,
				'sector_id' => 1,
			],
			[
				'category_id' => 6,
				'sector_id' => 1,
			],
			[
				'category_id' => 7,
				'sector_id' => 1,
			],
			[
				'category_id' => 8,
				'sector_id' => 1,
			],
			[
				'category_id' => 9,
				'sector_id' => 1,
			],
			[
				'category_id' => 1,
				'sector_id' => 2,
			],
			[
				'category_id' => 2,
				'sector_id' => 2,
			],
			[
				'category_id' => 3,
				'sector_id' => 2,
			],
			[
				'category_id' => 4,
				'sector_id' => 2,
			],
			[
				'category_id' => 5,
				'sector_id' => 2,
			],
			[
				'category_id' => 6,
				'sector_id' => 2,
			],
			[
				'category_id' => 7,
				'sector_id' => 2,
			],
			[
				'category_id' => 8,
				'sector_id' => 2,
			],
			[
				'category_id' => 9,
				'sector_id' => 2,
			],
			[
				'category_id' => 1,
				'sector_id' => 3,
			],
			[
				'category_id' => 2,
				'sector_id' => 3,
			],
			[
				'category_id' => 3,
				'sector_id' => 3,
			],
			[
				'category_id' => 4,
				'sector_id' => 3,
			],
			[
				'category_id' => 5,
				'sector_id' => 3,
			],
			[
				'category_id' => 6,
				'sector_id' => 3,
			],
			[
				'category_id' => 7,
				'sector_id' => 3,
			],
			[
				'category_id' => 8,
				'sector_id' => 3,
			],
			[
				'category_id' => 9,
				'sector_id' => 3,
			],
			[
				'category_id' => 1,
				'sector_id' => 4,
			],
			[
				'category_id' => 2,
				'sector_id' => 4,
			],
			[
				'category_id' => 3,
				'sector_id' => 4,
			],
			[
				'category_id' => 4,
				'sector_id' => 4,
			],
			[
				'category_id' => 5,
				'sector_id' => 4,
			],
			[
				'category_id' => 6,
				'sector_id' => 4,
			],
			[
				'category_id' => 7,
				'sector_id' => 4,
			],
			[
				'category_id' => 8,
				'sector_id' => 4,
			],
			[
				'category_id' => 9,
				'sector_id' => 4,
			],
			[
				'category_id' => 10,
				'sector_id' => 6,
			],
			[
				'category_id' => 11,
				'sector_id' => 6,
			],
			[
				'category_id' => 12,
				'sector_id' => 6,
			],
			[
				'category_id' => 13,
				'sector_id' => 6,
			],
			[
				'category_id' => 14,
				'sector_id' => 6,
			],
			[
				'category_id' => 15,
				'sector_id' => 6,
			],
			[
				'category_id' => 16,
				'sector_id' => 6,
			],
			[
				'category_id' => 17,
				'sector_id' => 6,
			],
			[
				'category_id' => 18,
				'sector_id' => 6,
			],
			[
				'category_id' => 19,
				'sector_id' => 6,
			],
			[
				'category_id' => 20,
				'sector_id' => 6,
			],
			[
				'category_id' => 21,
				'sector_id' => 6,
			],
			[
				'category_id' => 24,
				'sector_id' => 3,
			],
			[
				'category_id' => 24,
				'sector_id' => 2,
			],
			[
				'category_id' => 25,
				'sector_id' => 1,
			],
			[
				'category_id' => 26,
				'sector_id' => 4,
			],
			[
				'category_id' => 28,
				'sector_id' => 1,
			],
		];
		
		DB::table('category_sector')->delete();
		
		DB::table('category_sector')->insert($categorySectors);
	}
}
