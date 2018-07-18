<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

use Illuminate\Database\Seeder;

class CategoryExpertTableSeeder extends Seeder
{
	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		$categoryExperts = [
			[
				'category_id' => 1,
				'expert_id' => 1,
			],
		];
		
		DB::table('category_expert')->delete();
		
		DB::table('category_expert')->insert($categoryExperts);
	}
}
