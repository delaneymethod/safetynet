<?php
/**
 * @link	  https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license	  https://www.delaneymethod.com/cms/license
 */

namespace App\Http\Middleware;

use Closure;
use StorageHelper;

class AuthenticateWithAccessToken
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  Request			$request
	 * @param  Closure			$next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		$disk = StorageHelper::getDisk();
		
		// Sharepoint REST API requires an access token
		if ($disk === 'sharepoint') {
			// If no access tokens are found, redirect to Sharepoint login
			$accessToken = session()->get('oauth.sharepoint.access_token');
			
			if (!empty($accessToken)) {
				$expiresAt = (int) session()->get('oauth.sharepoint.expires_at');
				
				// Has the access token expired? It only has 1 hour life time
				if (!empty($expiresAt) && time() > $expiresAt) {
					// More than 1 hour ago so get the refresh token to get a new access token.
					return redirect('/login/sharepoint/refresh');
				}
			} else {
				// Access token value was empty so start over.
				return redirect('/login/sharepoint');
			}
		}		
		
		return $next($request);
	}
}
