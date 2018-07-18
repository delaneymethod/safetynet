<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Http\Controllers;

use DB;
use Log;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\{RoleTrait, PermissionTrait};

class RoleController extends Controller
{
	use RoleTrait, PermissionTrait;

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
		
		$this->cacheKey = 'roles';
	}
	
	/**
	 * Get roles view.
	 *
	 * @params	Request 	$request
	 * @return 	Response
	 */
   	public function index(Request $request)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('view_roles')) {
			$title = 'Roles';
			
			$subTitle = '';
			
			$leadParagraph = 'Roles define a set of tasks a user who is assigned to the role is allowed to perform.';
			
			$roles = $this->getCache($this->cacheKey);
			
			if (is_null($roles)) {
				$roles = $this->getRoles();
				
				$this->setCache($this->cacheKey, $roles);
			}
			
			$roles = $roles->each(function($model) {
				$model->title = $this->htmlEntityDecode($model->title);
			});
			
			return view('cp.advanced.roles.index', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'roles'));
		}
		
		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Shows a form for creating a new role.
	 *
	 * @params	Request 	$request
	 * @return 	Response
	 */
   	public function create(Request $request)
	{
		$currentUser = $this->getAuthenticatedUser();

		if ($currentUser->hasPermission('create_roles')) {
			$title = 'Create Role';
			
			$subTitle = 'Roles';
			
			$leadParagraph = 'Roles define a set of tasks a user who is assigned to the role is allowed to perform.';
			
			return view('cp.advanced.roles.create', compact('currentUser', 'title', 'subTitle', 'leadParagraph'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
     * Creates a new role.
     *
	 * @params Request 	$request
     * @return Response
     */
    public function store(Request $request)
    {
	    $currentUser = $this->getAuthenticatedUser();

		if ($currentUser->hasPermission('create_roles')) {
			// Remove any Cross-site scripting (XSS)
			$cleanedRole = $this->sanitizerInput($request->all());

			$rules = $this->getRules('role');
			
			// Make sure all the input data is what we actually save
			$validator = $this->validatorInput($cleanedRole, $rules);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput();
			}

			DB::beginTransaction();

			try {
				// Create new model
				$role = new Role;
	
				// Set our field data
				$role->title = $cleanedRole['title'];
				
				$role->save();
				
				$this->setCache($this->cacheKey, $this->getRoles());
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

			flash('Role created successfully.', $level = 'success');

			return redirect('/cp/advanced/roles');
		}

		abort(403, 'Unauthorised action');
    }
    
    /**
     * Creates new permissions.
     *
	 * @params Request 	$request
     * @return Response
     */
    public function permissions(Request $request)
    {
	    $currentUser = $this->getAuthenticatedUser();

		if ($currentUser->hasPermission('create_permissions')) {
			// Remove any Cross-site scripting (XSS)
			$cleanedRolesPermissions = $this->sanitizerInput($request->all());
			
			// Creates custom role permission rules as matrix is dynamic
			if (!empty($cleanedRolesPermissions['1'])) {
				$permissions = count($cleanedRolesPermissions['1']) - 1;
			
				foreach (range(0, $permissions) as $index) {
					$rules['1.'.$index] = 'integer';
				}
			}
			
			if (!empty($cleanedRolesPermissions['2'])) {
				$permissions = count($cleanedRolesPermissions['2']) - 1;
			
				foreach (range(0, $permissions) as $index) {
					$rules['2.'.$index] = 'integer';
				}
			}
			
			if (!empty($cleanedRolesPermissions['3'])) {
				$permissions = count($cleanedRolesPermissions['3']) - 1;
				
				foreach (range(0, $permissions) as $index) {
					$rules['3.'.$index] = 'integer';
				}
			}
			
			// Make sure all the input data is what we actually save
			$validator = $this->validatorInput($cleanedRolesPermissions, $rules);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput();
			}
			
			// Prevent anyone removing Super Admin permissions via frontend - we enforce it here each time.
			$allPermissions = $this->getPermissions();
			
			$allPermissions = $allPermissions->pluck('id')->toArray();

			$cleanedRolesPermissions['1'] = $allPermissions;
				
			DB::beginTransaction();
			
			try {
				$roles = $this->getData('getRoles', 'roles');
				
				foreach($roles as $role) {
					if (!empty($cleanedRolesPermissions[$role->id])) {
						$role->setPermissions($cleanedRolesPermissions[$role->id]);
					} else {
						$role->setPermissions([]);
					}
				}
				
				$this->cacheKey = 'permissions';
				
				$this->setCache($this->cacheKey, $this->getPermissions());
					
				$this->cacheKey = 'roles';
				
				$this->setCache($this->cacheKey, $this->getRoles());
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

			flash('Changes saved successfully.', $level = 'success');

			return redirect('/cp/advanced/permissions');
		}

		abort(403, 'Unauthorised action');
    }
    
    /**
	 * Shows a form for editing a role.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
   	public function edit(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('edit_roles')) {
			$title = 'Edit Role';
		
			$subTitle = 'Roles';
			
			$leadParagraph = 'Roles define a set of tasks a user who is assigned to the role is allowed to perform.';
			
			$role = $this->getRole($id);
			
			$role->title = $this->htmlEntityDecode($role->title);
			
			return view('cp.advanced.roles.edit', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'role'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Updates a specific role.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
   	public function update(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();

		if ($currentUser->hasPermission('edit_roles')) {
			// Remove any Cross-site scripting (XSS)
			$cleanedRole = $this->sanitizerInput($request->all());

			$rules = $this->getRules('role');
			
			// Make sure all the input data is what we actually save
			$validator = $this->validatorInput($cleanedRole, $rules);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput();
			}
			
			DB::beginTransaction();

			try {
				// Create new model
				$role = $this->getRole($id);
				
				// Set our field data
				$role->title = $cleanedRole['title'];
				$role->updated_at = $this->datetime;
				
				$role->save();
				
				$this->setCache($this->cacheKey, $this->getRoles());
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

			flash('Role updated successfully.', $level = 'success');

			return redirect('/cp/advanced/roles');
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Shows a form for deleting a role.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
   	public function confirm(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('delete_roles')) {
			$role = $this->getRole($id);
			
			$role->title = $this->htmlEntityDecode($role->title);
			
			$title = 'Delete Role';
			
			$subTitle = 'Roles';
			
			$leadParagraph = 'Roles define a set of tasks a user who is assigned to the role is allowed to perform.';
			
			return view('cp.advanced.roles.delete', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'role'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Deletes a specific role.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
   	public function delete(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('delete_roles')) {
			$role = $this->getRole($id);
			
			DB::beginTransaction();

			try {
				$role->delete();
				
				$this->setCache($this->cacheKey, $this->getRoles());
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

			flash('Role deleted successfully.', $level = 'info');

			return redirect('/cp/advanced/roles');
		}

		abort(403, 'Unauthorised action');
	}
}
