<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Http\Traits;

use App\Models\Asset;
use Illuminate\Database\Eloquent\Collection as CollectionResponse;

trait AssetTrait
{
	/**
	 * Get the specified asset based on id.
	 *
	 * @param 	int 		$id
	 * @return 	Object
	 */
	public function getAsset(int $id)
	{
		return Asset::findOrFail($id);
	}
	
	/**
	 * Get the specified asset based on file name.
	 *
	 * @param 	int 		$id
	 * @return 	Object
	 */
	public function getAssetByFileName(string $filename)
	{
		return Asset::where('file_name', $filename)->first();
	}
	
	/**
	 * Get the specified asset based on file name and disk.
	 *
	 * @param 	string 		$filename
	 * @param 	string 		$disk
	 * @return 	Object
	 */
	public function getAssetByFileNameAndDisk(string $filename, string $disk)
	{
		return Asset::where('file_name', $filename)->where('disk', $disk)->first();
	}
	
	/**
	 * Get the specified asset based on file name, disk and Path.
	 *
	 * @param 	string 		$filename
	 * @param 	string 		$disk
	 * @param 	string 		$path
	 * @return 	Object
	 */
	public function getAssetByFileNameDiskAndPath(string $filename, string $disk, string $path)
	{
		return Asset::where('file_name', $filename)->where('disk', $disk)->where('path', $path)->first();
	}
	
	/**
	 * Get all the assets.
	 *
	 * @return 	Response
	 */
	public function getAssets() : CollectionResponse
	{
		return Asset::all();
	}
}
