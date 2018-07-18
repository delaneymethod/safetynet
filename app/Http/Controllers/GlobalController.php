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
use App\Models\Globals;
use Illuminate\Http\Request;
use App\Http\Traits\GlobalTrait;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;

class GlobalController extends Controller
{
	use GlobalTrait;

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
		
		$this->cacheKey = 'globals';
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
		
		if ($currentUser->hasPermission('view_globals')) {
			$title = 'Globals';
			
			$subTitle = '';
			
			$leadParagraph = 'Globals are visible throughout the web app.';
			
			$globals = $this->getCache($this->cacheKey);
			
			if (is_null($globals)) {
				$globals = $this->getGlobals();
				
				$this->setCache($this->cacheKey, $globals);
			}
			
			return view('cp.globals.index', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'globals'));
		}
		
		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Shows a form for creating a new global.
	 *
	 * @params	Request 	$request
	 * @return 	Response
	 */
	public function create(Request $request)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('create_globals')) {
			$title = 'Create Global';
		
			$subTitle = 'Globals';
			
			$leadParagraph = 'Globals are visible throughout the web app.';
			
			return view('cp.globals.create', compact('currentUser', 'title', 'subTitle', 'leadParagraph'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Creates a new global.
	 *
	 * @params Request 	$request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$currentUser = $this->getAuthenticatedUser();

		if ($currentUser->hasPermission('create_globals')) {
			// Remove any Cross-site scripting (XSS)
			$cleanedGlobal = $this->sanitizerInput($request->all());
			
			$rules = $this->getRules('global');
			
			// Make sure all the input data is what we actually save
			$validator = $this->validatorInput($cleanedGlobal, $rules);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput();
			}

			DB::beginTransaction();
			
			try {
				// Create new model
				$global = new Globals;
	
				// Set our field data
				$global->title = $cleanedGlobal['title'];
				$global->handle = $cleanedGlobal['handle'];
				$global->data = $cleanedGlobal['data'];
				$global->image = $this->fixProtocol($cleanedGlobal['image']);
				
				$global->save();
				
				$this->setCache($this->cacheKey, $this->getGlobals());
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

			flash('Global created successfully.', $level = 'success');

			return redirect('/cp/globals');
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Shows a form for editing a global.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
	public function edit(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('edit_globals')) {
			$title = 'Edit Global';
			
			$subTitle = 'Globals';
			
			$leadParagraph = 'Globals are visible throughout the web app.';
			
			$global = $this->getGlobal($id);
			
			return view('cp.globals.edit', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'global'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Updates a specific global.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
	public function update(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();

		if ($currentUser->hasPermission('edit_globals')) {
			// Remove any Cross-site scripting (XSS)
			$cleanedGlobal = $this->sanitizerInput($request->all());
			
			$rules = $this->getRules('global');
			
			$rules['handle'] = 'required|string|unique:globals,handle,'.$id.'|max:255';
			
			// Make sure all the input data is what we actually save
			$validator = $this->validatorInput($cleanedGlobal, $rules);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput();
			}

			DB::beginTransaction();

			try {
				// Get our model
				$global = $this->getGlobal($id);
				
				// Set our field data
				$global->title = $cleanedGlobal['title'];
				$global->handle = $cleanedGlobal['handle'];
				$global->data = $cleanedGlobal['data'];
				$global->image = $this->fixProtocol($cleanedGlobal['image']);
				$global->updated_at = $this->datetime;
				
				$global->save();
				
				$this->setCache($this->cacheKey, $this->getGlobals());
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

			flash('Global updated successfully.', $level = 'success');

			return redirect('/cp/globals');
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Shows a form for deleting a global.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
	public function confirm(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('delete_globals')) {
			$global = $this->getGlobal($id);
			
			$title = 'Delete Global';
			
			$subTitle = 'Globals';
			
			$leadParagraph = 'Globals are visible throughout the web app.';
			
			return view('cp.globals.delete', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'global'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Deletes a specific global.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
	public function delete(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('delete_globals')) {
			$global = $this->getGlobal($id);
		
			DB::beginTransaction();

			try {
				$global->delete();
				
				$this->setCache($this->cacheKey, $this->getGlobals());
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

			flash('Global deleted successfully.', $level = 'info');

			return redirect('/cp/globals');
		}

		abort(403, 'Unauthorised action');
	}
}
