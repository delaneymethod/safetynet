<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::enableForeignKeyConstraints();

		Schema::create('assets', function (Blueprint $table) {
			$table->engine = 'InnoDB ROW_FORMAT=DYNAMIC';
			
			$table->increments('id');
			
            $table->string('name');
            $table->string('file_name');
            $table->string('mime_type', 128);
            $table->string('focus_point')->nullable();
            $table->string('disk');
            $table->string('extension');
            $table->string('path');
            
            $table->longText('url');
            
            $table->unsignedInteger('size');
			$table->unsignedInteger('width')->nullable();
			$table->unsignedInteger('height')->nullable();
					
			$table->timestamp('created_at')->useCurrent();
			$table->timestamp('updated_at')->useCurrent();
		});

		Schema::table('assets', function (Blueprint $table) {
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('assets');
	}
}
