<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\{RoleTrait, PermissionTrait};

class PermissionController extends Controller
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
	}
	
	/**
	 * Get permissions view.
	 *
	 * @params	Request 	$request
	 * @return 	Response
	 */
   	public function index(Request $request)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('view_permissions')) {
			$title = 'Permissions';
			
			$subTitle = '';
			
			$leadParagraph = 'Permissions are access rights to specific tasks.';
			
			$roles = $this->getData('getRoles', 'roles');
				
			$permissions = $this->getData('getPermissions', 'permissions');
			
			$permissions = $permissions->groupBy('permission_group_id');
			
			return view('cp.advanced.permissions.index', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'permissions', 'roles'));
		}
		
		abort(403, 'Unauthorised action');
	}
}
