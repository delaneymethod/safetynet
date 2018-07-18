<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Events\UserLoginEvent;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\{Auth, Session};
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Http\Traits\{EmailLoginTrait, PasswordResetTrait};

class LoginController extends Controller
{
	use EmailLoginTrait, PasswordResetTrait, AuthenticatesUsers;

	protected $redirectTo = '/';

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest')->except('logout');
	}
	
	/**
	 * Override register method in \Illuminate\Foundation\Auth\AuthenticatesUsers.php
	 *
	 * Handles an authenticated request to the application.
	 *
	 * @param	Request 	$request
	 * @param 	mixed 		$user
	 * @return 	mixed
	 */
	protected function authenticated(Request $request, $user) : RedirectResponse
	{
		// Check if the user status is in active
		if ($user->status->id === 2) {
			Auth::logout();

			flash('Your account is in active! Please contact your account owner.', $level = 'warning');

			return back();
		}
		
		// If the user has logged in successfully, and they have password reset requests pending, just remove them.
		$passwordResets = $this->getPasswordReset($user->email);

		if (count($passwordResets) > 0) {
			$this->deletePasswordReset($user->email);
		}
		
		// If the user has logged in successfully, and they have email login requests pending, just remove them.
		$emailLogins = $this->getEmailLogin($user->email);

		if (count($emailLogins) > 0) {
			$this->deleteEmailLoginByEmail($user->email);
		}
		
		// Used to update the frontend / backend UI's
		// UserLoginEvent::dispatch($user);
		
		// If we are redirecting user back to previous page, then we set the new route here
		$redirectTo = $request->get('redirectTo');
		
		if (!empty($redirectTo)) {
			$this->redirectTo = $redirectTo;
			
			$code = $request->get('code');
		
			if (!empty($code)) {
				$this->redirectTo = $this->redirectTo.'#'.$code;
			}
		}
		
		return redirect($this->redirectTo);
	}
	
	/**
	 * Override logout method in \Illuminate\Foundation\Auth\AuthenticatesUsers.php
	 *
	 * Handles an logout request to the application.
	 *
	 * @param	Request 	$request
	 * @return 	mixed
	 */
	public function logout(Request $request) : RedirectResponse
	{
		// Now logout and clear out all the exisiting sessions
		Auth::logout();
		
		Session::flush();
		
		Session::regenerate(true);
		
		flash('You have been logged out!', $level = 'success');
        
		return redirect($this->redirectTo);
	}
}
