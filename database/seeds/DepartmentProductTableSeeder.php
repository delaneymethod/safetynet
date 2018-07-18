<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

use Illuminate\Database\Seeder;

class DepartmentProductTableSeeder extends Seeder
{
	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		$departmentProducts = [
			[
				'department_id' => 1,
				'product_id' => 1,
			],
			[
				'department_id' => 1,
				'product_id' => 2,
			],
			[
				'department_id' => 1,
				'product_id' => 3,
			],
			[
				'department_id' => 2,
				'product_id' => 4,
			],
			[
				'department_id' => 1,
				'product_id' => 5,
			],
			[
				'department_id' => 1,
				'product_id' => 6,
			],
			[
				'department_id' => 2,
				'product_id' => 7,
			],		
		];
		
		DB::table('department_product')->delete();
		
		DB::table('department_product')->insert($departmentProducts);
	}
}
