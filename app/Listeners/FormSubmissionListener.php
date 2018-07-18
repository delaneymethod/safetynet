<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Listeners;

use Notification;
use Carbon\Carbon;
use App\Events\FormSubmissionEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\FormSubmissionNotification;

class FormSubmissionListener implements ShouldQueue
{
	/**
	 * The name of the queue the job should be sent to.
	 *
	 * @var string|null
	 */
	public $queue = 'default';
	
	/**
     * The number of minutes the job is delayed.
     *
     * @var int
     */
    protected $minutes;
    
	/**
	 * Create the event listener.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->minutes = config('cms.delays.notifications');
	}

	/**
	 * Handle the event.
	 *
	 * @param 	OrderCreated 	$event
	 * @return 	void
	 */
	public function handle(FormSubmissionEvent $event)
	{
		$time = Carbon::now()->addMinutes($this->minutes);
		
		$formData = $event->formData;
		
		$currentUser = $event->currentUser;
		
		$recipients = [];
		
		switch($formData['form']) {
			case 'New Product Development' :
				array_push($recipients, $formData['npd_feedback_recipient']);
					
				break;
				
			case 'Existing Products' :
				array_push($recipients, $formData['ex_feedback_recipient']);
				
				break;
				
			case 'Acquisition Target' :
				array_push($recipients, config('cms.email.recipients.acquisition_target'));
					
				break;
			
			case 'Ask An Expert' :
				array_push($recipients, $formData['expert_email']);
				
				break;
				
			case 'Request A Post' :
				foreach ($formData['channels'] as $channel) {
					array_push($recipients, config('cms.email.recipients.'.$channel));
				}
				
				break;
				
			case 'Request An Event' :
				if ($formData['sector'] === 'Marine') {
					array_push($recipients, config('cms.email.recipients.marine'));
				}
				
				if ($formData['sector'] === 'Defence & Aerospace') {
					array_push($recipients, config('cms.email.recipients.defence_aerospace'));
				}
				
				if ($formData['sector'] === 'Wind Energy') {
					array_push($recipients, config('cms.email.recipients.wind_energy'));
				}
				
				if ($formData['sector'] === 'Corporate') {
					array_push($recipients, config('cms.email.recipients.corporate'));
				}
				
				break;		
		}
		
		$recipients = array_unique($recipients);
		
		foreach ($recipients as $recipient) {
			Notification::route('mail', $recipient)->notify((new FormSubmissionNotification($recipient, $formData, $currentUser))->delay($time)->onQueue('default'));
		}
    }
}
