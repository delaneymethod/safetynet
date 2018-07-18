<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSessionsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::enableForeignKeyConstraints();
		 
		Schema::create('sessions', function (Blueprint $table) {
			$table->engine = 'InnoDB ROW_FORMAT=DYNAMIC';
			
			$table->string('id')->unique();
			
			$table->unsignedInteger('user_id')->nullable();
			
			$table->string('ip_address', 45)->nullable();
			
			$table->text('user_agent')->nullable();
			$table->text('payload');
			
			$table->integer('last_activity');
		});
		
		Schema::table('sessions', function (Blueprint $table) {
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('sessions');
	}
}
