<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Providers;

use App;
use stdClass;
use Exception;
use StorageHelper;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\{Auth, View};
use App\Http\Traits\{AssetTrait, GlobalTrait};

class ComposerServiceProvider extends ServiceProvider
{
	use AssetTrait, GlobalTrait;
	
	/**
	 * Register bindings in the container.
	 *
	 * @return void
	 */
	public function boot()
	{
		if (!App::runningInConsole()) {
			View::composer('*', function ($view) {
				try {
					$authenticated = Auth::check();
				
					$view->with([
						'authenticated' => $authenticated,
					]);
					
					$globals = $this->getGlobals();
					
					if ($globals->count() > 0) {
						$globals->each(function ($global) {
							View::share(camel_case($global->handle), $global->data);
							
							if ($global->handle === 'shop_-_giveaways' || $global->handle === 'shop_-_brochures_and_stationery') {
								$global = $this->imageToAsset($global);
								
								View::share(camel_case($global->handle.'Data'), $global->data);
								View::share(camel_case($global->handle.'Image'), $global->image->url);
							}
						});
					}
					
					View::share('sidebarSmCols', config('cms.column_widths.cp.sidebar.sm'));
					View::share('sidebarMdCols', config('cms.column_widths.cp.sidebar.md'));
					View::share('sidebarLgCols', config('cms.column_widths.cp.sidebar.lg'));
					View::share('sidebarXlCols', config('cms.column_widths.cp.sidebar.xl'));
					
					View::share('mainSmCols', config('cms.column_widths.cp.main.sm'));
					View::share('mainMdCols', config('cms.column_widths.cp.main.md'));
					View::share('mainLgCols', config('cms.column_widths.cp.main.lg'));
					View::share('mainXlCols', config('cms.column_widths.cp.main.xl'));
				} catch (Exception $exception) {
					exit('<h2>500 Internal Server Error</h2><p>Something went wrong on our servers while we were processing your request.</p><p>We&#39;re really sorry about this, and will work hard to get this resolved as soon as possible.</p><h3>Exception</h3><small><pre>'.$exception->getMessage().'</pre></small>');
				}
			});
		}
	}
	
	/**
	 * Maps an image to an asset modal object if the image exists in the assets table.
	 */
	private function imageToAsset($model)
	{
		$disk = StorageHelper::getDisk();
		
		$path = explode(DIRECTORY_SEPARATOR, $model->image);
		
		$path = array_reverse($path);
		
		$fileName = $path[0];
		
		if ($model->image) {
			$asset = $this->getAssetByFileNameDiskAndPath($fileName, $disk, $model->image);
		} else {
			$asset = $this->getAssetByFileNameAndDisk($fileName, $disk);
		}
		
		if (!empty($asset)) {
			$asset->focus_point = json_decode($asset->focus_point);
			
			$model->image = $asset;
		} else {
			$modelAsset = new stdClass;
			
			if (empty($model->image)) {
				$model->image = '/assets/img/placeholder.png';	
			}
			
			$modelAsset->url = $model->image;
			$modelAsset->path = $model->image;
			
			$model->image = $modelAsset;
		}
		
		return $model;
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
	}
}
