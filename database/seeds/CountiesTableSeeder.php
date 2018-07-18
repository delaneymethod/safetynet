<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CountiesTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$now = Carbon::now()->format('Y-m-d H:i:s');

		$counties = [
			[
				'country_id' => 1,
				'title' => 'Carlow',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 1,
				'title' => 'Cavan',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 1,
				'title' => 'Clare',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 1,
				'title' => 'Cork',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 1,
				'title' => 'Donegal',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 1,
				'title' => 'Dublin',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 1,
				'title' => 'Galway',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 1,
				'title' => 'Kerry',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 1,
				'title' => 'Kildare',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 1,
				'title' => 'Kilkenny',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 1,
				'title' => 'Laois',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 1,
				'title' => 'Leitrim',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 1,
				'title' => 'Limerick',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 1,
				'title' => 'Longford',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 1,
				'title' => 'Louth',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 1,
				'title' => 'Mayo',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 1,
				'title' => 'Meath',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 1,
				'title' => 'Monaghan',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 1,
				'title' => 'Offaly',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 1,
				'title' => 'Roscommon',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 1,
				'title' => 'Sligo',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 1,
				'title' => 'Tipperary',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 1,
				'title' => 'Waterford',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 1,
				'title' => 'Westmeath',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 1,
				'title' => 'Wexford',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 1,
				'title' => 'Wicklow',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 2,
				'title' => 'Antrim',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 2,
				'title' => 'Armagh',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 2,
				'title' => 'Down',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 2,
				'title' => 'Fermanagh',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 2,
				'title' => 'Londonderry',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 2,
				'title' => 'Tyrone',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 3,
				'title' => 'Kirkcudbrightshire',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 3,
				'title' => 'Lanarkshire',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 3,
				'title' => 'Midlothian',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 3,
				'title' => 'Moray',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 3,
				'title' => 'Nairnshire',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 3,
				'title' => 'Orkney',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 3,
				'title' => 'Peebleshire',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 3,
				'title' => 'Perthshire',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 3,
				'title' => 'Renfrewshire',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 3,
				'title' => 'Ross & Cromarty',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 3,
				'title' => 'Roxburghshire',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 3,
				'title' => 'Selkirkshire',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 3,
				'title' => 'Shetland',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 3,
				'title' => 'Stirlingshire',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 3,
				'title' => 'Sutherland',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 3,
				'title' => 'West Lothian',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 3,
				'title' => 'Wigtownshire',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 3,
				'title' => 'Aberdeen City',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 3,
				'title' => 'Aberdeenshire',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 3,
				'title' => 'Angus',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 3,
				'title' => 'Argyll',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 3,
				'title' => 'Ayrshire',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 3,
				'title' => 'Banffshire',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 3,
				'title' => 'Berwickshire',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 3,
				'title' => 'Bute',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 3,
				'title' => 'Caithness',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 3,
				'title' => 'Clackmannanshire',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 3,
				'title' => 'Dumfriesshire',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 3,
				'title' => 'Dumbartonshire',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 3,
				'title' => 'East Lothian',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 3,
				'title' => 'Fife',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 3,
				'title' => 'Inverness',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 3,
				'title' => 'Kincardineshire',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 3,
				'title' => 'Kinross-shire',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 4,
				'title' => 'Flintshire',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 4,
				'title' => 'Glamorgan',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 4,
				'title' => 'Merionethshire',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 4,
				'title' => 'Monmouthshire',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 4,
				'title' => 'Montgomeryshire',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 4,
				'title' => 'Pembrokeshire',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 4,
				'title' => 'Radnorshire',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 4,
				'title' => 'Anglesey',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 4,
				'title' => 'Breconshire',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 4,
				'title' => 'Caernarvonshire',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 4,
				'title' => 'Cardiganshire',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 4,
				'title' => 'Carmarthenshire',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 4,
				'title' => 'Denbighshire',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 5,
				'title' => 'London',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 5,
				'title' => 'Bedfordshire',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 5,
				'title' => 'Buckinghamshire',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 5,
				'title' => 'Cambridgeshire',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 5,
				'title' => 'Cheshire',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 5,
				'title' => 'Cornwall and Isles of Scilly',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 5,
				'title' => 'Cumbria',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 5,
				'title' => 'Derbyshire',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 5,
				'title' => 'Devon',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 5,
				'title' => 'Dorset',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 5,
				'title' => 'Durham',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 5,
				'title' => 'East Sussex',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 5,
				'title' => 'Essex',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 5,
				'title' => 'Gloucestershire',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 5,
				'title' => 'Greater London',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 5,
				'title' => 'Greater Manchester',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 5,
				'title' => 'Hampshire',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 5,
				'title' => 'Hertfordshire',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 5,
				'title' => 'Kent',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 5,
				'title' => 'Lancashire',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 5,
				'title' => 'Leicestershire',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 5,
				'title' => 'Lincolnshire',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 5,
				'title' => 'Merseyside',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 5,
				'title' => 'Norfolk',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 5,
				'title' => 'North Yorkshire',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 5,
				'title' => 'Northamptonshire',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 5,
				'title' => 'Northumberland',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 5,
				'title' => 'Nottinghamshire',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 5,
				'title' => 'Oxfordshire',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 5,
				'title' => 'Shropshire',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 5,
				'title' => 'Somerset',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 5,
				'title' => 'South Yorkshire',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 5,
				'title' => 'Staffordshire',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 5,
				'title' => 'Suffolk',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 5,
				'title' => 'Surrey',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 5,
				'title' => 'Tyne and Wear',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 5,
				'title' => 'Warwickshire',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 5,
				'title' => 'West Midlands',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 5,
				'title' => 'West Sussex',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 5,
				'title' => 'West Yorkshire',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'country_id' => 5,
				'title' => 'Wiltshire',
				'created_at' => $now,
				'updated_at' => $now,
			],
		];

		$counties = $this->_sortByCountryThenByCounty($counties, 'country_id', SORT_ASC, 'title', SORT_ASC);

		DB::table('counties')->delete();

		DB::table('counties')->insert($counties);
	}

	private function _sortByCountryThenByCounty()
	{
		$args = func_get_args();

		$data = array_shift($args);

		foreach ($args as $n => $field) {
			if (is_string($field)) {
				$tmp = array();

				foreach ($data as $key => $row) {
					$tmp[$key] = $row[$field];

					$args[$n] = $tmp;
				}
			}
		}

		$args[] = &$data;

		call_user_func_array('array_multisort', $args);

		return array_pop($args);
	}
}
