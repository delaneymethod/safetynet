<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Http\Traits;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection as CollectionResponse;

trait ProductTrait
{
	/**
	 * Get the specified product based on id.
	 *
	 * @param 	int 		$id
	 * @return 	Object
	 */
	public function getProduct(int $id) : Product
	{
		return Product::findOrFail($id);
	}
	
	/**
	 * Get all the products.
	 *
	 * @return 	Response
	 */
	public function getProducts() : CollectionResponse
	{
		return Product::all();
	}
	
	/**
	 * Get the product record by their slug.
	 */
	public function getProductBySlug(string $slug) : Product
	{
		return Product::where('slug', $slug)->where('status_id', 1)->firstOrFail();
	}
}
