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
use App\Models\Location;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use App\Http\Traits\{StatusTrait, CountyTrait, CountryTrait, LocationTrait};

class LocationController extends Controller
{
	use StatusTrait, CountyTrait, CountryTrait, LocationTrait;

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
		
		$this->cacheKey = 'locations';
	}
	
	/**
	 * Get locations view.
	 *
	 * @params	Request 	$request
	 * @return 	Response
	 */
   	public function index(Request $request)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('view_locations')) {
			$title = 'Locations';
		
			$subTitle = '';
			
			$leadParagraph = 'Locations are used to set a Users primary/default office.';
			
			$locations = $this->getCache($this->cacheKey);
			
			if (is_null($locations)) {
				$locations = $this->getLocations();
				
				$this->setCache($this->cacheKey, $locations);
			}
			
			$locations = $locations->each(function($model) {
				$model->title = $this->htmlEntityDecode($model->title);
			});
			
			return view('cp.locations.index', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'locations'));
		}
		
		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Shows a form for creating a new location.
	 *
	 * @params	Request 	$request
	 * @return 	Response
	 */
   	public function create(Request $request)
	{
		$currentUser = $this->getAuthenticatedUser();

		if ($currentUser->hasPermission('create_locations')) {
			$title = 'Create Location';
			
			$subTitle = 'Locations';
			
			$leadParagraph = 'Locations are used to set a Users primary/default office.';
			
			// Used to set county_id
			$counties = $this->getData('getCounties');
			
			// Used to set country_id
			$countries = $this->getData('getCountries');
			
			// Used to set status_id
			$statuses = $this->getData('getStatuses');
			
			return view('cp.locations.create', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'counties', 'countries', 'statuses'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
     * Creates a new location.
     *
	 * @params Request 	$request
     * @return Response
     */
    public function store(Request $request)
    {
	    $currentUser = $this->getAuthenticatedUser();

		if ($currentUser->hasPermission('create_locations')) {
			// Remove any Cross-site scripting (XSS)
			$cleanedLocation = $this->sanitizerInput($request->all());

			$rules = $this->getRules('location');
			
			// Make sure all the input data is what we actually save
			$validator = $this->validatorInput($cleanedLocation, $rules);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput();
			}

			DB::beginTransaction();

			try {
				// Create new model
				$location = new Location;
	
				// Set our field data
				$location->title = $cleanedLocation['title'];
				$location->unit = $cleanedLocation['unit'];
				$location->building = $cleanedLocation['building'];
				$location->street_address_1 = $cleanedLocation['street_address_1'];
				$location->street_address_2 = $cleanedLocation['street_address_2'];
				$location->street_address_3 = $cleanedLocation['street_address_3'];
				$location->street_address_4 = $cleanedLocation['street_address_4'];
				$location->town_city = $cleanedLocation['town_city'];
				$location->postal_code = $cleanedLocation['postal_code'];
				$location->county_id = $cleanedLocation['county_id'];
				$location->country_id = $cleanedLocation['country_id'];
				$location->telephone = $cleanedLocation['telephone'];
				$location->fax = $cleanedLocation['fax'];
				$location->email = $cleanedLocation['email'];
				$location->status_id = $cleanedLocation['status_id'];
				
				$location->save();
				
				$this->setCache($this->cacheKey, $this->getLocations());
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

			flash('Location created successfully.', $level = 'success');

			return redirect('/cp/locations');
		}

		abort(403, 'Unauthorised action');
    }
    
    /**
	 * Shows a form for editing a location.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
   	public function edit(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('edit_locations')) {
			$title = 'Edit Location';
		
			$subTitle = 'Locations';
			
			$leadParagraph = 'Locations are used to set a Users primary/default office.';
			
			$location = $this->getLocation($id);
			
			$location->title = $this->htmlEntityDecode($location->title);
			
			// Used to set county_id
			$counties = $this->getData('getCounties');
			
			// Used to set country_id
			$countries = $this->getData('getCountries');
			
			// Used to set status_id
			$statuses = $this->getData('getStatuses');
			
			return view('cp.locations.edit', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'location', 'counties', 'countries', 'statuses'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Updates a specific location.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
   	public function update(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();

		if ($currentUser->hasPermission('edit_locations')) {
			// Remove any Cross-site scripting (XSS)
			$cleanedLocation = $this->sanitizerInput($request->all());

			$rules = $this->getRules('location');
			
			// Make sure all the input data is what we actually save
			$validator = $this->validatorInput($cleanedLocation, $rules);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput();
			}
			
			DB::beginTransaction();

			try {
				// Create new model
				$location = $this->getLocation($id);
				
				// Set our field data
				$location->title = $cleanedLocation['title'];
				$location->unit = $cleanedLocation['unit'];
				$location->building = $cleanedLocation['building'];
				$location->street_address_1 = $cleanedLocation['street_address_1'];
				$location->street_address_2 = $cleanedLocation['street_address_2'];
				$location->street_address_3 = $cleanedLocation['street_address_3'];
				$location->street_address_4 = $cleanedLocation['street_address_4'];
				$location->town_city = $cleanedLocation['town_city'];
				$location->postal_code = $cleanedLocation['postal_code'];
				$location->county_id = $cleanedLocation['county_id'];
				$location->country_id = $cleanedLocation['country_id'];
				$location->telephone = $cleanedLocation['telephone'];
				$location->fax = $cleanedLocation['fax'];
				$location->email = $cleanedLocation['email'];
				$location->status_id = $cleanedLocation['status_id'];
				$location->updated_at = $this->datetime;
			
				$location->save();
				
				$this->setCache($this->cacheKey, $this->getLocations());
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

			flash('Location updated successfully.', $level = 'success');

			return redirect('/cp/locations');
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Shows a form for deleting a location.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
   	public function confirm(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('delete_locations')) {
			$location = $this->getLocation($id);
		
			$location->title = $this->htmlEntityDecode($location->title);
		
			$title = 'Delete Location';
			
			$subTitle = 'Locations';
			
			$leadParagraph = 'Locations are used to set a Users primary/default office.';

			return view('cp.locations.delete', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'location'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Deletes a specific location.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
   	public function delete(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('delete_locations')) {
			$location = $this->getLocation($id);
			
			DB::beginTransaction();

			try {
				$location->delete();
				
				$this->setCache($this->cacheKey, $this->getLocations());
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

			flash('Location deleted successfully.', $level = 'info');

			return redirect('/cp/locations');
		}

		abort(403, 'Unauthorised action');
	}
}
