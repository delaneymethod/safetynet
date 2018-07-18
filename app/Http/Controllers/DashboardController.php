<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Http\Controllers;

use StorageHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\{UserTrait, IdeaTrait, ModelTrait, AssetTrait, StatusTrait, SectorTrait, EventTrait, ExpertTrait, ProductTrait, CategoryTrait, TeamMemberTrait, LocationTrait, ContentTypeTrait, DepartmentTrait};

class DashboardController extends Controller
{
	use UserTrait, IdeaTrait, ModelTrait, AssetTrait, StatusTrait, SectorTrait, EventTrait, ExpertTrait, ProductTrait, CategoryTrait, TeamMemberTrait, LocationTrait, ContentTypeTrait, DepartmentTrait;
	
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
	 * Get cp view.
	 *
	 * @params	Request 	$request
	 * @return 	Response
	 */
   	public function index(Request $request)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		$disk = StorageHelper::getDisk();
			
		// If we are redirecting user back to previous page, then we set the new route here
		$redirectTo = $request->get('redirectTo');
		
		if (!empty($redirectTo)) {
			return redirect($redirectTo);
		} else {
			$title = 'Dashboard';
			
			$subTitle = '';
			
			$statCards = [];
			
			if ($currentUser->hasPermission('view_users')) {
				$users = $this->getData('getUsers', 'users');
				
				array_push($statCards, [
					'id' => 'users',
					'label' => 'Users',
					'url' => '/cp/users',
					'count' => $users->count()
				]);
			}
			
			if ($currentUser->hasPermission('view_team_members')) {
				$teamMembers = $this->getData('getTeamMembers', 'team_members');
				
				array_push($statCards, [
					'id' => 'teamMembers',
					'label' => 'Team Members',
					'url' => '/cp/team-members',
					'count' => $teamMembers->count()
				]);
			}
			
			if ($currentUser->hasPermission('view_assets')) {
				$assets = $this->getAssets();
				
				$assets = $assets->filter(function ($asset) use ($disk) {
					return $asset->disk === $disk;
				});
		
				array_push($statCards, [
					'id' => 'assets',
					'label' => 'Assets',
					'url' => '/cp/assets',
					'count' => $assets->count()
				]);
			}
			
			if ($currentUser->hasPermission('view_departments')) {
				$departments = $this->getData('getDepartments', 'departments');
				
				array_push($statCards, [
					'id' => 'departments',
					'label' => 'Departments',
					'url' => '/cp/departments',
					'count' => $departments->count()
				]);
			}
			
			if ($currentUser->hasPermission('view_sectors')) {
				$sectors = $this->getData('getSectors', 'sectors');

				array_push($statCards, [
					'id' => 'sectors',
					'label' => 'Sectors',
					'url' => '/cp/sectors',
					'count' => $sectors->count()
				]);
			}
			
			if ($currentUser->hasPermission('view_categories')) {
				$categories = $this->getData('getCategories', 'categories');
				
				array_push($statCards, [
					'id' => 'categories',
					'label' => 'Categories',
					'url' => '/cp/categories',
					'count' => $categories->count()
				]);
			}
			
			if ($currentUser->hasPermission('view_content_types')) {
				$contentTypes = $this->getData('getContentTypes', 'content_types');
				
				array_push($statCards, [
					'id' => 'contentTypes',
					'label' => 'Content Types',
					'url' => '/cp/content-types',
					'count' => $contentTypes->count()
				]);
			}
			
			if ($currentUser->hasPermission('view_products')) {
				$products = $this->getData('getProducts', 'products');
				
				array_push($statCards, [
					'id' => 'products',
					'label' => 'Products',
					'url' => '/cp/products',
					'count' => $products->count()
				]);
			}
			
			if ($currentUser->hasPermission('view_models')) {
				$models = $this->getData('getModels', 'models');
				
				array_push($statCards, [
					'id' => 'models',
					'label' => 'Models',
					'url' => '/cp/models',
					'count' => $models->count()
				]);
			}
			
			if ($currentUser->hasPermission('view_experts')) {
				$experts = $this->getData('getExperts', 'experts');
				
				array_push($statCards, [
					'id' => 'experts',
					'label' => 'Experts',
					'url' => '/cp/experts',
					'count' => $experts->count()
				]);
			}
			
			if ($currentUser->hasPermission('view_locations')) {
				$locations = $this->getData('getLocations', 'locations');
				
				array_push($statCards, [
					'id' => 'locations',
					'label' => 'Locations',
					'url' => '/cp/locations',
					'count' => $locations->count()
				]);
			}
			
			if ($currentUser->hasPermission('view_events')) {
				$events = $this->getData('getEvents', 'events');
				
				array_push($statCards, [
					'id' => 'events',
					'label' => 'Events',
					'url' => '/cp/events',
					'count' => $events->count()
				]);
			}
			
			if ($currentUser->hasPermission('view_ideas')) {
				$ideas = $this->getData('getIdeas', 'ideas');
				
				array_push($statCards, [
					'id' => 'ideas',
					'label' => 'Ideas',
					'url' => '/cp/ideas',
					'count' => $ideas->count()
				]);
			}
					
			if (count($statCards) > 0) {
				$statCards = recursiveObject($statCards);
			}
			
			return view('cp.dashboard.index', compact('currentUser', 'title', 'subTitle', 'statCards'));
		}
	}
}
