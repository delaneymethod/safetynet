<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

use Illuminate\Database\Seeder;
use App\Http\Traits\{CategoryTrait, ContentTypeTrait};

class CategoryContentTypeTableSeeder extends Seeder
{
	use CategoryTrait, ContentTypeTrait;
	
	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('category_content_type')->delete();
		
		$categories = $this->getCategories();
		
		$contentTypes = $this->getContentTypes();
		
		$categories->each(function ($category) use ($contentTypes) {
			// We only want to seed Source data
			if ($category->sectors->pluck('id')->contains(1)) {
				$contentTypes->each(function ($contentType) use ($category) {
					$categoryContentType = [
						'category_id' => $category->id,
						'content_type_id' => $contentType->id,
					];
			
					DB::table('category_content_type')->insert($categoryContentType);
				});
			}
		});
	}
}
