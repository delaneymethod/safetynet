<?php

namespace App\Providers;

use Storage;
use League\Flysystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use DelaneyMethod\Sharepoint\Client as SharepointClient;
use DelaneyMethod\FlysystemSharepoint\SharepointAdapter;

class SharepointServiceProvider extends ServiceProvider
{
	/**
	 * Perform post-registration booting of services.
	 *
	 * @return void
	 */
	public function boot()
	{
		Storage::extend('sharepoint', function ($app, $config) {
			$client = new SharepointClient($config['site_name'], $config['site_url'], $config['url'], $config['client_id'], $config['client_secret'], $config['verify'], $config['access_token']);
			
			$adapter = new SharepointAdapter($client);
			
			return new Filesystem($adapter, ['url' => $config['url']]);
		});
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
