<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

use Illuminate\Database\Seeder;

class EventSectorTableSeeder extends Seeder
{
	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		$eventSectors = [
			[
				'event_id' => 1,
				'sector_id' => 1,
			],
			[
				'event_id' => 2,
				'sector_id' => 1,
			],
		];
		
		DB::table('event_sector')->delete();
		
		DB::table('event_sector')->insert($eventSectors);
	}
}
