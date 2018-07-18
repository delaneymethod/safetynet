<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

use Illuminate\Database\Seeder;

class DepartmentSectorTableSeeder extends Seeder
{
	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		$departmentSectors = [
			[
				'department_id' => 1,
				'sector_id' => 1,
			],
			[
				'department_id' => 1,
				'sector_id' => 2,
			],
			[
				'department_id' => 1,
				'sector_id' => 3,
			],
			[
				'department_id' => 1,
				'sector_id' => 4,
			],
			[
				'department_id' => 2,
				'sector_id' => 5,
			],
			[
				'department_id' => 2,
				'sector_id' => 6,
			],
			[
				'department_id' => 2,
				'sector_id' => 7,
			],
			[
				'department_id' => 2,
				'sector_id' => 8,
			],
		];
		
		DB::table('department_sector')->delete();
		
		DB::table('department_sector')->insert($departmentSectors);
	}
}
