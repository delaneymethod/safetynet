<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupportingFilesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::enableForeignKeyConstraints();

		Schema::create('supporting_files', function (Blueprint $table) {
			$table->engine = 'InnoDB ROW_FORMAT=DYNAMIC';
			
			$table->increments('id');
			
			$table->morphs('model');
			
			$table->string('collection_name');
			$table->string('name');
			$table->string('file_name');
			$table->string('mime_type')->nullable();
			$table->string('disk');
			
			$table->unsignedInteger('size');
			
			$table->json('manipulations');
			$table->json('custom_properties');
			
			$table->unsignedInteger('order_column')->nullable();
			
			$table->timestamp('created_at')->useCurrent();
			$table->timestamp('updated_at')->useCurrent();
		});

		Schema::table('supporting_files', function (Blueprint $table) {
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('supporting_files');
	}
}
