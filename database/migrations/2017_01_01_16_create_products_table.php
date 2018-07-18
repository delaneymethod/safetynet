<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::enableForeignKeyConstraints();

		Schema::create('products', function (Blueprint $table) {
			$table->engine = 'InnoDB ROW_FORMAT=DYNAMIC';

			$table->increments('id');
			
			$table->string('title')->index();
			$table->string('slug')->index();
			
			$table->longText('description');
			
			$table->string('banner')->nullable();
			$table->string('image')->nullable();
			$table->string('video')->nullable();
			$table->string('npd_feedback_recipient')->nullable()->comment('New Product Development related field');
			$table->string('ex_feedback_recipient')->nullable()->comment('Existing Products related field');
			
			$table->longText('overview')->nullable()->comment('New Product Development related field');
			$table->longText('due_date')->nullable()->comment('New Product Development related field');
			
			$table->unsignedInteger('minimum_number_of_units')->nullable()->comment('Existing Products - Action - Request a Modification - Limit at the Product level');
			
			$table->unsignedInteger('status_id')->nullable()->index()->comment('Foreign key to the statuses table');

			$table->timestamp('created_at')->useCurrent();
			$table->timestamp('updated_at')->useCurrent();
		});

		Schema::table('products', function (Blueprint $table) {
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
		Schema::dropIfExists('products');
	}
}
