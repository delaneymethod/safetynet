<?php

namespace App\Helpers;

use StorageHelper;
use DateTimeInterface;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Contracts\Config\Repository as Config;
use Spatie\MediaLibrary\UrlGenerator\BaseUrlGenerator;

class MediaLibraryUrlGenerator extends BaseUrlGenerator
{
	/** @var \Illuminate\Filesystem\FilesystemManager */
    protected $filesystemManager;

	public function __construct(Config $config, FilesystemManager $filesystemManager)
	{
		$this->filesystemManager = $filesystemManager;
	
		parent::__construct($config);
	}
    
	public function getUrl() : string
	{
		$disk = StorageHelper::getDisk();

		return config('medialibrary.'.$disk.'.domain').DIRECTORY_SEPARATOR.$this->getPathRelativeToRoot();
	}

	public function getPath() : string
	{
		return $this->getPathRelativeToRoot();
	}
	
	 /**
     * Get the temporary url for the profile of a media item.
     *
     * @param \DateTimeInterface $expiration
     * @param array $options
     *
     * @return string
     */
	public function getTemporaryUrl(DateTimeInterface $expiration, array $options = []): string
	{
		return $this->filesystemManager->disk($this->media->disk)->temporaryUrl($this->getPath(), $expiration, $options);
	}
}
