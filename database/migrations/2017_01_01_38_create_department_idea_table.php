<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepartmentIdeaTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::enableForeignKeyConstraints();
		 
		Schema::create('department_idea', function (Blueprint $table) {
			$table->engine = 'InnoDB ROW_FORMAT=DYNAMIC';
			
			$table->unsignedInteger('department_id')->comment('Foreign key to the departments table');
			$table->unsignedInteger('idea_id')->comment('Foreign key to the ideas table');
		});
		
		Schema::table('department_idea', function (Blueprint $table) {
			$table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
			$table->foreign('idea_id')->references('id')->on('ideas')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('department_idea');
	}
}
