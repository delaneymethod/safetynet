<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryContentTypeTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::enableForeignKeyConstraints();
		 
		Schema::create('category_content_type', function (Blueprint $table) {
			$table->engine = 'InnoDB ROW_FORMAT=DYNAMIC';
			
			$table->unsignedInteger('category_id')->comment('Foreign key to the categories table');
			$table->unsignedInteger('content_type_id')->comment('Foreign key to the content types table');
		});
		
		Schema::table('category_content_type', function (Blueprint $table) {
			$table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
			$table->foreign('content_type_id')->references('id')->on('content_types')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('category_content_type');
	}
}
