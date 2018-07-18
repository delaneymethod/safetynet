<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventSectorTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::enableForeignKeyConstraints();

		Schema::create('event_sector', function (Blueprint $table) {
			$table->engine = 'InnoDB ROW_FORMAT=DYNAMIC';

			$table->unsignedInteger('event_id')->comment('Foreign key to the events table');
			$table->unsignedInteger('sector_id')->comment('Foreign key to the sectors table');
		});

		Schema::table('event_sector', function (Blueprint $table) {
			$table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
			$table->foreign('sector_id')->references('id')->on('sectors')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('event_sector');
	}
}
