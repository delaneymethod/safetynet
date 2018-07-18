<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Http\Traits;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection as CollectionResponse;

trait CategoryTrait
{
	/**
	 * Get the specified category based on id.
	 *
	 * @param 	int 		$id
	 * @return 	Object
	 */
	public function getCategory(int $id) : Category
	{
		return Category::findOrFail($id);
	}
	
	/**
	 * Get the specified category based on id.
	 *
	 * @param 	int 		$id
	 * @return 	Object
	 */
	public function getCategoryById(int $id)
	{
		return Category::find($id);
	}
	
	/**
	 * Get all the categories.
	 *
	 * @return 	Response
	 */
	public function getCategories() : CollectionResponse
	{
		return Category::all();
	}
	
	/**
	 * Get all the categories ordered.
	 */
	public function getCategoriesByOrder() : CollectionResponse
	{
		return Category::ordered()->get();
	}
	
	/**
	 * Get the category record by their slug.
	 */
	public function getCategoryBySlug(string $slug) : Category
	{
		return Category::where('slug', $slug)->where('status_id', 1)->firstOrFail();
	}
}
