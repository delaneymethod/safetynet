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
use App\Models\Expert;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use App\Http\Traits\{ExpertTrait, StatusTrait, CategoryTrait};

class ExpertController extends Controller
{
	use ExpertTrait, StatusTrait, CategoryTrait;

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
		
		$this->cacheKey = 'experts';
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
		
		if ($currentUser->hasPermission('view_experts')) {
			$title = 'Experts';
			
			$subTitle = '';
			
			$leadParagraph = 'Experts belong to Categories.';
			
			$experts = $this->getCache($this->cacheKey);
			
			if (is_null($experts)) {
				$experts = $this->getExperts();
				
				$this->setCache($this->cacheKey, $experts);
			}
			
			$this->mapImagesToAssets($experts);
			
			$experts = $experts->each(function($model) {
				$model->full_name = $this->htmlEntityDecode($model->full_name);
				$model->position = $this->htmlEntityDecode($model->position);
			});
			
			return view('cp.experts.index', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'experts'));
		}
		
		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Shows a form for creating a new expert.
	 *
	 * @params	Request 	$request
	 * @return 	Response
	 */
	public function create(Request $request)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('create_experts')) {
			$title = 'Create Expert';
		
			$subTitle = 'Experts';
			
			$leadParagraph = 'Experts belong to Categories.';
			
			// Used to set status_id
			$statuses = $this->getData('getStatuses');
			
			// Used to set category_id
			$categories = $this->getData('getCategories', 'categories');
			
			return view('cp.experts.create', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'statuses', 'categories'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Creates a new expert.
	 *
	 * @params Request 	$request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$currentUser = $this->getAuthenticatedUser();

		if ($currentUser->hasPermission('create_experts')) {
			// Remove any Cross-site scripting (XSS)
			$cleanedExpert = $this->sanitizerInput($request->all());
			
			$rules = $this->getRules('expert');
			
			// Make sure all the input data is what we actually save
			$validator = $this->validatorInput($cleanedExpert, $rules);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput();
			}

			DB::beginTransaction();
			
			try {
				// Create new expert
				$expert = new Expert;
	
				// Set our field data
				$expert->full_name = $cleanedExpert['full_name'];
				$expert->email = $cleanedExpert['email'];
				$expert->position = $cleanedExpert['position'];
				$expert->image = $cleanedExpert['image'];
				$expert->status_id = $cleanedExpert['status_id'];
				
				$expert->save();
				
				if (!empty($cleanedExpert['category_ids'])) {
					$expert->setCategories($cleanedExpert['category_ids']);
				}
				
				$this->setCache($this->cacheKey, $this->getExperts());
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

			flash('Expert created successfully.', $level = 'success');

			return redirect('/cp/experts');
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Shows a form for editing a expert.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
	public function edit(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('edit_experts')) {
			$title = 'Edit Expert';
			
			$subTitle = 'Experts';
			
			$leadParagraph = 'Experts belong to Categories.';
			
			$expert = $this->getExpert($id);
			
			$expert->full_name = $this->htmlEntityDecode($expert->full_name);
			$expert->position = $this->htmlEntityDecode($expert->position);
			
			$this->mapImagesToAssets($expert);
			
			// Used to set status_id
			$statuses = $this->getData('getStatuses');
			
			// Used to set category_id
			$categories = $this->getData('getCategories', 'categories');
			
			return view('cp.experts.edit', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'expert', 'statuses', 'categories'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Updates a specific expert.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
	public function update(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();

		if ($currentUser->hasPermission('edit_experts')) {
			// Remove any Cross-site scripting (XSS)
			$cleanedExpert = $this->sanitizerInput($request->all());
			
			$rules = $this->getRules('expert');
			
			$rules['email'] = 'required|email|unique:experts,email,'.$id.'|max:255';
			
			// Make sure all the input data is what we actually save
			$validator = $this->validatorInput($cleanedExpert, $rules);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput();
			}

			DB::beginTransaction();

			try {
				// Get our expert
				$expert = $this->getExpert($id);
				
				// Set our field data
				$expert->full_name = $cleanedExpert['full_name'];
				$expert->email = $cleanedExpert['email'];
				$expert->position = $cleanedExpert['position'];
				$expert->image = $cleanedExpert['image'];
				$expert->status_id = $cleanedExpert['status_id'];
				$expert->updated_at = $this->datetime;
				
				$expert->save();
				
				if (!empty($cleanedExpert['category_ids'])) {
					$expert->setCategories($cleanedExpert['category_ids']);
				}
				
				$this->setCache($this->cacheKey, $this->getExperts());
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

			flash('Expert updated successfully.', $level = 'success');

			return redirect('/cp/experts');
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Shows a form for deleting a expert.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
	public function confirm(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('delete_experts')) {
			$expert = $this->getExpert($id);
			
			$expert->full_name = $this->htmlEntityDecode($expert->full_name);
			
			$title = 'Delete Expert';
			
			$subTitle = 'Experts';
			
			$leadParagraph = 'Experts belong to Categories.';
			
			return view('cp.experts.delete', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'expert'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Deletes a specific expert.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
	public function delete(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('delete_experts')) {
			$expert = $this->getExpert($id);
		
			DB::beginTransaction();

			try {
				$expert->delete();
				
				$this->setCache($this->cacheKey, $this->getExperts());
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

			flash('Expert deleted successfully.', $level = 'info');

			return redirect('/cp/experts');
		}

		abort(403, 'Unauthorised action');
	}
}
