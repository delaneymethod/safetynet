<?php
				
return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'uploads'),

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => env('FILESYSTEM_CLOUD', 's3'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "s3", "rackspace"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],
        
        // Added by Sean
        'uploads' => [
			'driver' => 'local',
			'root' => base_path().'/'.config('cms.app_path_public').'/uploads',
			'url' => config('app.url').'/uploads',
		],
		
        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => config('app.url').'/storage',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'visibility' => 'private',
        ],
        
        // Added by Sean
        'spaces' => [
			'driver' => 's3',
			'key' => env('DO_SPACES_KEY'),
			'secret' => env('DO_SPACES_SECRET'),
			'endpoint' => env('DO_SPACES_ENDPOINT'),
			'region' => env('DO_SPACES_REGION'),
			'bucket' => env('DO_SPACES_BUCKET'),
			'url' => env('DO_SPACES_URL'),
			'visibility' => 'private',
		],
		
		// Added by Sean
        'sharepoint' => [
			'driver' => 'sharepoint',
			'client_id' => env('SHAREPOINT_KEY'),
			'verify' => env('SHAREPOINT_SSL_VERIFY'),
			'site_url' => env('SHAREPOINT_SITE_URL'),
			'site_name' => env('SHAREPOINT_SITE_NAME'),
			'client_secret' => env('SHAREPOINT_SECRET'),
			'redirect_uri' => env('SHAREPOINT_REDIRECT_URI'),
			'url' => env('SHAREPOINT_PUBLIC_URL'),
			'access_token' => env('SHAREPOINT_ACCESS_TOKEN'),
		],
		
    ],

];
