<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */
 
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CountriesTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$now = Carbon::now()->format('Y-m-d H:i:s');

		$countries = [
			[
				'title' => 'Republic of Ireland',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Northern Ireland',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Scotland',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Wales',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'England',
				'created_at' => $now,
				'updated_at' => $now,
			],
		];

		DB::table('countries')->delete();

		DB::table('countries')->insert($countries);
	}
}
