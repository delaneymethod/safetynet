<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Events;

use App\User;
use Illuminate\Foundation\Events\Dispatchable;

class FormSubmissionEvent
{
	use Dispatchable;
	
	/**
	 * Information about the form data.
	 *
	 * @var object
	 */
	public $formData;
	
	/**
	 * Information about the current user.
	 *
	 * @var object
	 */
	public $currentUser;
	
	/**
	 * The name of the queue on which to place the event.
	 *
	 * @var string
	 */
	public $broadcastQueue = 'default';
	
	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct(array $formData, User $currentUser)
	{
		$this->formData = $formData;
		
		$this->currentUser = $currentUser;
	}
}
