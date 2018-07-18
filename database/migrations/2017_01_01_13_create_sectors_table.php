<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSectorsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::enableForeignKeyConstraints();

		Schema::create('sectors', function (Blueprint $table) {
			$table->engine = 'InnoDB ROW_FORMAT=DYNAMIC';

			$table->increments('id');
			
			$table->string('title')->index();
			$table->string('slug')->index();
			
			$table->longText('description');
			
			$table->string('banner')->nullable();
			$table->string('image')->nullable();
			$table->string('yammer')->nullable();
			$table->string('stream')->nullable();
			$table->string('colour')->nullable();
			
			$table->unsignedInteger('status_id')->nullable()->index()->comment('Foreign key to the statuses table');
			$table->unsignedInteger('order')->nullable();
			
			$table->timestamp('created_at')->useCurrent();
			$table->timestamp('updated_at')->useCurrent();
		});

		Schema::table('sectors', function (Blueprint $table) {
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
		Schema::dropIfExists('sectors');
	}
}
