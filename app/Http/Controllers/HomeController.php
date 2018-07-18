<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\DepartmentTrait;

class HomeController extends Controller
{
	use DepartmentTrait;
	
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
	 * Get home view.
	 *
	 * @params	Request 	$request
	 * @return 	Response
	 */
   	public function index(Request $request)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		$departments = $this->getData('getDepartments', 'departments');
		
		$this->mapImagesToAssets($departments);
		$this->mapBannersToAssets($departments);
		
		return view('index', compact('currentUser', 'departments'));
	}
}
