<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Http\Controllers;

use DB;
use Log;
use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Event, EventDateTime};
use Illuminate\Database\QueryException;
use App\Http\Traits\{EventTrait, StatusTrait, SectorTrait, DepartmentTrait};

class EventController extends Controller
{
	use EventTrait, StatusTrait, SectorTrait, DepartmentTrait;
	
	private $allDayOptions = [];
	
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->middleware('auth');
		
		$this->middleware('auth.accessToken');
		
		$this->cacheKey = 'events';
		
		$this->allDayOptions = [[
			'title' => 'No', 
			'value' => 0,
		], [
			'title' => 'Yes',
			'value' => 1,
		]];
	}
	
	/**
	 * Get templates view.
	 *
	 * @params	Request 	$request
	 * @return 	Response
	 */
   	public function index(Request $request)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('view_events')) {
			$title = 'Events';
			
			$subTitle = '';
			
			$leadParagraph = 'Events belong to Sectors.';
			
			$events = $this->getCache($this->cacheKey);
			
			if (is_null($events)) {
				$events = $this->getEvents();
				
				$this->setCache($this->cacheKey, $events);
			}
			
			$this->mapImagesToAssets($events);
			
			$events = $events->each(function($model) {
				$model->title = $this->htmlEntityDecode($model->title);
				$model->description = $this->htmlEntityDecode($model->title);
			});
			
			return view('cp.events.index', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'events'));
		}
		
		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Shows a form for creating a new event.
	 *
	 * @params	Request 	$request
	 * @return 	Response
	 */
	public function create(Request $request)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('create_events')) {
			$title = 'Create Event';
		
			$subTitle = 'Events';
			
			$leadParagraph = 'Events belong to Sectors.';
			
			// Used to set status_id
			$statuses = $this->getData('getStatuses');
			
			// Used to set sector_id
			$sectors = $this->getData('getSectors', 'sectors');
			
			$allDayOptions = recursiveObject($this->allDayOptions);
			
			return view('cp.events.create', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'statuses', 'sectors', 'allDayOptions'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Creates a new event.
	 *
	 * @params Request 	$request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$currentUser = $this->getAuthenticatedUser();

		if ($currentUser->hasPermission('create_events')) {
			// Remove any Cross-site scripting (XSS)
			$cleanedEvent = $this->sanitizerInput($request->all());
			
			$rules = $this->getRules('event');
			
			// Make sure all the input data is what we actually save
			$validator = $this->validatorInput($cleanedEvent, $rules);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput();
			}

			DB::beginTransaction();
			
			try {
				// Create new event
				$event = new Event;
	
				// Set our field data
				$event->title = $cleanedEvent['title'];
				$event->slug = $cleanedEvent['slug'];
				$event->description = $cleanedEvent['description'];
				$event->image = $this->fixProtocol($cleanedEvent['image']);
				$event->all_day = $cleanedEvent['all_day'];
				
				/*
				// If its an all day event, we need to remove the time parts from the start and end values
				if ($event->all_day == 1) {
					$event->start = $this->parseDate($start, 'Y-m-d');
					$event->end = $this->parseDate($ends[$counter], 'Y-m-d');
				} else {
					$event->start = $this->parseDate($start, 'Y-m-d H:i:s');
					$event->end = $this->parseDate($ends[$counter], 'Y-m-d H:i:s');
				}
				*/
					
				if (!empty($cleanedEvent['url'])) {
					$event->options = json_encode([
						'url' => $this->fixProtocol($cleanedEvent['url'])
					]);
				} else {
					$event->options = null;
				}
				
				$event->organiser_name = $cleanedEvent['organiser_name'];
				$event->organiser_email = $cleanedEvent['organiser_email'];
				$event->organiser_contact_number = $cleanedEvent['organiser_contact_number'];
			
				$event->status_id = $cleanedEvent['status_id'];
				
				$event->save();
								
				$starts = $cleanedEvent['starts'];
					
				$ends = $cleanedEvent['ends'];
				
				$datesTimes = [];
				
				$counter = 0;
				
				foreach ($starts as $start) {
					$dateTime = [
						'start' => $this->parseDate($start, 'Y-m-d H:i:s'),
						'end' => $this->parseDate($ends[$counter], 'Y-m-d H:i:s'),
					];
					
					array_push($datesTimes, $dateTime);
					
					$counter++;
				}
				
				$event->datesTimes()->delete();
				
				$event->datesTimes()->createMany($datesTimes);
				
				if (!empty($cleanedEvent['sector_ids'])) {
					$event->setSectors($cleanedEvent['sector_ids']);
				}
				
				$this->setCache($this->cacheKey, $this->getEvents());
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

			flash('Event created successfully.', $level = 'success');

			return redirect('/cp/events');
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Shows a form for editing a event.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
	public function edit(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('edit_events')) {
			$title = 'Edit Event';
			
			$subTitle = 'Events';
			
			$leadParagraph = 'Events belong to Sectors.';
			
			$event = $this->getEvent($id);
			
			$event->title = $this->htmlEntityDecode($event->title);
			$event->description = $this->htmlEntityDecode($event->description);
			
			$this->mapImagesToAssets($event);
			
			// Used to set status_id
			$statuses = $this->getData('getStatuses');
			
			// Used to set sector_id
			$sectors = $this->getData('getSectors', 'sectors');

			$allDayOptions = recursiveObject($this->allDayOptions);
			
			return view('cp.events.edit', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'event', 'statuses', 'sectors', 'allDayOptions'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Updates a specific event.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
	public function update(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();

		if ($currentUser->hasPermission('edit_events')) {
			// Remove any Cross-site scripting (XSS)
			$cleanedEvent = $this->sanitizerInput($request->all());
			
			$rules = $this->getRules('event');
			
			// Make sure all the input data is what we actually save
			$validator = $this->validatorInput($cleanedEvent, $rules);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput();
			}

			DB::beginTransaction();

			try {
				// Get our event
				$event = $this->getEvent($id);
				
				// Set our field data
				$event->title = $cleanedEvent['title'];
				$event->slug = $cleanedEvent['slug'];
				$event->description = $cleanedEvent['description'];
				$event->image = $this->fixProtocol($cleanedEvent['image']);
				$event->all_day = $cleanedEvent['all_day'];
				
				/*
				// If its an all day event, we need to remove the time parts from the start and end values
				if ($event->all_day == 1) {
					$event->start = $this->parseDate($cleanedEvent['start'], 'Y-m-d');
					$event->end = $this->parseDate($cleanedEvent['end'], 'Y-m-d');
				} else {
					$event->start = $this->parseDate($cleanedEvent['start'], 'Y-m-d H:i:s');
					$event->end = $this->parseDate($cleanedEvent['end'], 'Y-m-d H:i:s');
				}
				*/
				
				if (!empty($cleanedEvent['url'])) {
					$event->options = json_encode([
						'url' => $this->fixProtocol($cleanedEvent['url'])
					]);
				} else {
					$event->options = null;
				}
				
				$event->organiser_name = $cleanedEvent['organiser_name'];
				$event->organiser_email = $cleanedEvent['organiser_email'];
				$event->organiser_contact_number = $cleanedEvent['organiser_contact_number'];
				
				$event->status_id = $cleanedEvent['status_id'];
				$event->updated_at = $this->datetime;
				
				$event->save();
				
				$starts = $cleanedEvent['starts'];
					
				$ends = $cleanedEvent['ends'];
				
				$datesTimes = [];
				
				$counter = 0;
				
				foreach ($starts as $start) {
					$dateTime = [
						'start' => $this->parseDate($start, 'Y-m-d H:i:s'),
						'end' => $this->parseDate($ends[$counter], 'Y-m-d H:i:s'),
					];
					
					array_push($datesTimes, $dateTime);
					
					$counter++;
				}
				
				$event->datesTimes()->delete();
				
				$event->datesTimes()->createMany($datesTimes);
				
				if (!empty($cleanedEvent['sector_ids'])) {
					$event->setSectors($cleanedEvent['sector_ids']);
				}
				
				$this->setCache($this->cacheKey, $this->getEvents());
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

			flash('Event updated successfully.', $level = 'success');

			return redirect('/cp/events');
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Shows a form for deleting a event.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
	public function confirm(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('delete_events')) {
			$event = $this->getEvent($id);
			
			$event->title = $this->htmlEntityDecode($event->title);
			
			$title = 'Delete Event';
			
			$subTitle = 'Events';
			
			$leadParagraph = 'Events belong to Sectors.';
			
			return view('cp.events.delete', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'event'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Deletes a specific event.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
	public function delete(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('delete_events')) {
			$event = $this->getEvent($id);
		
			DB::beginTransaction();

			try {
				$event->delete();
				
				$this->setCache($this->cacheKey, $this->getEvents());
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

			flash('Event deleted successfully.', $level = 'info');

			return redirect('/cp/events');
		}

		abort(403, 'Unauthorised action');
	}
	
	public function filter(Request $request, string $sectors = '')
	{
		$events = [];
		
		if (!empty($sectors)) {
			$sectors = explode(',', $sectors);
			
			$events = [];
			
			foreach ($sectors as $sector) {
				$sector = $this->getSectorBySlug($sector);
				
				// Remove inactive
				$sector->events = $sector->events->filter(function ($model) {
					return $model->isActive();
				});
				
				$calEvent = [];
			
				foreach ($sector->events as $event) {
					foreach ($event->datesTimes as $eventDateTime) {
						$eventDateTime->start = $eventDateTime->start;
						$eventDateTime->end = $eventDateTime->end;
						
						$options = json_decode($event->options, true);
		
						$colour = '#fe5001';
						
						if (!empty($event->sectors[0]->colour)) {
							$colour = $event->sectors[0]->colour;
						}
						
						$eventSectors = implode(', ', $event->sectors->pluck('slug')->toArray());
						
						$options = [
							'slug' => $event->slug,
							'image' => $event->image,
							'url' => $options['url'],
							'color' => $colour,
							'sectors' => $eventSectors,
							'description' => html_entity_decode($event->description, ENT_QUOTES | ENT_HTML5, 'UTF-8'),
						];
						
						$calendarEvent = [
							'id' => $eventDateTime->id,
							'_id' => $event->id,
							'title' => $this->htmlEntityDecode($event->title),
							'description' => $this->htmlEntityDecode($event->description),
							'start' => $eventDateTime->start,
							'end' => $eventDateTime->end,
							'sectors' => $eventSectors,
							'color' => $colour,
							'allDay' => $event->all_day,
							'all_day' => $event->all_day,
							'slug' => $event->slug,
							'image' => $event->image,
							'url' => $options['url'],
							'options' => $options,
						];
						
						$calEvent = array_merge($calendarEvent);
						
						// dd($calEvent);
						
						array_push($events, $calEvent);
					}
				}
			}
		} else {
			$sectors = $this->getSectors();
			
			// Remove inactive
			$sectors = $sectors->filter(function ($model) {
				return $model->isActive();
			});
			
			$events = [];
				
			foreach ($sectors as $sector) {
				// Remove inactive
				$sector->events = $sector->events->filter(function ($model) {
					return $model->isActive();
				});
				
				$calEvent = [];
				
				foreach ($sector->events as $event) {
					foreach ($event->datesTimes as $eventDateTime) {
						$eventDateTime->start = $eventDateTime->start;
						$eventDateTime->end = $eventDateTime->end;
						
						$options = json_decode($event->options, true);
		
						$colour = '#fe5001';
						
						if (!empty($event->sectors[0]->colour)) {
							$colour = $event->sectors[0]->colour;
						}
						
						$eventSectors = implode(', ', $event->sectors->pluck('slug')->toArray());
						
						$options = [
							'slug' => $event->slug,
							'image' => $event->image,
							'url' => $options['url'],
							'color' => $colour,
							'sectors' => $eventSectors,
							'description' => html_entity_decode($event->description, ENT_QUOTES | ENT_HTML5, 'UTF-8'),
						];
						
						$calendarEvent = [
							'id' => $eventDateTime->id,
							'_id' => $event->id,
							'title' => $this->htmlEntityDecode($event->title),
							'description' => $this->htmlEntityDecode($event->description),
							'start' => $eventDateTime->start,
							'end' => $eventDateTime->end,
							'sectors' => $eventSectors,
							'color' => $colour,
							'allDay' => $event->all_day,
							'all_day' => $event->all_day,
							'slug' => $event->slug,
							'image' => $event->image,
							'url' => $options['url'],
							'options' => $options,
						];
						
						$calEvent = array_merge($calendarEvent);
						
						// dd($calEvent);
						
						array_push($events, $calEvent);
					}
				}
			}
		}
		
		// dd(collect($events));
		
		return collect($events)->toJson();
	}
	
	public function requestAnEvent(Request $request)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		$department = $this->getDepartmentBySlug($request->segment(1));
			
		$department->title = $this->htmlEntityDecode($department->title);
		
		$this->mapImagesToAssets($department);
		
		$this->mapBannersToAssets($department);
		
		$templateTitle = 'Request An Event';
			
		$templateDescription = 'Request An Event';
		
		$templateKeywords = $this->generateKeywords('Request An Event');
			
		$sectors = $this->getData('getSectors', 'sectors');
		
		$sectors->forget(4); // ID 4
		$sectors->forget(5); // ID 5
		$sectors->forget(6); // ID 6
		$sectors->forget(7); // ID 7
		$sectors->forget(8); // ID 9
		
		$yammer = $department->yammer;
			
		$stream = $department->stream;
			
		return view('requestAnEvent', compact('currentUser', 'sectors', 'department', 'templateTitle', 'templateDescription', 'templateKeywords', 'yammer', 'stream'));	
	}
}
