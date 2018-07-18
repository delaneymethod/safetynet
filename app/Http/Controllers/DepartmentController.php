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
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use App\Http\Traits\{AssetTrait, StatusTrait, DepartmentTrait};

class DepartmentController extends Controller
{
	use AssetTrait, StatusTrait, DepartmentTrait;

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
		
		$this->cacheKey = 'departments';
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
		
		if ($currentUser->hasPermission('view_departments')) {
			$title = 'Departments';
			
			$subTitle = '';
			
			$leadParagraph = 'Departments are your top-level entry points into the system.';
			
			$departments = $this->getCache($this->cacheKey);
			
			if (is_null($departments)) {
				$departments = $this->getDepartments();
				
				$this->setCache($this->cacheKey, $departments);
			}
			
			$this->mapImagesToAssets($departments);
			
			$this->mapBannersToAssets($departments);
			
			$departments = $departments->each(function($model) {
				$model->title = $this->htmlEntityDecode($model->title);
				$model->description = $this->htmlEntityDecode($model->description);
			});
			
			return view('cp.departments.index', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'departments'));
		}
		
		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Shows a form for creating a new department.
	 *
	 * @params	Request 	$request
	 * @return 	Response
	 */
	public function create(Request $request)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('create_departments')) {
			$title = 'Create Department';
		
			$subTitle = 'Departments';
			
			$leadParagraph = 'Departments are your top-level entry points into the system.';
			
			// Used to set status_id
			$statuses = $this->getData('getStatuses');
			
			return view('cp.departments.create', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'statuses'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Creates a new department.
	 *
	 * @params Request 	$request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$currentUser = $this->getAuthenticatedUser();

		if ($currentUser->hasPermission('create_departments')) {
			// Remove any Cross-site scripting (XSS)
			$cleanedDepartment = $this->sanitizerInput($request->all());
			
			$rules = $this->getRules('department');
			
			// Make sure all the input data is what we actually save
			$validator = $this->validatorInput($cleanedDepartment, $rules);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput();
			}

			DB::beginTransaction();
			
			try {
				// Create new model
				$department = new Department;
	
				// Set our field data
				$department->title = $cleanedDepartment['title'];
				$department->slug = $cleanedDepartment['slug'];
				$department->description = $cleanedDepartment['description'];
				$department->banner = $this->fixProtocol($cleanedDepartment['banner']);
				$department->image = $this->fixProtocol($cleanedDepartment['image']);
				$department->yammer = $cleanedDepartment['yammer'];
				$department->stream = $cleanedDepartment['stream'];
				$department->status_id = $cleanedDepartment['status_id'];
				
				$department->save();
				
				$this->setCache($this->cacheKey, $this->getDepartments());
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

			flash('Department created successfully.', $level = 'success');

			return redirect('/cp/departments');
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Shows a form for editing a department.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
	public function edit(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('edit_departments')) {
			$title = 'Edit Department';
			
			$subTitle = 'Departments';
			
			$leadParagraph = 'Departments are your top-level entry points into the system.';
			
			$department = $this->getDepartment($id);
			
			$department->title = $this->htmlEntityDecode($department->title);
			$department->description = $this->htmlEntityDecode($department->description);
			
			$this->mapImagesToAssets($department);
			
			$this->mapBannersToAssets($department);
			
			// Used to set status_id
			$statuses = $this->getData('getStatuses');
			
			return view('cp.departments.edit', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'department', 'statuses'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Updates a specific department.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
	public function update(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();

		if ($currentUser->hasPermission('edit_departments')) {
			// Remove any Cross-site scripting (XSS)
			$cleanedDepartment = $this->sanitizerInput($request->all());
			
			$rules = $this->getRules('department');
			
			// Make sure all the input data is what we actually save
			$validator = $this->validatorInput($cleanedDepartment, $rules);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput();
			}

			DB::beginTransaction();

			try {
				// Get our model
				$department = $this->getDepartment($id);
				
				// Set our field data
				$department->title = $cleanedDepartment['title'];
				$department->slug = $cleanedDepartment['slug'];
				$department->description = $cleanedDepartment['description'];
				$department->banner = $this->fixProtocol($cleanedDepartment['banner']);
				$department->image = $this->fixProtocol($cleanedDepartment['image']);
				$department->yammer = $cleanedDepartment['yammer'];
				$department->stream = $cleanedDepartment['stream'];
				$department->status_id = $cleanedDepartment['status_id'];
				$department->updated_at = $this->datetime;
				
				$department->save();
				
				$this->setCache($this->cacheKey, $this->getDepartments());
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

			flash('Department updated successfully.', $level = 'success');

			return redirect('/cp/departments');
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Shows a form for deleting a department.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
	public function confirm(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('delete_departments')) {
			$department = $this->getDepartment($id);
			
			$department->title = $this->htmlEntityDecode($department->title);
			
			$title = 'Delete Department';
			
			$subTitle = 'Departments';
			
			$leadParagraph = 'Departments are your top-level entry points into the system.';
			
			return view('cp.departments.delete', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'department'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Deletes a specific department.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
	public function delete(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('delete_departments')) {
			$department = $this->getDepartment($id);
		
			DB::beginTransaction();

			try {
				$department->delete();
				
				$this->setCache($this->cacheKey, $this->getDepartments());
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

			flash('Department deleted successfully.', $level = 'info');

			return redirect('/cp/departments');
		}

		abort(403, 'Unauthorised action');
	}
}
