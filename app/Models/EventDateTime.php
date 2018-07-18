<?php
/**
 * @link	  https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license	  https://www.delaneymethod.com/cms/license
 */

namespace App\Models;

use App\Models\Event;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventDateTime extends Model
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'event_date_time';
	
	protected $characterSet = 'UTF-8';
	
	protected $flags = ENT_QUOTES;
	
	
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'id',
		'event_id' ,
		'start',
		'end',
	];
	
	/**
	 * Get the sectors records associated with the event.
	 */
	public function event() : BelongsTo
	{
		return $this->belongsTo(Event::class);
	}
}
