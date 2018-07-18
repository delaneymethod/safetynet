<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventDateTimeTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::enableForeignKeyConstraints();

		Schema::create('event_date_time', function (Blueprint $table) {
			$table->engine = 'InnoDB ROW_FORMAT=DYNAMIC';

			$table->increments('id');
			
			$table->unsignedInteger('event_id')->nullable()->index()->comment('Foreign key to the events table');
			
			$table->timestamp('start')->nullable()->index();
			$table->timestamp('end')->nullable()->index();
			
			$table->timestamp('created_at')->useCurrent();
			$table->timestamp('updated_at')->useCurrent();
		});

		Schema::table('event_date_time', function (Blueprint $table) {
			$table->foreign('event_id')->references('id')->on('events')->onUpdate('cascade');
			$table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('event_date_time');
	}
}
