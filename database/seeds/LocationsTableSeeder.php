<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class LocationsTableSeeder extends Seeder
{
	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		$now = Carbon::now()->format('Y-m-d H:i:s');
		
		$locations = [
			[
				'title' => 'Survitec Group - Head Office',
				'unit' => '1-5',
				'building' => NULL,
				'street_address_1' => 'Beaufort Road',
				'street_address_2' => NULL,
				'street_address_3' => NULL,
				'street_address_4' => NULL,
				'town_city' => 'Birkenhead',
				'postal_code' => 'CH41 1HQ',
				'telephone' => '+44 (0) 151 670 9009',
				'fax' => '+44 (0) 151 670 0958',
				'email' => 'info@survitecgroup.com',
				'county_id' => 102,
				'country_id' => 5,
				'status_id' => 1,
				'created_at' => $now,
				'updated_at' => $now,
			],
		];
		
		DB::table('locations')->delete();
		
		DB::table('locations')->insert($locations);
	}
}
