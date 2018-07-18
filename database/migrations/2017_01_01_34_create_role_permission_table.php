<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolePermissionTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::enableForeignKeyConstraints();

		Schema::create('role_permission', function (Blueprint $table) {
			$table->engine = 'InnoDB ROW_FORMAT=DYNAMIC';

			$table->unsignedInteger('role_id')->comment('Foreign key to the roles table');
			$table->unsignedInteger('permission_id')->comment('Foreign key to the permissions table');
		});

		Schema::table('role_permission', function (Blueprint $table) {
			$table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
			$table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('role_permission');
	}
}
