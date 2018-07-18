<?php
/**
 * @link	  https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license	  https://www.delaneymethod.com/cms/license
 */

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{	
	/**
	 * The application's global HTTP middleware stack.
	 *
	 * These middleware are run during every request to your application.
	 *
	 * @var array
	 */
	protected $middleware = [
		\Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
		\Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
		\App\Http\Middleware\TrimStrings::class,
		\Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
		\App\Http\Middleware\TrustProxies::class,
		
		// By Sean
		// Removed by Sean since this converts & to &amp; in all URLs
		// \RenatoMarinho\LaravelPageSpeed\Middleware\InlineCss::class,
		\RenatoMarinho\LaravelPageSpeed\Middleware\ElideAttributes::class,
		\RenatoMarinho\LaravelPageSpeed\Middleware\InsertDNSPrefetch::class,
		\RenatoMarinho\LaravelPageSpeed\Middleware\RemoveComments::class,
		// Removed by Sean since this rmeoves http and https from all URLs
		// \RenatoMarinho\LaravelPageSpeed\Middleware\TrimUrls::class,
		\RenatoMarinho\LaravelPageSpeed\Middleware\RemoveQuotes::class,
		\RenatoMarinho\LaravelPageSpeed\Middleware\CollapseWhitespace::class,
	];

	/**
	 * The application's route middleware groups.
	 *
	 * @var array
	 */
	protected $middlewareGroups = [
		'web' => [
			\App\Http\Middleware\EncryptCookies::class,
			\Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
			\Illuminate\Session\Middleware\StartSession::class,
			// \Illuminate\Session\Middleware\AuthenticateSession::class,
			\Illuminate\View\Middleware\ShareErrorsFromSession::class,
			\App\Http\Middleware\VerifyCsrfToken::class,
			\Illuminate\Routing\Middleware\SubstituteBindings::class,
		
			// Added By Sean
			\App\Http\Middleware\LogoutIfInactive::class,
		],
		
		'api' => [
			'throttle:500,1', // allow 500 requests per minute
			'bindings',
		],
	];

	/**
	 * The application's route middleware.
	 *
	 * These middleware may be assigned to groups or used individually.
	 *
	 * @var array
	 */
	protected $routeMiddleware = [
		'auth' => \App\Http\Middleware\Authenticate::class,
		'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
		'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
		'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
		'can' => \Illuminate\Auth\Middleware\Authorize::class,
		'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
		'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
		'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
		// Added By Sean
		'auth.accessToken' => \App\Http\Middleware\AuthenticateWithAccessToken::class,
	];
}
