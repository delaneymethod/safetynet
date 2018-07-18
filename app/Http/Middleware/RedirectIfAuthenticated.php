<?php
/**
 * @link	  https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license	  https://www.delaneymethod.com/cms/license
 */

namespace App\Http\Middleware;

use Route;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  Request			$request
	 * @param  Closure			$next
	 * @param  string|null		$guard
	 * @return mixed
	 */
	public function handle($request, Closure $next, $guard = null)
	{
		if (Auth::guard($guard)->check() && in_array(Route::current()->uri(), ['register', 'login', 'login/email'])) {
			$redirectTo = $request->get('redirectTo');
			
			if (!empty($redirectTo)) {
				$redirectTo = '?redirectTo='.$redirectTo;
			}
			
			return redirect('/cp/dashboard'.$redirectTo);
		}
		
		return $next($request);
	}
}
