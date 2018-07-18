<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Http\Controllers;

use DB;
use Log;
use App\Models\Status;
use Illuminate\Http\Request;
use App\Http\Traits\StatusTrait;
use App\Http\Controllers\Controller;

class StatusController extends Controller
{
	use StatusTrait;

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
		
		$this->cacheKey = 'statuses';
	}
	
	/**
	 * Get statuses view.
	 *
	 * @params	Request 	$request
	 * @return 	Response
	 */
   	public function index(Request $request)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('view_statuses')) {
			$title = 'Statuses';
			
			$subTitle = '';
			
			$leadParagraph = 'Statuses are used to control data visibility.';
			
			$statuses = $this->getCache($this->cacheKey);
			
			if (is_null($statuses)) {
				$statuses = $this->getStatuses();
				
				$this->setCache($this->cacheKey, $statuses);
			}
			
			$statuses = $statuses->each(function($model) {
				$model->title = $this->htmlEntityDecode($model->title);
				$model->description = $this->htmlEntityDecode($model->description);
			});
			
			return view('cp.advanced.statuses.index', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'statuses'));
		}
		
		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Shows a form for creating a new status.
	 *
	 * @params	Request 	$request
	 * @return 	Response
	 */
   	public function create(Request $request)
	{
		$currentUser = $this->getAuthenticatedUser();

		if ($currentUser->hasPermission('create_statuses')) {
			$title = 'Create Status';
			
			$subTitle = 'Statuses';
			
			$leadParagraph = 'Statuses are used to control data visibility.';
			
			return view('cp.advanced.statuses.create', compact('currentUser', 'title', 'subTitle', 'leadParagraph'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
     * Creates a new status.
     *
	 * @params Request 	$request
     * @return Response
     */
    public function store(Request $request)
    {
	    $currentUser = $this->getAuthenticatedUser();

		if ($currentUser->hasPermission('create_statuses')) {
			// Remove any Cross-site scripting (XSS)
			$cleanedStatus = $this->sanitizerInput($request->all());

			$rules = $this->getRules('status');
			
			// Make sure all the input data is what we actually save
			$validator = $this->validatorInput($cleanedStatus, $rules);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput();
			}

			DB::beginTransaction();

			try {
				// Create new model
				$status = new Status;
	
				// Set our field data
				$status->title = $cleanedStatus['title'];
				$status->description = $cleanedStatus['description'];
				
				$status->save();
				
				$this->setCache($this->cacheKey, $this->getStatuses());
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

			flash('Status created successfully.', $level = 'success');

			return redirect('/cp/advanced/statuses');
		}

		abort(403, 'Unauthorised action');
    }
    
    /**
	 * Shows a form for editing a status.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
   	public function edit(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('edit_statuses')) {
			$title = 'Edit Status';
		
			$subTitle = 'Statuses';
			
			$leadParagraph = 'Statuses are used to control data visibility.';
			
			$status = $this->getStatus($id);
			
			$status->title = $this->htmlEntityDecode($status->title);
			$status->description = $this->htmlEntityDecode($status->description);
			
			return view('cp.advanced.statuses.edit', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'status'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Updates a specific status.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
   	public function update(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();

		if ($currentUser->hasPermission('edit_statuses')) {
			// Remove any Cross-site scripting (XSS)
			$cleanedStatus = $this->sanitizerInput($request->all());

			$rules = $this->getRules('status');
			
			// Make sure all the input data is what we actually save
			$validator = $this->validatorInput($cleanedStatus, $rules);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput();
			}
			
			DB::beginTransaction();

			try {
				// Create new model
				$status = $this->getStatus($id);
				
				// Set our field data
				$status->title = $cleanedStatus['title'];
				$status->description = $cleanedStatus['description'];
				$status->updated_at = $this->datetime;
				
				$status->save();
				
				$this->setCache($this->cacheKey, $this->getStatuses());
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

			flash('Status updated successfully.', $level = 'success');

			return redirect('/cp/advanced/statuses');
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Shows a form for deleting a status.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
   	public function confirm(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('delete_statuses')) {
			$status = $this->getStatus($id);
		
			$status->title = $this->htmlEntityDecode($status->title);
		
			$title = 'Delete Status';
			
			$subTitle = 'Statuses';
			
			$leadParagraph = 'Statuses are used to control data visibility.';
			
			return view('cp.advanced.statuses.delete', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'status'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Deletes a specific status.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
   	public function delete(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('delete_statuses')) {
			$status = $this->getStatus($id);
			
			DB::beginTransaction();

			try {
				$status->delete();
				
				$this->setCache($this->cacheKey, $this->getStatuses());
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

			flash('Status deleted successfully.', $level = 'info');

			return redirect('/cp/advanced/statuses');
		}

		abort(403, 'Unauthorised action');
	}
}
