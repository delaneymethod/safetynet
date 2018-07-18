<?php
/**
 * @link	  https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license	  https://www.delaneymethod.com/cms/license
 */

namespace App\Http\Controllers\Auth;

use DB;
use Log;
use App\Models\EmailLogin;
use Illuminate\Http\Request;
use App\Events\UserLoginEvent;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Notifications\EmailLoginNotification;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Http\Traits\{UserTrait, EmailLoginTrait, PasswordResetTrait};

class EmailLoginController extends Controller
{
	use UserTrait, EmailLoginTrait, PasswordResetTrait, AuthenticatesUsers;

	protected $redirectTo = '/';

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest');
	}
	
	public function showLoginForm(Request $request)
	{
		return view('auth.email.login');
	}
	
	/**
	 * Override login method in \Illuminate\Foundation\Auth\AuthenticatesUsers.php
	 *
	 * Handle a login by email request to the application.
	 *
	 * @param  Request	 $request
	 * @return mixed
	 */
	public function login(Request $request)
	{
		 // Remove any Cross-site scripting (XSS)
		$cleanedEmailLogin = $this->sanitizerInput($request->all());
		
		$rules = $this->getRules('email_login');
			
		// Make sure all the input data is what we actually save
		$validator = $this->validatorInput($cleanedEmailLogin, $rules);
		
		if ($validator->fails()) {
			return back()->withErrors($validator)->withInput();
		}
		
		// Create a token
		DB::beginTransaction();

		try {	
			// Create new model
			$emailLogin = new EmailLogin;
	
			// Set our field data
			$emailLogin->email = $cleanedEmailLogin['email'];
			$emailLogin->token = str_random(20);
				
			$emailLogin->save();
		} catch (QueryException $queryException) {
			DB::rollback();
		
			Log::info('SQL: '.$queryException->getSql());

			Log::info('Bindings: '.implode(', ', $queryException->getBindings()));

			abort(500, $queryException);
		} catch (Exception $exception) {
			DB::rollback();

			abort(500, $exception);
		}

		DB::commit();
		
		// Now get the user by email - we can do this safetly because the validation check above tells us that the email address exists!
		$user = $this->getUserByEmail($cleanedEmailLogin['email']);
	
		$user->notify(new EmailLoginNotification($emailLogin->token, $user->first_name));
			
		flash('Login email sent. Go check your email!', $level = 'success');
		
		return redirect($this->redirectTo);
	}
	
	/**
     * Attempt to log the user into the application.
     *
     * @param 	Request 	$request
     * @param 	string 		$token
     */
	public function authenticate(Request $request, string $token) : RedirectResponse
	{
		$request->session()->regenerate();

        $this->clearLoginAttempts($request);
        
		$emailLogin = $this->getEmailLoginByToken($token);
		
		Auth::login($emailLogin->user);
		
		return $this->authenticated($request, $this->guard()->user()) ?: redirect()->intended($this->redirectTo);
    }
	
	/**
	 * Override register method in \Illuminate\Foundation\Auth\AuthenticatesUsers.php
	 *
	 * Handles an authenticated by email request to the application.
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
		UserLoginEvent::dispatch($user);
		
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
}
