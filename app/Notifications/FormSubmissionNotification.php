<?php
/**
 * @link	  https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license	  https://www.delaneymethod.com/cms/license
 */

namespace App\Notifications;

use App\User;
use Illuminate\Bus\Queueable;
use App\Http\Traits\GlobalTrait;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class FormSubmissionNotification extends Notification implements ShouldQueue
{
	use Queueable, GlobalTrait;
	
	/**
	 * Information about the site name.
	 *
	 * @var string
	 */
	protected $siteName;
	
	/**
	 * Information about the site logo.
	 *
	 * @var string
	 */
	protected $siteLogo;
	
	/**
	 * Information about the form data.
	 *
	 * @var array
	 */
	protected $formData;
	
	/**
	 * Information about the $recipient.
	 *
	 * @var string
	 */
	protected $recipient;
	
	/**
	 * Information about the $currentUser.
	 *
	 * @var string
	 */
	protected $currentUser;
	
	/**
	 * Create a new notification instance.
	 *
	 * @return void
	 */
	public function __construct(string $recipient, array $formData, User $currentUser)
	{
		$this->formData = $formData;
		
		$this->recipient = $recipient;
		
		$this->currentUser = $currentUser;
		
		$this->siteName = $this->getGlobal(1)->data;
		
		$this->siteLogo = $this->getGlobal(2)->data;
	}

	/**
	 * Get the notification's delivery channels.
	 *
	 * @param  mixed  $notifiable
	 * @return array
	 */
	public function via($notifiable) : array
	{
		return ['mail'];
	}

	/**
	 * Get the mail representation of the notification.
	 *
	 * @param  mixed  $notifiable
	 * @return \Illuminate\Notifications\Messages\MailMessage
	 */
	public function toMail($notifiable)
	{
		$data = [
			'formData' => $this->formData,
			'siteName' => $this->siteName,
			'recipient' => $this->recipient,
			'siteLogo' => url($this->siteLogo),
			'currentUserEmail' => $this->currentUser->email,
			'currentUserTelephone' => $this->currentUser->telephone,
			'currentUserFullName' => $this->currentUser->first_name.' '.$this->currentUser->last_name,
			'currentUserLocation' => $this->currentUser->location->title.', '.$this->currentUser->location->postal_address,
		];
		
		return (new MailMessage)->view('emails.email.formSubmission', $data)->subject($this->siteName.': '.$this->formData['form']);
	}

	/**
	 * Get the array representation of the notification.
	 *
	 * @param  mixed  $notifiable
	 * @return array
	 */
	public function toArray($notifiable) : array
	{
		return [];
	}
}
