<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Http\Controllers;

use Calendar;
use Carbon\Carbon;
use App\Models\Sector;
use Illuminate\Http\Request;
use App\Events\FormSubmissionEvent;
use App\Http\Controllers\Controller;
use App\Http\Traits\{IdeaTrait, UserTrait, StatusTrait, EventTrait, SectorTrait, ProductTrait, CategoryTrait, TeamMemberTrait, ContentTypeTrait, DepartmentTrait};

class NexoController extends Controller
{
	use  IdeaTrait, UserTrait, StatusTrait, EventTrait, SectorTrait, ProductTrait, CategoryTrait, TeamMemberTrait, ContentTypeTrait, DepartmentTrait;
	
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
		
		$this->designerHubCategoryIds = [63];
	}

	/**
	 * Get index view.
	 *
	 * @params	Request 	$request
	 * @return 	Response
	 */
   	public function index(Request $request)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('view_departments')) {
			$department = $this->getDepartmentBySlug($request->segment(1));
			
			$department->title = $this->htmlEntityDecode($department->title);
			
			$this->mapImagesToAssets($department);
			
			$this->mapBannersToAssets($department);
			
			// Remove inactive
			$department->sectors = $department->sectors->filter(function ($model) {
				return $model->isActive();
			})->sortBy('order');
			
			// Removes the Designer Hub sector as per https://glazeteam.atlassian.net/browse/SGS-158
			$department->sectors->forget(1);
			$department->sectors->forget(3);
			
			$this->mapImagesToAssets($department->sectors);
			
			$this->mapBannersToAssets($department->sectors);
			
			$department->sectors = $department->sectors->each(function($model) {
				$model->title = $this->htmlEntityDecode($model->title);
				$model->description = $this->htmlEntityDecode($model->description);
			});
			
			$templateTitle = $department->title;
			
			$templateDescription = $department->description;
			
			$templateKeywords = $this->generateKeywords($department->title.' '.$department->description);
			
			$yammer = $department->yammer;
			
			$stream = $department->stream;
			
			$designerHubCategory = null;
			
			return view('nexo.index', compact('currentUser', 'department', 'templateTitle', 'templateDescription', 'templateKeywords', 'yammer', 'stream', 'designerHubCategory'));
		}
		
		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Get sector view.
	 *
	 * @params	Request 	$request
	 * @return 	Response
	 */
   	public function sector(Request $request, string $sector)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('view_sectors')) {
			if ($sector === 'ip-library' && !$currentUser->hasPermission('view_ip_library')) {
				abort(403, 'Unauthorised action');
			}
			
			$sector = $this->getSectorBySlug($sector);
			
			$sector->title = $this->htmlEntityDecode($sector->title);
			
			$designerHubCategory = $this->mapSectorToDesignerHubCategory($sector);
			
			$this->mapImagesToAssets($sector);
			
			$this->mapBannersToAssets($sector);
			
			// Remove inactive
			$sector->departments = $sector->departments->filter(function ($model) {
				return $model->isActive();
			});
			
			$sector->departments = $sector->departments->each(function($model) {
				$model->title = $this->htmlEntityDecode($model->title);
				$model->description = $this->htmlEntityDecode($model->description);
			});
			
			$department = $this->getDepartmentBySlug($request->segment(1));
			
			$department->title = $this->htmlEntityDecode($department->title);
			
			$this->mapImagesToAssets($department);
			
			$this->mapBannersToAssets($department);
			
			// Make sure the sector belongs to the department!
			if (!$sector->departments->pluck('slug')->contains($department->slug)) {
				abort(404);
			}
			
			$templateTitle = $this->generateTitle($sector->departments, $sector->title);
			
			$templateDescription = $sector->description;
			
			$templateKeywords = $this->generateKeywords($sector->title.' '.$sector->description);
			
			$sector->categories = $sector->categories->sortBy('order');
			
			// Remove Default categories - this is more used for direct linking to content types from sectors.
			$sector->categories = $sector->categories->filter(function ($model) {
				return ($model->slug !== 'functions');
			});
			
			// Remove Designer Hub categories - this is more used for hiding them from the categories grid
			$sector->categories = $sector->categories->filter(function ($model) {
				return (!in_array($model->id, $this->designerHubCategoryIds));
			});
			
			$sector->categories = $this->filterData($sector->categories, $sector);
			
			// We need to filter each categories products so we only have products in the current categories tagged departments
			$sector->categories->each(function ($category) use ($department) {
				$category->products = $category->products->filter(function ($product) use ($department) {
					return $product->departments->pluck('slug')->contains($department->slug);
				});
			});
			
			$sector->categories = $sector->categories->sortBy('order');
			
			$this->mapImagesToAssets($sector->categories);
			
			$this->mapBannersToAssets($sector->categories);
			
			$sector->categories = $sector->categories->each(function($model) {
				$model->title = $this->htmlEntityDecode($model->title);
				$model->description = $this->htmlEntityDecode($model->description);
			});

			$this->mapImagesToAssets($sector->products);
			
			$this->mapBannersToAssets($sector->products);
			
			$view = 'nexo.'.camel_case($sector->slug);
			
			$yammer = $sector->yammer;
			
			$stream = $sector->stream;
			
			return view($view, compact('currentUser', 'department', 'sector', 'templateTitle', 'templateDescription', 'templateKeywords', 'yammer', 'stream', 'designerHubCategory'));
		}
		
		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Get category view.
	 *
	 * @params	Request 	$request
	 * @return 	Response
	 */
   	public function category(Request $request, string $sector, string $category)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('view_categories')) {
			$segmentThree = $request->segment(3);
			
			if ($sector === 'ip-library' && !$currentUser->hasPermission('view_ip_library')) {
				abort(403, 'Unauthorised action');
			}
			
			if ($category === 'designer-hub' && !$currentUser->hasPermission('view_designer_hub')) {
				abort(403, 'Unauthorised action');
			}
			
			$category = $this->getCategoryBySlug($category);
			
			$category->title = $this->htmlEntityDecode($category->title);
			
			$this->mapImagesToAssets($category);
			
			$this->mapBannersToAssets($category);
			
			// Remove inactive
			$category->sectors = $category->sectors->filter(function ($model) {
				return $model->isActive();
			})->sortBy('order');
			
			$category->sectors = $category->sectors->each(function($model) {
				$model->title = $this->htmlEntityDecode($model->title);
				$model->description = $this->htmlEntityDecode($model->description);
			});
			
			$sector = $this->getSectorBySlug($sector);
			
			$sector->title = $this->htmlEntityDecode($sector->title);
			
			$designerHubCategory = $this->mapSectorToDesignerHubCategory($sector);
			
			$this->mapImagesToAssets($sector);
			
			$this->mapBannersToAssets($sector);
			
			// Make sure the category belongs to the sector!
			if (!$category->sectors->pluck('slug')->contains($sector->slug)) {
				abort(404);
			}
			
			// Remove inactive
			$sector->departments = $sector->departments->filter(function ($model) {
				return $model->isActive();
			});
			
			$sector->departments = $sector->departments->each(function($model) {
				$model->title = $this->htmlEntityDecode($model->title);
				$model->description = $this->htmlEntityDecode($model->description);
			});
			
			$department = $this->getDepartmentBySlug($request->segment(1));
			
			$department->title = $this->htmlEntityDecode($department->title);
			
			$this->mapImagesToAssets($department);
			
			$this->mapBannersToAssets($department);
			
			// Make sure the sector belongs to the department!
			if (!$sector->departments->pluck('slug')->contains($department->slug)) {
				abort(404);
			}
			
			$sector->categories = $sector->categories->sortBy('order');
			
			// Remove Default categories - this is more used for direct linking to content types from sectors.
			$sector->categories = $sector->categories->filter(function ($model) {
				return ($model->slug !== 'functions');
			});
			
			// Remove Designer Hub categories - this is more used for hiding them from the categories grid
			$sector->categories = $sector->categories->filter(function ($model) {
				return (!in_array($model->id, $this->designerHubCategoryIds));
			});
			
			$sector->categories = $sector->categories->each(function($model) {
				$model->title = $this->htmlEntityDecode($model->title);
				$model->description = $this->htmlEntityDecode($model->description);
			});
			
			$templateTitle = $this->generateTitle($category->sectors, $category->title);
			
			$templateDescription = $category->description;
			
			$templateKeywords = $this->generateKeywords($category->title.' '.$category->description);
			
			$category->contentTypes = $this->filterData($category->contentTypes, $category);
			
			$this->mapImagesToAssets($category->contentTypes);
		
			$this->mapBannersToAssets($category->contentTypes);
			
			$category->contentTypes = $category->contentTypes->each(function($model) {
				$model->title = $this->htmlEntityDecode($model->title);
				$model->description = $this->htmlEntityDecode($model->description);
			});
			
			$category->products = $this->filterData($category->products, $department, $sector, $category);
		
			$this->mapImagesToAssets($category->products);
		
			$this->mapBannersToAssets($category->products);
			
			$category->products = $category->products->each(function($model) {
				$model->title = $this->htmlEntityDecode($model->title);
				$model->description = $this->htmlEntityDecode($model->description);
			});
			
			$yammer = $sector->yammer;
			
			$stream = $sector->stream;
			
			return view('nexo.category', compact('currentUser', 'department', 'sector', 'category', 'templateTitle', 'templateDescription', 'templateKeywords', 'yammer', 'stream', 'designerHubCategory'));
		}
		
		abort(403, 'Unauthorised action');	
	}
	
	/**
	 * Get content type view.
	 *
	 * @params	Request 	$request
	 * @return 	Response
	 */
   	public function contentType(Request $request, string $sector, string $category, string $contentType)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('view_content_types')) {
			if ($sector === 'ip-library' && !$currentUser->hasPermission('view_ip_library')) {
				abort(403, 'Unauthorised action');
			}
			
			if ($category === 'designer-hub' && !$currentUser->hasPermission('view_designer_hub')) {
				abort(403, 'Unauthorised action');
			}
			
			$contentType = $this->getContentTypeBySlug($contentType);
			
			$contentType->title = $this->htmlEntityDecode($contentType->title);
			
			$this->mapImagesToAssets($contentType);
			
			$this->mapBannersToAssets($contentType);
			
			// Remove inactive
			$contentType->categories = $contentType->categories->filter(function ($model) {
				return $model->isActive();
			})->sortBy('order');
			
			$contentType->categories = $contentType->categories->each(function($model) {
				$model->title = $this->htmlEntityDecode($model->title);
				$model->description = $this->htmlEntityDecode($model->description);
			});
			
			$category = $this->getCategoryBySlug($category);
			
			$category->title = $this->htmlEntityDecode($category->title);
			
			$this->mapImagesToAssets($category);
			
			$this->mapBannersToAssets($category);
			
			// Make sure the content type belongs to the category!
			if (!$contentType->categories->pluck('slug')->contains($category->slug)) {
				abort(404);
			}
			
			$sector = $this->getSectorBySlug($sector);
			
			$sector->title = $this->htmlEntityDecode($sector->title);
			
			$designerHubCategory = $this->mapSectorToDesignerHubCategory($sector);
			
			$this->mapImagesToAssets($sector);
			
			$this->mapBannersToAssets($sector);
			
			// Remove inactive
			$sector->departments = $sector->departments->filter(function ($model) {
				return $model->isActive();
			});
			
			$sector->departments = $sector->departments->each(function($model) {
				$model->title = $this->htmlEntityDecode($model->title);
				$model->description = $this->htmlEntityDecode($model->description);
			});
			
			$department = $this->getDepartmentBySlug($request->segment(1));
			
			$department->title = $this->htmlEntityDecode($department->title);
			
			$this->mapImagesToAssets($department);
			
			$this->mapBannersToAssets($department);
			
			// Make sure the sector belongs to the department!
			if (!$sector->departments->pluck('slug')->contains($department->slug)) {
				abort(404);
			}
			
			// Remove inactive
			$category->sectors = $category->sectors->filter(function ($model) {
				return $model->isActive();
			})->sortBy('order');
			
			// Make sure the category belongs to the sector!
			if (!$category->sectors->pluck('slug')->contains($sector->slug)) {
				abort(404);
			}
			
			$sector->categories = $sector->categories->sortBy('order');
			
			// Remove Default categories - this is more used for direct linking to content types from sectors.
			$sector->categories = $sector->categories->filter(function ($model) {
				return ($model->slug !== 'functions');
			});
			
			// Remove Designer Hub categories - this is more used for hiding them from the categories grid
			$sector->categories = $sector->categories->filter(function ($model) {
				return (!in_array($model->id, $this->designerHubCategoryIds));
			});
			
			$sector->categories = $sector->categories->each(function($model) {
				$model->title = $this->htmlEntityDecode($model->title);
				$model->description = $this->htmlEntityDecode($model->description);
			});
			
			$templateTitle = $this->generateTitle($contentType->categories, $contentType->title);
			
			$templateDescription = $contentType->description;
			
			$templateKeywords = $this->generateKeywords($contentType->title.' '.$contentType->description);
			
			$contentType->products = $this->filterData($contentType->products, $department, $sector, $category, $contentType);
		
			$this->mapImagesToAssets($contentType->products);
		
			$this->mapBannersToAssets($contentType->products);
			
			$contentType->products = $contentType->products->each(function($model) {
				$model->title = $this->htmlEntityDecode($model->title);
				$model->description = $this->htmlEntityDecode($model->description);
			});
			
			$yammer = $sector->yammer;
			
			$stream = $sector->stream;
			
			return view('nexo.contentType', compact('currentUser', 'department', 'sector', 'category', 'contentType', 'templateTitle', 'templateDescription', 'templateKeywords', 'yammer', 'stream', 'designerHubCategory'));
		}
		
		abort(403, 'Unauthorised action');	
	}
	
	/**
	 * Get product view.
	 *
	 * @params	Request 	$request
	 * @return 	Response
	 */
   	public function product(Request $request, string $sector, string $category, string $contentType, string $product)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('view_products')) {
			if ($sector === 'ip-library' && !$currentUser->hasPermission('view_ip_library')) {
				abort(403, 'Unauthorised action');
			}
			
			if ($category === 'designer-hub' && !$currentUser->hasPermission('view_designer_hub')) {
				abort(403, 'Unauthorised action');
			}
			
			$product = $this->getProductBySlug($product);
			
			$product->title = $this->htmlEntityDecode($product->title);
			
			$this->mapImagesToAssets($product);
			
			$this->mapBannersToAssets($product);
			
			// Remove inactive
			$product->contentTypes = $product->contentTypes->filter(function ($model) {
				return $model->isActive();
			});
			
			$product->contentTypes = $product->contentTypes->each(function($model) {
				$model->title = $this->htmlEntityDecode($model->title);
				$model->description = $this->htmlEntityDecode($model->description);
			});
			
			$contentType = $this->getContentTypeBySlug($contentType);
			
			$contentType->title = $this->htmlEntityDecode($contentType->title);
			
			$this->mapImagesToAssets($contentType);
			
			$this->mapBannersToAssets($contentType);
			
			// Make sure the product belongs to the content type!
			if (!$product->contentTypes->pluck('slug')->contains($contentType->slug)) {
				abort(404);
			}
			
			$sector = $this->getSectorBySlug($sector);
			
			$sector->title = $this->htmlEntityDecode($sector->title);
			
			$designerHubCategory = $this->mapSectorToDesignerHubCategory($sector);
			
			$this->mapImagesToAssets($sector);
			
			$this->mapBannersToAssets($sector);
			
			// Remove inactive
			$sector->departments = $sector->departments->filter(function ($model) {
				return $model->isActive();
			});
			
			$sector->departments = $sector->departments->each(function($model) {
				$model->title = $this->htmlEntityDecode($model->title);
				$model->description = $this->htmlEntityDecode($model->description);
			});
			
			$department = $this->getDepartmentBySlug($request->segment(1));
			
			$department->title = $this->htmlEntityDecode($department->title);
			
			$this->mapImagesToAssets($department);
			
			$this->mapBannersToAssets($department);
			
			// Make sure the sector belongs to the department!
			if (!$sector->departments->pluck('slug')->contains($department->slug)) {
				abort(404);
			}
			
			$category = $this->getCategoryBySlug($category);
			
			$category->title = $this->htmlEntityDecode($category->title);
			
			$this->mapImagesToAssets($category);
			
			$this->mapBannersToAssets($category);	
			
			// Remove inactive
			$category->sectors = $category->sectors->filter(function ($model) {
				return $model->isActive();
			})->sortBy('order');
			
			// Make sure the category belongs to the sector!
			if (!$category->sectors->pluck('slug')->contains($sector->slug)) {
				abort(404);
			}
			
			// Remove inactive
			$contentType->categories = $contentType->categories->filter(function ($model) {
				return $model->isActive();
			})->sortBy('order');
		
			$contentType->categories = $contentType->categories->each(function($model) {
				$model->title = $this->htmlEntityDecode($model->title);
				$model->description = $this->htmlEntityDecode($model->description);
			});
			
			// Make sure the content type belongs to the category!
			if (!$contentType->categories->pluck('slug')->contains($category->slug)) {
				abort(404);
			}
			
			$sector->categories = $sector->categories->sortBy('order');
			
			// Remove Default categories - this is more used for direct linking to content types from sectors.
			$sector->categories = $sector->categories->filter(function ($model) {
				return ($model->slug !== 'functions');
			});
			
			// Remove Designer Hub categories - this is more used for hiding them from the categories grid
			$sector->categories = $sector->categories->filter(function ($model) {
				return (!in_array($model->id, $this->designerHubCategoryIds));
			});
			
			$sector->categories = $sector->categories->each(function($model) {
				$model->title = $this->htmlEntityDecode($model->title);
				$model->description = $this->htmlEntityDecode($model->description);
			});
			
			$templateTitle = $this->generateTitle($product->contentTypes, $product->title);
			
			$templateDescription = $product->description;
			
			$templateKeywords = $this->generateKeywords($product->title.' '.$product->description);
			
			$yammer = $sector->yammer;
			
			$stream = $sector->stream;
			
			$product->supportingFiles = $product->getMedia();
			
			$product->supportingFiles = $product->supportingFiles->sortByDesc('created_at');
			
			return view('nexo.product', compact('currentUser', 'department', 'sector', 'category', 'contentType', 'product', 'templateTitle', 'templateDescription', 'templateKeywords', 'yammer', 'stream', 'designerHubCategory'));
		}
		
		abort(403, 'Unauthorised action');	
	}
	
	/**
	 * Get events view.
	 *
	 * @params	Request 	$request
	 * @return 	Response
	 */
   	public function events(Request $request, string $sector = null)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('view_departments')) {
			$sectors = $this->getSectors();
			
			// Remove inactive
			$sectors = $sectors->filter(function ($model) {
				return $model->isActive();
			});
			
			$department = $this->getDepartmentBySlug($request->segment(1));
			
			$department->title = $this->htmlEntityDecode($department->title);
			
			$this->mapImagesToAssets($department);
			
			$this->mapBannersToAssets($department);
			
			$events = $this->getEvents();
			
			$designerHubCategory = null;

			if (!empty($sector)) {
				$sector = $this->getSectorBySlug($sector);
				
				$sector->title = $this->htmlEntityDecode($sector->title);
				
				$designerHubCategory = $this->mapSectorToDesignerHubCategory($sector);
				
				$this->mapImagesToAssets($sector);
				
				$this->mapBannersToAssets($sector);
			
				// Make sure the sector belongs to the department!
				if (!$sector->departments->pluck('slug')->contains($department->slug)) {
					abort(404);
				}
				
				// Filter events based on sector
				if (count($events) > 0 && $sector->events->count() > 0) {
					$events = $sector->events->diffAssoc($events)->unique();
				} else {
					$events = [];
				}
			}
			
			$calendar = Calendar::setOptions([
				'firstDay' => 1,
			]);
			
			// Remove inactive
			
			if (count($events) > 0) {
				$events = $events->filter(function ($model) {
					return $model->isActive();
				});
			
				foreach ($events as $event) {
					foreach ($event->datesTimes as $eventDateTime) {
						$eventDateTime->start = new Carbon($eventDateTime->start);
						$eventDateTime->end = new Carbon($eventDateTime->end);
						
						/*
						$event->start = $eventDateTime->start;
						$event->end = $eventDateTime->end;
						
						$options = json_decode($event->options);
						
						$calEvent = Calendar::event($event->title, $event->all_day, $eventDateTime->start, $eventDateTime->end, $eventDateTime->id, $options);
						
						$calendar->addEvent($event);
						*/
					}
				}
				
				$this->mapImagesToAssets($events);
				
				$events = $events->each(function($model) {
					$model->title = $this->htmlEntityDecode($model->title);
					$model->description = $this->htmlEntityDecode($model->description);
				});
			}

			$templateTitle = 'Events Calendar';
				
			$templateDescription = 'Events Calendar';
			
			$templateKeywords = $this->generateKeywords('Events Calendar');
			
			$yammer = $department->yammer;
			
			$stream = $department->stream;
			
			return view('nexo.events', compact('currentUser', 'department', 'sectors', 'sector', 'templateTitle', 'templateDescription', 'templateKeywords', 'yammer', 'stream', 'calendar', 'events', 'designerHubCategory'));
		}
		
		abort(403, 'Unauthorised action');			
	}
	
	/**
	 * Get ideas view.
	 *
	 * @params	Request 	$request
	 * @return 	Response
	 */
   	public function ideas(Request $request)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('view_ideas')) {
			$department = $this->getDepartmentBySlug($request->segment(1));
			
			$department->title = $this->htmlEntityDecode($department->title);
			
			$this->mapImagesToAssets($department);
			
			$this->mapBannersToAssets($department);
			
			$ideas = $this->getIdeas();
			
			// Remove inactive
			$ideas = $ideas->filter(function ($model) {
				return $model->isActive();
			});
			
			$ideas = $ideas->each(function($model) {
				$model->title = $this->htmlEntityDecode($model->title);
				$model->description = $this->htmlEntityDecode($model->description);
			});
			
			$this->mapImagesToAssets($ideas);
			
			$templateTitle = 'Ideas';
				
			$templateDescription = 'Ideas';
			
			$templateKeywords = $this->generateKeywords('Ideas');
			
			$yammer = $department->yammer;
			
			$stream = $department->stream;
			
			// HACK TO SHOW HUB IN SIDEBAR
			$sector = $this->getSectorBySlug('new-product-development');
			
			$designerHubCategory = $this->mapSectorToDesignerHubCategory($sector);
			
			return view('nexo.ideas', compact('currentUser', 'department', 'sector', 'templateTitle', 'templateDescription', 'templateKeywords', 'yammer', 'stream', 'ideas', 'designerHubCategory'));
		}
		
		abort(403, 'Unauthorised action');			
	}
	
	/**
	 * Get meet the team view.
	 *
	 * @params	Request 	$request
	 * @return 	Response
	 */
   	public function meetTheTeam(Request $request)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('view_team_members')) {
			$department = $this->getDepartmentBySlug($request->segment(1));
			
			$department->title = $this->htmlEntityDecode($department->title);
			
			$this->mapImagesToAssets($department);
			
			$this->mapBannersToAssets($department);
			
			$teamMembers = $this->getTeamMembers();
			
			// Remove inactive
			$teamMembers = $teamMembers->filter(function ($model) {
				return $model->isActive();
			});
			
			$this->mapImagesToAssets($teamMembers);
			
			$templateTitle = 'Meet The Team';
				
			$templateDescription = 'Meet The Team';
			
			$templateKeywords = $this->generateKeywords('Meet The Team');
			
			$yammer = $department->yammer;
			
			$stream = $department->stream;
			
			// HACK TO SHOW HUB IN SIDEBAR
			$sector = $this->getSectorBySlug('new-product-development');
			
			$designerHubCategory = $this->mapSectorToDesignerHubCategory($sector);
			
			return view('nexo.meetTheTeam', compact('currentUser', 'department', 'sector', 'templateTitle', 'templateDescription', 'templateKeywords', 'yammer', 'stream', 'teamMembers', 'designerHubCategory'));
		}
		
		abort(403, 'Unauthorised action');			
	}
	
	/**
	 * Processes new initial idea request
	 *
	 * @params	Request 	$request
	 * @return 	Response
	 */
	public function formSubmission(Request $request)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		$cleanedForm = $this->sanitizerInput($request->all());
		
		$cleanedForm = $this->uploadSupportingFiles($cleanedForm);
		
		FormSubmissionEvent::dispatch($cleanedForm, $currentUser);
		
		flash('Your '.$cleanedForm['form'].' post was submitted successfully.', $level = 'success');

		return back();
	}
	
	public function mapSectorToDesignerHubCategory(Sector $sector) 
	{
		switch($sector->id) {
			case 5:
				// new product development
				return $this->getCategoryById(63);
				break;

			case 6:
				// existing products
				return $this->getCategoryById(63);
				break;

			case 7:
				// IP library
				return $this->getCategoryById(63);
				break;

			case 9:
				// Bidder Toolkit
				return $this->getCategoryById(63);
				break;

			default:
				return;
		}
	}
}
