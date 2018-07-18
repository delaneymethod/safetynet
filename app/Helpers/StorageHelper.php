<?php 
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Helpers;

use Spatie\MediaLibrary\Media;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\PathGenerator\PathGenerator;

class StorageHelper implements PathGenerator
{
	private $root;
	
	private $disk;

	protected $mediaLibraryPath;
	
	protected $mediaLibraryCoversions;
	
	/**
	 * Create a new instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->disk = config('filesystems.default');
		
		$this->mediaLibraryPath = 'supporting-files';
		
		$this->mediaLibraryCoversions = 'conversions';
		
		$this->root = config('filesystems.disks.'.$this->disk.'.root');
		
		$this->setDisk($this->disk);
	}
	
	public function getDiskRootPath() : string
	{
		return $this->root;
	}
	
	public function getDisk() : string
	{
		return $this->disk;
	}
	
	public function setDisk(string $disk)
	{
		Storage::disk($disk);
	}
	
	public function getPublicUrl() : string
	{
		return Storage::getDriver()->getConfig()->get('url');
	}
	
	public function exists(string $path) : string 
	{
		$path = $this->cleanPath($path);
		
		return Storage::exists($path);
	}
	
	public function getMediaLibraryPath() : string
	{
		return $this->mediaLibraryPath;
	}
	
	public function getPath(Media $media) : string
	{
		return $this->mediaLibraryPath.DIRECTORY_SEPARATOR.$media->id.DIRECTORY_SEPARATOR;
	}
	
	public function getPathForConversions(Media $media) : string
	{
		return $this->getPath($media).$this->mediaLibraryCoversions.DIRECTORY_SEPARATOR;
	}
	
	public function getFile(string $path) : string
	{
		$path = $this->cleanPath($path);

		return Storage::get($path);
	}
	
	public function getFiles(string $path) : array
	{
		$path = $this->cleanPath($path);
		
		return Storage::files($path);
	}
	
	public function getAllFiles(string $path) : array
	{
		$path = $this->cleanPath($path);
		
		return Storage::allFiles($path);
	}
	
	public function uploadFile(string $path, $file, string $filename, string $visibility) : bool
	{
		$path = $this->cleanPath($path);
		
		return Storage::putFileAs($path, $file, $filename, $visibility);
	}
	
	public function moveFile(string $oldPath, string $newPath) : bool
	{
		$oldPath = $this->cleanPath($oldPath);
		
		$newPath = $this->cleanPath($newPath);
		
		return Storage::move($oldPath, $newPath);
	}
	
	public function deleteFile(string $path) : bool
	{
		$path = $this->cleanPath($path);
		
		return Storage::delete($path);
	}
	
	public function getSize(string $path) : string 
	{
		$path = $this->cleanPath($path);
		
		return Storage::size($path);
	}
	
	public function getUrl(string $path) : string 
	{
		$path = $this->cleanPath($path);
		
		return Storage::url($path);
	}
	
	public function getTemporaryUrl(string $path) : string
	{
		$path = $this->cleanPath($path);
		
		return Storage::temporaryUrl($path, now()->addMinutes(4320)); // 72 hours
	}
	
	public function getLastModified(string $path) : string 
	{
		$path = $this->cleanPath($path);
		
		return Storage::lastModified($path);
	}
	
	public function getFolders(string $path) : array
	{
		$path = $this->cleanPath($path);
		
		return Storage::directories($path);
	}
	
	public function getAllFolders(string $path) : array
	{
		$path = $this->cleanPath($path);
		
		return Storage::allDirectories($path);
	}
	
	public function createFolder(string $path) : bool
	{
		$path = $this->cleanPath($path);
		
		return Storage::makeDirectory($path);	
	}
	
	public function deleteFolder(string $path) : bool
	{
		$path = $this->cleanPath($path);
		
		return Storage::deleteDirectory($path);
	}
	
	public function getVisibility(string $path) : string
	{
		$path = $this->cleanPath($path);
		
		return Storage::getVisibility($path);
	}
	
	/**
	 * Strip off any leading or trailing slashes
	 */
	public function cleanPath(string $path) : string
	{
		return trim($path, DIRECTORY_SEPARATOR);
	}
}
