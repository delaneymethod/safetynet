<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Http\Traits;

use App\Models\SupportingFile;
use Illuminate\Database\Eloquent\Collection as CollectionResponse;

trait SupportingFileTrait
{	
	/**
	 * Get the specified supporting file based on id.
	 *
	 * @param 	int 		$id
	 * @return 	Object
	 */
	public function getSupportingFile(int $id)
	{
		return SupportingFile::findOrFail($id);
	}
	
	/**
	 * Get the specified supporting file based on filename.
	 *
	 * @param 	int 		$id
	 * @return 	Object
	 */
	public function getSupportingFileByFileName(string $filename)
	{
		return SupportingFile::where('file_name', $filename)->first();
	}
	
	/**
	 * Get all the assets.
	 *
	 * @return 	Response
	 */
	public function getSupportingFiles() : CollectionResponse
	{
		return SupportingFile::all();
	}
}
