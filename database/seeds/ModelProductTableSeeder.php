<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

use Illuminate\Database\Seeder;

class ModelProductTableSeeder extends Seeder
{
	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		$modelProducts = [
			[
				'model_id' => 1,
				'product_id' => 7,
			],
			[
				'model_id' => 2,
				'product_id' => 7,
			],
		];
		
		DB::table('model_product')->delete();
		
		DB::table('model_product')->insert($modelProducts);
	}
}
