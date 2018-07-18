<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductSectorTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::enableForeignKeyConstraints();
		 
		Schema::create('product_sector', function (Blueprint $table) {
			$table->engine = 'InnoDB ROW_FORMAT=DYNAMIC';
			
			$table->unsignedInteger('product_id')->comment('Foreign key to the products table');
			$table->unsignedInteger('sector_id')->comment('Foreign key to the sectors table');
		});
		
		Schema::table('product_sector', function (Blueprint $table) {
			$table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
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
		Schema::dropIfExists('product_sector');
	}
}
