<?php
/**
 * @link	  https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license	  https://www.delaneymethod.com/cms/license
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class LogoutIfInactive
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
		if (Auth::guard($guard)->check() && Auth::user()->isInactive()) {
			Auth::logout();
			
			flash('Your account is in active! Please contact your system administrator.', $level = 'warning');

			return redirect('/login');
		}
		
		return $next($request);
	}
}
