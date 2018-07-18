<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class GlobalsTableSeeder extends Seeder
{
	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		$now = Carbon::now()->format('Y-m-d H:i:s');
		
		$globals = [
			[
				'title' => 'Site Name',
				'handle' => 'site_name',
				'data' => 'SafetyNet',
				'image' => '',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Site Logo',
				'handle' => 'site_logo',
				'data' => '/assets/img/safetynet-logo.png',
				'image' => '',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Site Tagline',
				'handle' => 'site_tagline',
				'data' => 'Providing critical business support',
				'image' => '',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Google Analytics',
				'handle' => 'google_analytics',
				'data' => 'XX-XXX-XXX',
				'image' => '',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Shop - Giveaway',
				'handle' => 'shop_giveaway',
				'data' => 'http://www.ease-e-order.com/survitec/login',
				'image' => '',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Shop - brochure_stationery',
				'handle' => 'shop_brochure_stationery',
				'data' => 'https://www.gelatoglobe.com/app/en/designs',
				'image' => '',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'title' => 'Sales Force',
				'handle' => 'sales_force',
				'data' => 'https://login.salesforce.com/?locale=uk',
				'image' => '',
				'created_at' => $now,
				'updated_at' => $now,
			],
		];
		
		DB::table('globals')->delete();
		
		DB::table('globals')->insert($globals);
	}
}
