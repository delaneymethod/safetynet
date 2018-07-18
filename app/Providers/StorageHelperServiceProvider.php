<?php 
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */
 	
namespace App\Providers;

use App\Helpers\StorageHelper;
use Illuminate\Support\ServiceProvider;

class StorageHelperServiceProvider extends ServiceProvider 
{
	public function register()
	{
		$this->app->singleton('helpers.storage', function ($app) {
			return new StorageHelper($app);
		});
	}
}
