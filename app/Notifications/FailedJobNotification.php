<?php
/**
 * @link	  https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license	  https://www.delaneymethod.com/cms/license
 */

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use App\Http\Traits\GlobalTrait;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class FailedJobNotification extends Notification implements ShouldQueue
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
	 * Information about the job.
	 *
	 * @var string
	 */
	protected $job;
	
	/**
	 * Information about the subject.
	 *
	 * @var string
	 */
	protected $subject;
	
	/**
	 * Create a new notification instance.
	 *
	 * @return void
	 */
	public function __construct(string $job, string $subject = 'Failed Job Notification')
	{
		$this->job = $job;
		
		$global = $this->getGlobal(1);
		
		$this->siteName = $global->data;
		
		$global = $this->getGlobal(2);
		
		$this->siteLogo = $global->data;
		
		$this->subject = $subject;
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
			'job' => $this->job,
			'siteName' => $this->siteName,
			'siteLogo' => url($this->siteLogo),
		];
		
		return (new MailMessage)->view('emails.jobs.failed', $data)->subject($this->subject);
	}

	/**
	 * Get the array representation of the notification.
	 *
	 * @param  mixed  $notifiable
	 * @return array
	 */
	public function toArray($notifiable) : array
	{
		return [
			'job' => $this->job,
		];
	}
}
