<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */
 
namespace App\Console\Commands;

use Storage;
use Exception;
use Illuminate\Console\Command;
use App\Http\Traits\{AssetTrait, SupportingFileTrait};

class GetTemporaryUrls extends Command
{
	use AssetTrait, SupportingFileTrait;

	private $minutes;

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'storage:get-temporary-urls';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Uploaded files are stored on Digital Ocean Spaces which are private by default. This commands sets a temporary url for each file found in the Assets and Supporting Files tables so they can be viewed.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->minutes = 4320; // 72 hours
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$assets = $this->getAssets();

		foreach ($assets as $asset) {
			if ($asset->disk === 'spaces') {
				try {
					$url = Storage::temporaryUrl($asset->path, now()->addMinutes($this->minutes)); 

					$asset->url = $url;

					$asset->save();
				} catch (Exception $exception) {}
			}
		}
		
		$supportingFiles = $this->getSupportingFiles();

		foreach ($supportingFiles as $supportingFile) {
			if ($supportingFile->disk === 'spaces') {
				try {
					$path = 'supporting-files'.DIRECTORY_SEPARATOR.$supportingFile->id.DIRECTORY_SEPARATOR.$supportingFile->file_name;

					$url = Storage::temporaryUrl($path, now()->addMinutes($this->minutes));

					$customProperties = $supportingFile->custom_properties;

					$customProperties['url'] = $url;

					if (!empty($customProperties['thumbnail'])) {
						$thumbnail = $customProperties['thumbnail'];

						$thumbnailPath = 'supporting-files'.DIRECTORY_SEPARATOR.$supportingFile->id.DIRECTORY_SEPARATOR.$thumbnail['fileName'];

						$thumbnailUrl = Storage::temporaryUrl($thumbnailPath, now()->addMinutes($this->minutes));

						$thumbnail['url'] = $thumbnailUrl;

						$customProperties['thumbnail'] = $thumbnail;
					}
					
					$supportingFile->custom_properties = $customProperties;
				
					$supportingFile->save();
				} catch (Exception $exception) {}
			}
		}

		$this->info('Asset and Supporting File temporary urls set successfully.');
	}
}
