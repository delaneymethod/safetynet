<?php
/**
 * @link	  https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license	  https://www.delaneymethod.com/cms/license
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Status, Sector, EventDateTime};
use MaddHatter\LaravelFullcalendar\IdentifiableEvent;
use Illuminate\Database\Eloquent\Relations\{HasMany, BelongsTo, BelongsToMany};

class Event extends Model implements IdentifiableEvent
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'events';
	
	protected $characterSet = 'UTF-8';
	
	protected $flags = ENT_QUOTES;
	
	protected $dates = [
		'start', 
		'end'
	];
	
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'id',
		'title' ,
		'slug',
		'description',
		'image',
		'all_day',
		'options',
		'organiser_name',
		'organiser_email',
		'organiser_contact_number',
		'status_id',
	];
	
	/**
	 * Get the status record associated with the event.
	 */
	public function status() : BelongsTo
	{
		return $this->belongsTo(Status::class);
	}
	
	/**
	 * Get the sectors records associated with the event.
	 */
	public function sectors() : BelongsToMany
	{
		return $this->belongsToMany(Sector::class);
	}
	
	/**
	 * Set sectors for the event.
	 *
	 * $param 	array 	$sectors
	 */
	public function setSectors(array $sectors)
	{
		return $this->sectors()->sync($sectors);
	}
	
	/**
	 * Get the dates times records associated with the event.
	 */
	public function datesTimes() : HasMany
    {
        return $this->hasMany(EventDateTime::class);
    }
	
	/**
	 * Get the event's id number
	 *
	 * @return int
	 */
	public function getId() : int
	{
		return $this->id;
	}

	/**
	 * Get the event's title
	 *
	 * @return string
	 */
	public function getTitle() : string
	{
		return html_entity_decode($this->title, ENT_QUOTES | ENT_HTML5, 'UTF-8');
	}

	/**
	 * Is it an all day event?
	 *
	 * @return bool
	 */
	public function isAllDay() : bool
	{
		return $this->all_day;
	}

	/**
	 * Get the start time
	 *
	 * @return DateTime
	 */
	public function getStart() : Carbon
	{
		return $this->start;
	}

	/**
	 * Get the end time
	 *
	 * @return DateTime
	 */
	public function getEnd() : Carbon
	{
		return $this->end;
	}
	
	/**
	 * Optional FullCalendar.io settings for this event
	 *
	 * @return array
	 */
	public function getEventOptions() : array
	{
		$options = json_decode($this->options, true);
		
		$colour = '#fe5001';
		
		if (!empty($this->sectors[0]->colour)) {
			$colour = $this->sectors[0]->colour;
		}
		
		return [
			'slug' => $this->slug,
			'image' => $this->image,
			'url' => $options['url'],
			'color' => $colour,
			'sectors' => implode(', ', $this->sectors->pluck('slug')->toArray()),
			'description' => html_entity_decode($this->description, ENT_QUOTES | ENT_HTML5, 'UTF-8'),
		];
	}
	
	/**
	 * Checks if event is active.
	 *
	 * @return bool
	 */
	public function isActive() : bool
	{
		return $this->status_id == 1;
	}
	
	/**
	 * Checks if event is in active.
	 *
	 * @return bool
	 */
	public function isInactive() : bool
	{
		return $this->status_id == 2;
	}
}
