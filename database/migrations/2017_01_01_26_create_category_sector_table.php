<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategorySectorTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::enableForeignKeyConstraints();
		 
		Schema::create('category_sector', function (Blueprint $table) {
			$table->engine = 'InnoDB ROW_FORMAT=DYNAMIC';
			
			$table->unsignedInteger('category_id')->comment('Foreign key to the categories table');
			$table->unsignedInteger('sector_id')->comment('Foreign key to the sectors table');
		});
		
		Schema::table('category_sector', function (Blueprint $table) {
			$table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
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
		Schema::dropIfExists('category_sector');
	}
}
