<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamMembersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::enableForeignKeyConstraints();

		Schema::create('team_members', function (Blueprint $table) {
			$table->engine = 'InnoDB ROW_FORMAT=DYNAMIC';

			$table->increments('id');
			
			$table->string('full_name')->index();
			$table->string('email');
			$table->string('job_title');
			$table->string('image');
			
			$table->longText('bio');
			
			$table->unsignedInteger('location_id')->nullable()->index()->comment('Foreign key to the locations table');
			$table->unsignedInteger('status_id')->nullable()->index()->comment('Foreign key to the statuses table');

			$table->timestamp('created_at')->useCurrent();
			$table->timestamp('updated_at')->useCurrent();
		});

		Schema::table('team_members', function (Blueprint $table) {
			$table->foreign('location_id')->references('id')->on('locations')->onDelete('set null');
			$table->foreign('status_id')->references('id')->on('statuses')->onDelete('set null');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('team_members');
	}
}
