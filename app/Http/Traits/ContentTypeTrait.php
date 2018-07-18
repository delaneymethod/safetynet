<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Http\Traits;

use App\Models\ContentType;
use Illuminate\Database\Eloquent\Collection as CollectionResponse;

trait ContentTypeTrait
{
	/**
	 * Get the specified content type based on id.
	 *
	 * @param 	int 		$id
	 * @return 	Object
	 */
	public function getContentType(int $id) : ContentType
	{
		return ContentType::findOrFail($id);
	}
	
	/**
	 * Get all the content types.
	 *
	 * @return 	Response
	 */
	public function getContentTypes() : CollectionResponse
	{
		return ContentType::all();
	}
	
	/**
	 * Get the content type record by their slug.
	 */
	public function getContentTypeBySlug(string $slug) : ContentType
	{
		return ContentType::where('slug', $slug)->where('status_id', 1)->firstOrFail();
	}
}
