<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Http\Traits;

use Carbon\Carbon;
use App\Models\EmailLogin;

trait EmailLoginTrait
{
	/**
	 * Get the specified email login record based on email.
	 *
	 * @param 	string 		$email
	 * @return 	EmailLogin
	 */
	public function getEmailLogin(string $email)
	{
		return EmailLogin::where('email', $email)->first();
	}
	
	/**
	 * Get the specified email login record based on token.
	 *
	 * @param 	string 		$token
	 * @return 	EmailLogin
	 */
	public function getEmailLoginByToken(string $token)
	{
		$emailLogin = EmailLogin::where('token', $token)->where('created_at', '>', Carbon::parse('-15 minutes'))->first();
		
		if (!$emailLogin) {
			$this->deleteEmailLoginByToken($token);
			
			abort(410, 'There might be a typing error in the web address, or if you clicked on a link, it may be out of date/expired.');
		}
		
		return $emailLogin;
	}
	
	/**
	 * Delete the email login record by the token.
	 *
	 * @param 	string 		$token
	 * @return 	Object
	 */
	public function deleteEmailLoginByToken(string $token)
	{
		return EmailLogin::where('token', $token)->delete();
	}
	
	/**
	 * Delete the email login record by the email.
	 *
	 * @param 	string 		$email
	 * @return 	Object
	 */
	public function deleteEmailLoginByEmail(string $email)
	{
		return EmailLogin::where('email', $email)->delete();
	}
}
