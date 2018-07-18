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
use App\Http\Traits\{StatusTrait, EventTrait, SectorTrait, ProductTrait, CategoryTrait, ContentTypeTrait, DepartmentTrait};

class SourceController extends Controller
{
	use StatusTrait, EventTrait, SectorTrait, ProductTrait, CategoryTrait, ContentTypeTrait, DepartmentTrait;
	
	private $updateCategoryIds;
	
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
		
		$this->updateCategoryIds = [54, 55, 56, 57];
		
		$this->strategyCategoryIds = [58, 59, 60, 61];
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

			$this->mapImagesToAssets($department->sectors);
			
			$this->mapBannersToAssets($department->sectors);
			
			$department->sectors = $department->sectors->each(function($model) {
				$model->title = $this->htmlEntityDecode($model->title);
				$model->description = $this->htmlEntityDecode($model->description);
			});
			
			// Bidder Toolkit only appears in the sidebar, not the main grid layout.
			$department->sectors->forget(4);
			
			$templateTitle = $department->title;
			
			$templateDescription = $department->description;
			
			$templateKeywords = $this->generateKeywords($department->title.' '.$department->description);
			
			$yammer = $department->yammer;
			
			$stream = $department->stream;
			
			$updateCategory = null;

			$strategyCategory = null;
				
			return view('source.index', compact('currentUser', 'department', 'templateTitle', 'templateDescription', 'templateKeywords', 'yammer', 'stream', 'updateCategory', 'strategyCategory'));
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
			$sector = $this->getSectorBySlug($sector);
			
			$sector->title = $this->htmlEntityDecode($sector->title);
			
			$updateCategory = $this->mapSectorToUpdateCategory($sector);
			
			$strategyCategory = $this->mapSectorToStrategyCategory($sector);
			
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
			
			$sector->categories = $sector->categories->sortBy('order');
			
			// Remove Default categories - this is more used for direct linking to content types from sectors.
			$sector->categories = $sector->categories->filter(function ($model) {
				return ($model->slug !== 'functions');
			});
			
			// Remove Update categories - this is more used for hiding them from the categories grid
			$sector->categories = $sector->categories->filter(function ($model) {
				return (!in_array($model->id, $this->updateCategoryIds));
			});
			
			// Remove Strategy categories - this is more used for hiding them from the categories grid
			$sector->categories = $sector->categories->filter(function ($model) {
				return (!in_array($model->id, $this->strategyCategoryIds));
			});
			
			$templateTitle = $this->generateTitle($sector->departments, $sector->title);
			
			$templateDescription = $sector->description;
			
			$templateKeywords = $this->generateKeywords($sector->title.' '.$sector->description);
			
			$this->mapImagesToAssets($sector->categories);
			
			$this->mapBannersToAssets($sector->categories);
			
			$sector->categories = $sector->categories->each(function($model) {
				$model->title = $this->htmlEntityDecode($model->title);
				$model->description = $this->htmlEntityDecode($model->description);
			});
			
			$yammer = $sector->yammer;
			
			$stream = $sector->stream;
			
			if ($updateCategory && $updateCategory->status->id === 2) {
				$updateCategory = null;
			}
			
			if ($strategyCategory && $strategyCategory->status->id === 2) {
				$strategyCategory = null;
			}
			
			return view('source.sector', compact('currentUser', 'department', 'sector', 'templateTitle', 'templateDescription', 'templateKeywords', 'yammer', 'stream', 'updateCategory', 'strategyCategory'));
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
			
			$category = $this->getCategoryBySlug($category);
			
			$category->title = $this->htmlEntityDecode($category->title);

			$this->mapImagesToAssets($category);
			
			$this->mapBannersToAssets($category);
			
			// Remove inactive
			$category->sectors = $category->sectors->filter(function ($model) {
				return $model->isActive();
			})->sortBy('order');
			
			// If the category has no experts, remove the content type "ask an expert"
			if ($category->experts->count() == 0) {
				$category->contentTypes = $category->contentTypes->filter(function ($model) {
					return $model->slug != 'ask-an-expert';
				})->values(); // Calling values() reindexes the collection :)
			}
			
			$category->sectors = $category->sectors->each(function($model) {
				$model->title = $this->htmlEntityDecode($model->title);
				$model->description = $this->htmlEntityDecode($model->description);
			});
			
			$sector = $this->getSectorBySlug($sector);
			
			$sector->title = $this->htmlEntityDecode($sector->title);

			$updateCategory = $this->mapSectorToUpdateCategory($sector);
			
			$strategyCategory = $this->mapSectorToStrategyCategory($sector);
			
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
			
			// Remove Update categories - this is more used for hiding them from the categories grid
			$sector->categories = $sector->categories->filter(function ($model) {
				return (!in_array($model->id, $this->updateCategoryIds));
			});
			
			// Remove Strategy categories - this is more used for hiding them from the categories grid
			$sector->categories = $sector->categories->filter(function ($model) {
				return (!in_array($model->id, $this->strategyCategoryIds));
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
			
			$this->mapImagesToAssets($category->experts);
			
			$category->experts = $category->experts->each(function($model) {
				$model->title = $this->htmlEntityDecode($model->title);
				$model->description = $this->htmlEntityDecode($model->description);
			});
			
			$yammer = $sector->yammer;
			
			$stream = $sector->stream;
			
			if ($updateCategory && $updateCategory->status->id === 2) {
				$updateCategory = null;
			}
			
			if ($strategyCategory && $strategyCategory->status->id === 2) {
				$strategyCategory = null;
			}
			
			return view('source.category', compact('currentUser', 'department', 'sector', 'category', 'templateTitle', 'templateDescription', 'templateKeywords', 'yammer', 'stream', 'updateCategory', 'strategyCategory'));
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

			$updateCategory = $this->mapSectorToUpdateCategory($sector);
			
			$strategyCategory = $this->mapSectorToStrategyCategory($sector);
			
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
			
			$category->sectors = $category->sectors->each(function($model) {
				$model->title = $this->htmlEntityDecode($model->title);
				$model->description = $this->htmlEntityDecode($model->description);
			});
			
			$sector->categories = $sector->categories->sortBy('order');
			
			// Remove Default categories - this is more used for direct linking to content types from sectors.
			$sector->categories = $sector->categories->filter(function ($model) {
				return ($model->slug !== 'functions');
			});
			
			// Remove Update categories - this is more used for hiding them from the categories grid
			$sector->categories = $sector->categories->filter(function ($model) {
				return (!in_array($model->id, $this->updateCategoryIds));
			});
			
			// Remove Strategy categories - this is more used for hiding them from the categories grid
			$sector->categories = $sector->categories->filter(function ($model) {
				return (!in_array($model->id, $this->strategyCategoryIds));
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
			
			if ($updateCategory && $updateCategory->status->id === 2) {
				$updateCategory = null;
			}
			
			if ($strategyCategory && $strategyCategory->status->id === 2) {
				$strategyCategory = null;
			}
			
			return view('source.contentType', compact('currentUser', 'department', 'sector', 'category', 'contentType', 'templateTitle', 'templateDescription', 'templateKeywords', 'yammer', 'stream', 'updateCategory', 'strategyCategory'));
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
			
			$updateCategory = $this->mapSectorToUpdateCategory($sector);
			
			$strategyCategory = $this->mapSectorToStrategyCategory($sector);
			
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
			
			$category->sectors = $category->sectors->each(function($model) {
				$model->title = $this->htmlEntityDecode($model->title);
				$model->description = $this->htmlEntityDecode($model->description);
			});
			
			// Remove inactive
			$contentType->categories = $contentType->categories->filter(function ($model) {
				return $model->isActive();
			})->sortBy('order');
		
			// Make sure the content type belongs to the category!
			if (!$contentType->categories->pluck('slug')->contains($category->slug)) {
				abort(404);
			}
			
			$contentType->categories = $contentType->categories->each(function($model) {
				$model->title = $this->htmlEntityDecode($model->title);
				$model->description = $this->htmlEntityDecode($model->description);
			});
			
			$sector->categories = $sector->categories->sortBy('order');
			
			// Remove Default categories - this is more used for direct linking to content types from sectors.
			$sector->categories = $sector->categories->filter(function ($model) {
				return ($model->slug !== 'functions');
			});
			
			// Remove Update categories - this is more used for hiding them from the categories grid
			$sector->categories = $sector->categories->filter(function ($model) {
				return (!in_array($model->id, $this->updateCategoryIds));
			});
			
			// Remove Strategy categories - this is more used for hiding them from the categories grid
			$sector->categories = $sector->categories->filter(function ($model) {
				return (!in_array($model->id, $this->strategyCategoryIds));
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
			
			if ($updateCategory && $updateCategory->status->id === 2) {
				$updateCategory = null;
			}
			
			if ($strategyCategory && $strategyCategory->status->id === 2) {
				$strategyCategory = null;
			}
			
			return view('source.product', compact('currentUser', 'department', 'sector', 'category', 'contentType', 'product', 'templateTitle', 'templateDescription', 'templateKeywords', 'yammer', 'stream', 'updateCategory', 'strategyCategory'));
		}
		
		abort(403, 'Unauthorised action');	
	}
	
	/**
	 * Get shop view.
	 *
	 * @params	Request 	$request
	 * @return 	Response
	 */
   	public function shop(Request $request)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('view_departments')) {
			$department = $this->getDepartmentBySlug($request->segment(1));
			
			$department->title = $this->htmlEntityDecode($department->title);
			
			$this->mapImagesToAssets($department);
			
			$this->mapBannersToAssets($department);
			
			$templateTitle = 'Shop';
				
			$templateDescription = 'Shop';
			
			$templateKeywords = $this->generateKeywords('Shop');
			
			$shop = true;
			
			$yammer = $department->yammer;
			
			$stream = $department->stream;
			
			$updateCategory = null;
			
			$strategyCategory = null;
			
			return view('source.shop.index', compact('currentUser', 'department', 'templateTitle', 'templateDescription', 'templateKeywords', 'shop', 'yammer', 'stream', 'updateCategory', 'strategyCategory'));
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
			
			$updateCategory = null;
			
			$strategyCategory = null;
			
			if (!empty($sector)) {
				$sector = $this->getSectorBySlug($sector);
				
				$sector->title = $this->htmlEntityDecode($sector->title);

				$updateCategory = $this->mapSectorToUpdateCategory($sector);
				
				$strategyCategory = $this->mapSectorToStrategyCategory($sector);
			
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
			
			if (count($events) > 0) {
				// Remove inactive
				$events = $events->filter(function ($model) {
					return $model->isActive();
				});
			
				foreach ($events as $event) {
					foreach ($event->datesTimes as $eventDateTime) {
						$eventDateTime->start = new Carbon($eventDateTime->start);
						$eventDateTime->end = new Carbon($eventDateTime->end);
						
						/*
						$castEvent->start = $eventDateTime->start;
						$castEvent->end = $eventDateTime->end;
						
						$options = json_decode($event->options);
						
						$calEvent = Calendar::event($event->title, $event->all_day, $eventDateTime->start, $eventDateTime->end, $eventDateTime->id, $options);
						
						$calendar->addEvent($castEvent);
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
			
			$templateKeywords = $this->generateKeywords('Events Calendar '.$department->title);
			
			$yammer = $department->yammer;
			
			$stream = $department->stream;
			
			if ($updateCategory && $updateCategory->status->id === 2) {
				$updateCategory = null;
			}
			
			if ($strategyCategory && $strategyCategory->status->id === 2) {
				$strategyCategory = null;
			}
			
			return view('source.events', compact('currentUser', 'department', 'sectors', 'sector', 'templateTitle', 'templateDescription', 'templateKeywords', 'calendar', 'events', 'yammer', 'stream', 'updateCategory', 'strategyCategory'));
		}
		
		abort(403, 'Unauthorised action');			
	}
	
	/**
	 * Get request a post view.
	 *
	 * @params	Request 	$request
	 * @return 	Response
	 */
   	public function requestAPost(Request $request)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('view_departments')) {
			$department = $this->getDepartmentBySlug($request->segment(1));
			
			$department->title = $this->htmlEntityDecode($department->title);
			
			$this->mapImagesToAssets($department);
			
			$this->mapBannersToAssets($department);
			
			$templateTitle = 'Request A Post';
				
			$templateDescription = 'Request A Post';
			
			$templateKeywords = $this->generateKeywords('Request A Post '.$department->title);
			
			$yammer = $department->yammer;
			
			$stream = $department->stream;
			
			$updateCategory = null;
			
			$strategyCategory = null;
			
			return view('source.requestAPost', compact('currentUser', 'department', 'templateTitle', 'templateDescription', 'templateKeywords', 'yammer', 'stream', 'updateCategory', 'strategyCategory'));
		}
				
		abort(403, 'Unauthorised action');			
	}
	
	/**
	 * Processes new acquisition target request
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
	
	public function mapSectorToUpdateCategory(Sector $sector) 
	{
		switch($sector->id) {
			case 1:
				// Marine > ON Board Updates
				return $this->getCategoryById(54);
				break;

			case 2:
				// Corporate > New Horizons Updates
				return $this->getCategoryById(55);
				break;

			case 3:
				// Defence & Aerospace > ON Target Updates
				return $this->getCategoryById(56);
				break;

			case 4:
				// Winds of Change > Winds of Change Updates
				return $this->getCategoryById(57);
				break;

			default:
				return;
		}
	}
	
	public function mapSectorToStrategyCategory(Sector $sector) 
	{
		switch($sector->id) {
			case 1:
				// Marine > ON Board Strategies
				return $this->getCategoryById(58);
				break;

			case 2:
				// Corporate > New Horizons Strategies
				return $this->getCategoryById(59);
				break;

			case 3:
				// Defence & Aerospace > ON Target Strategies
				return $this->getCategoryById(60);
				break;

			case 4:
				// Winds of Change > Winds of Change Strategies
				return $this->getCategoryById(61);
				break;

			default:
				return;
		}
	}
}
