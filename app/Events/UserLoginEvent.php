<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Events;

use App\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserLoginEvent implements ShouldBroadcast
{
	use Dispatchable, InteractsWithSockets, SerializesModels;
	
	/**
	 * Information about the user update.
	 *
	 * @var string
	 */
	public $user;
	
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
	public function __construct(User $user)
	{
		$this->user = $user;
	}
	
	/**
	 * The event's broadcast name.
	 *
	 * @return string
	 */
	public function broadcastAs() : string
	{
		return 'user.login';
	}
	
	/**
	 * Get the data to broadcast.
	 *
	 * @return array
	 */
	public function broadcastWith() : array
	{
		return [
			'user' => $this->user,
		];
	}

	/**
	 * Get the channels the event should broadcast on.
	 *
	 * @return \Illuminate\Broadcasting\Channel|array
	 */
	public function broadcastOn()
	{
		return new PrivateChannel('users.'.$this->user->id);
	}
}
