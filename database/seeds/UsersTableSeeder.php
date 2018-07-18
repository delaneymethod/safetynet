<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		$now = Carbon::now()->format('Y-m-d H:i:s');
		
		$users = [
			[
				'first_name' => 'Sean',
				'last_name' => 'Delaney',
				'email' => 'hello@delaneymethod.com',
				'telephone' => '12345678',
				'password' => bcrypt('12345678'),
				'image' => NULL,
				'skype' => NULL,
				'job_title' => NULL,
				'bio' => NULL,
				'status_id' => 1,
				'role_id' => 1,
				'location_id' => 1,
				'remember_token' => NULL,
				'last_login_at' => NULL,
				'created_at' => $now,
				'updated_at' => $now,
			],
		];
			
		DB::table('users')->delete();
		
		DB::table('users')->insert($users);
	}
}
