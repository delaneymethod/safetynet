<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 *
	 * @return void
	 */
	public function run()
	{
		ini_set('memory_limit', '1048M');
		
		DB::disableQueryLog();
		
		$this->call(PermissionGroupsTableSeeder::class);
		$this->call(PermissionsTableSeeder::class);
		$this->call(StatusesTableSeeder::class);
		$this->call(RolesTableSeeder::class);
		$this->call(CountriesTableSeeder::class);
		$this->call(CountiesTableSeeder::class);
		$this->call(LocationsTableSeeder::class);
		$this->call(UsersTableSeeder::class);
		$this->call(AssetsTableSeeder::class);
		$this->call(RolePermissionTableSeeder::class);
		$this->call(GlobalsTableSeeder::class);
		$this->call(DepartmentsTableSeeder::class);
		$this->call(SectorsTableSeeder::class);
		$this->call(CategoriesTableSeeder::class);
		$this->call(ContentTypesTableSeeder::class);
		$this->call(ProductsTableSeeder::class);
		$this->call(ModelsTableSeeder::class);
		$this->call(ExpertsTableSeeder::class);
		$this->call(EventsTableSeeder::class);
		$this->call(EventDatesTimesTableSeeder::class);
		$this->call(SupportingFilesTableSeeder::class);
		$this->call(DepartmentSectorTableSeeder::class);
		$this->call(CategorySectorTableSeeder::class);
		$this->call(CategoryContentTypeTableSeeder::class);
		$this->call(CategoryProductTableSeeder::class);
		$this->call(DepartmentProductTableSeeder::class);
		$this->call(ProductSectorTableSeeder::class);
		$this->call(ContentTypeProductTableSeeder::class);
		$this->call(ModelProductTableSeeder::class);
		$this->call(CategoryExpertTableSeeder::class);
		$this->call(EventSectorTableSeeder::class);
		$this->call(RefreshTokensTableSeeder::class);
		$this->call(IdeasTableSeeder::class);
		$this->call(TeamMembersTableSeeder::class);
		
		DB::enableQueryLog();
	}
}
