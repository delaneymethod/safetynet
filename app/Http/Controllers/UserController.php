<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Http\Controllers;

use DB;
use Log;
use App\User;
use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Events\UserUpdatedEvent;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Password;
use App\Http\Traits\{RoleTrait, UserTrait, StatusTrait,  LocationTrait};

class UserController extends Controller
{	
	use RoleTrait, UserTrait, StatusTrait, LocationTrait;
	
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
		
		$this->cacheKey = 'users';
	}

	/**
	 * Get users view.
	 *
	 * @return 	Response
	 */
   	public function index(Request $request)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('view_users')) {
			$title = 'Users';
	
			$subTitle = '';
			
			$users = $this->getCache($this->cacheKey);
			
			if (is_null($users)) {
				$users = $this->getUsers();
				
				$this->setCache($this->cacheKey, $users);
			}
			
			$users = $users->each(function($model) {
				$model->first_name = $this->htmlEntityDecode($model->first_name);
				$model->last_name = $this->htmlEntityDecode($model->last_name);
				$model->job_title = $this->htmlEntityDecode($model->job_title);
				$model->bio = $this->htmlEntityDecode($model->bio);
			});
			
			return view('cp.users.index', compact('currentUser', 'title', 'subTitle', 'users'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Shows a form for creating a new user.
	 *
	 * @params	Request 	$request
	 * @return 	Response
	 */
   	public function create(Request $request)
	{
		$currentUser = $this->getAuthenticatedUser();

		if ($currentUser->hasPermission('create_users')) {
			$title = 'Create User';
		
			$subTitle = 'Users';
			
			// Used to set role_id
			$roles = $this->getData('getRoles', 'roles');
			
			// If current user is not a super admin, hide super admin role
			if (!$currentUser->isSuperAdmin()) {
				$roles->forget(0);
			}
			
			// Used to set status_id
			$statuses = $this->getData('getStatuses');
			
			// Used to set location_id
			$locations = $this->getData('getLocations', 'locations');
			
			return view('cp.users.create', compact('currentUser', 'title', 'subTitle', 'statuses', 'roles', 'locations'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
     * Creates a new user.
     *
	 * @params Request 	$request
     * @return Response
     */
    public function store(Request $request)
    {
		$currentUser = $this->getAuthenticatedUser();

		if ($currentUser->hasPermission('create_users')) {
			// Remove any Cross-site scripting (XSS)
			$cleanedUser = $this->sanitizerInput($request->all());

			$rules = $this->getRules('user');
			
			// Make sure all the input data is what we actually save
			$validator = $this->validatorInput($cleanedUser, $rules);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput();
			}
			
			if ($cleanedUser['role_id'] == 1) {
				flash('Max number of Super Admin users was reached! User details were not updated.', $level = 'danger');

				return redirect('/cp/users');
			}

			DB::beginTransaction();

			try {
				// Create new model
				$user = new User;
	
				// Set our field data
				$user->first_name = $cleanedUser['first_name'];
				$user->last_name = $cleanedUser['last_name'];
				$user->email = $cleanedUser['email'];
				$user->location_id = $cleanedUser['location_id'];
				$user->telephone = $cleanedUser['telephone'];
				$user->image = $cleanedUser['image'];
				$user->skype = $cleanedUser['skype'];
				$user->job_title = $cleanedUser['job_title'];
				$user->bio = $cleanedUser['bio'];
				$user->password = bcrypt($cleanedUser['password']);
				$user->status_id = $cleanedUser['status_id'];
				$user->role_id = $cleanedUser['role_id'];
				
				$user->save();
				
				// Allows user to set a password
				Password::sendResetLink(['email' => $user->email]);
				
				$users = $this->getUsers();
					
				$this->setCache($this->cacheKey, $users);
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

			flash('User created successfully.', $level = 'success');

			return redirect('/cp/users');
		}

		abort(403, 'Unauthorised action');
    }
    
    /**
	 * Shows a form for editing a user.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
   	public function edit(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('edit_users') || $currentUser->id == $id) {
			$title = 'Edit User';
		
			$subTitle = 'Users';
			
			$user = $this->getUser($id);
			
			$user->first_name = $this->htmlEntityDecode($user->first_name);
			$user->last_name = $this->htmlEntityDecode($user->last_name);
			$user->job_title = $this->htmlEntityDecode($user->job_title);
			$user->bio = $this->htmlEntityDecode($user->bio);
			
			$this->mapImagesToAssets($user);
			
			// Used to set role_id
			$roles = $this->getData('getRoles', 'roles');
			
			// If current user is not a super admin, hide super admin role
			if (!$currentUser->isSuperAdmin()) {
				$roles->forget(0);
			}
			
			// Used to set status_id
			$statuses = $this->getData('getStatuses');
			
			// Used to set location_id
			$locations = $this->getData('getLocations', 'locations');
			
			return view('cp.users.edit.index', compact('currentUser', 'title', 'subTitle', 'user', 'roles', 'statuses', 'locations'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Shows a form for editing a users password.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
   	public function editPassword(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('edit_passwords_users') || $currentUser->id == $id) {
			$title = 'Change Password';
			
			$subTitle = 'Users';
			
			$user = $this->getUser($id);
			
			return view('cp.users.edit.password', compact('currentUser', 'title', 'subTitle', 'user'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Updates a specific user.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
   	public function update(Request $request, int $id)
	{
		$permission = 'edit_users';
		
		$updatePassword = false;
		
		if ($request->get('password_confirmation')) {
			$updatePassword = true;
		}
		
		// User is changing password so add "on the fly" permissions
		if ($updatePassword) {
			$permission = 'edit_passwords_users';
		}
		
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission($permission) || $currentUser->id == $id) {
			// Remove any Cross-site scripting (XSS)
			$cleanedUser = $this->sanitizerInput($request->all());
			
			$rules = $this->getRules('user');
			
			// User is changing password so add "on the fly" rule
			if ($updatePassword) {
				$rules = [];
				
				$rules['password_confirmation'] = 'required|string|same:password|max:255';
			} else {
				$rules['email'] = 'required|email|unique:users,email,'.$id.'|max:255';
			}
			
			// Make sure all the input data is what we actually save
			$validator = $this->validatorInput($cleanedUser, $rules);
			
			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput();
			}
			
			DB::beginTransaction();
			
			try {
				// Create new model
				$user = $this->getUser($id);
				
				// Set our field data
				if ($updatePassword) {
					$user->password = bcrypt($cleanedUser['password_confirmation']);
				} else {
					$user->first_name = $cleanedUser['first_name'];
					$user->last_name = $cleanedUser['last_name'];
					$user->email = $cleanedUser['email'];
					$user->password = $cleanedUser['password'];
					$user->telephone = $cleanedUser['telephone'];
					$user->image = $cleanedUser['image'];
					$user->skype = $cleanedUser['skype'];
					$user->job_title = $cleanedUser['job_title'];
					$user->bio = $cleanedUser['bio'];					
					$user->location_id = $cleanedUser['location_id'];
					$user->status_id = $cleanedUser['status_id'];
					$user->role_id = $cleanedUser['role_id'];
				}
								
				$user->updated_at = $this->datetime;
				
				$user->save();
				
				$users = $this->getUsers();
					
				$this->setCache($this->cacheKey, $users);
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
			
			// This will only be the case if the user has requested to edit their details fron the checkout page
			$redirectTo = $request->get('redirectTo');

			if (!empty($redirectTo)) {
				return redirect($redirectTo);
			}
		
			flash('User updated successfully.', $level = 'success');
			
			return redirect('/cp/users');
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Shows a form for deleting a user.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
   	public function confirm(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('delete_users')) {
			$user = $this->getUser($id);
		
			if ($currentUser->id == $user->id) {
				flash('You cannot delete yourself.', $level = 'warning');
	
				return redirect('/cp/users');
			}
			
			$title = 'Delete User';
			
			$subTitle = 'Users';
			
			return view('cp.users.delete', compact('currentUser', 'title', 'subTitle', 'user'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Deletes a specific user.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
   	public function delete(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('delete_users')) {
			$user = $this->getUser($id);
			
			if ($currentUser->id == $user->id) {
				flash('You cannot delete yourself.', $level = 'warning');

				return redirect('/cp/users');
			}
		
			DB::beginTransaction();

			try {
				$user->delete();
				
				$users = $this->getUsers();
				
				$this->setCache($this->cacheKey, $users);
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
			
			flash('User deleted successfully.', $level = 'success');
			
			return redirect('/cp/users');
		}

		abort(403, 'Unauthorised action');
	}
}
