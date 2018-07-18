<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Listeners;

use Carbon\Carbon;
use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserLoginListener implements ShouldQueue
{	
	/**
	 * Create the event listener.
	 *
	 * @return void
	 */
	public function __construct()
	{
	}

	/**
	 * Handle the event.
	 *
	 * @param 	UserLoginEvent 	$event
	 * @return	void
	 */
	public function handle(Login $event)
	{
		$event->user->last_login_at = Carbon::now();
		
		$event->user->save();
	}
}
