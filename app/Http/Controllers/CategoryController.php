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
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use App\Http\Traits\{StatusTrait, SectorTrait, CategoryTrait};

class CategoryController extends Controller
{
	use StatusTrait, SectorTrait, CategoryTrait;

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
		
		$this->cacheKey = 'categories';
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
		
		if ($currentUser->hasPermission('view_categories')) {
			$title = 'Categories';
			
			$subTitle = '';
			
			$leadParagraph = 'Categories belong to Sectors.';
			
			$categories = $this->getCache($this->cacheKey);
			
			if (is_null($categories)) {
				$categories = $this->getCategoriesByOrder();
				
				$this->setCache($this->cacheKey, $categories);
			}
			
			$this->mapImagesToAssets($categories);
			
			$this->mapBannersToAssets($categories);
			
			$categories = $categories->each(function($model) {
				$model->title = $this->htmlEntityDecode($model->title);
				$model->description = $this->htmlEntityDecode($model->description);
			});
			
			return view('cp.categories.index', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'categories'));
		}
		
		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Shows a form for creating a new category.
	 *
	 * @params	Request 	$request
	 * @return 	Response
	 */
	public function create(Request $request)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('create_categories')) {
			$title = 'Create Category';
		
			$subTitle = 'Categories';
			
			$leadParagraph = 'Categories belong to Sectors.';
			
			// Used to set status_id
			$statuses = $this->getData('getStatuses');
			
			// Used to set sector_id
			$sectors = $this->getData('getSectors', 'sectors');
			
			return view('cp.categories.create', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'statuses', 'sectors'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Creates a new category.
	 *
	 * @params Request 	$request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$currentUser = $this->getAuthenticatedUser();

		if ($currentUser->hasPermission('create_categories')) {
			// Remove any Cross-site scripting (XSS)
			$cleanedCategory = $this->sanitizerInput($request->all());
			
			$rules = $this->getRules('category');
			
			// Make sure all the input data is what we actually save
			$validator = $this->validatorInput($cleanedCategory, $rules);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput();
			}

			DB::beginTransaction();
			
			try {
				// Create new model
				$category = new Category;
	
				// Set our field data
				$category->title = $cleanedCategory['title'];
				$category->slug = $cleanedCategory['slug'];
				$category->description = $cleanedCategory['description'];
				$category->banner = $this->fixProtocol($cleanedCategory['banner']);
				$category->image = $this->fixProtocol($cleanedCategory['image']);
				$category->status_id = $cleanedCategory['status_id'];
					
				$category->save();
				
				if (!empty($cleanedCategory['sector_ids'])) {
					$category->setSectors($cleanedCategory['sector_ids']);
				}
				
				$this->setCache($this->cacheKey, $this->getCategories());
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

			flash('Category created successfully.', $level = 'success');

			return redirect('/cp/categories');
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Shows a form for editing a category.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
	public function edit(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('edit_categories')) {
			$title = 'Edit Category';
			
			$subTitle = 'Categories';
			
			$leadParagraph = 'Categories belong to Sectors.';
			
			$category = $this->getCategory($id);
			
			$category->title = $this->htmlEntityDecode($category->title);
			$category->description = $this->htmlEntityDecode($category->description);
			
			$this->mapImagesToAssets($category);
			
			$this->mapBannersToAssets($category);
			
			// Used to set status_id
			$statuses = $this->getData('getStatuses');
			
			// Used to set sector_id
			$sectors = $this->getData('getSectors', 'sectors');
			
			return view('cp.categories.edit', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'category', 'statuses', 'sectors'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Updates a specific category.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
	public function update(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();

		if ($currentUser->hasPermission('edit_categories')) {
			// Remove any Cross-site scripting (XSS)
			$cleanedCategory = $this->sanitizerInput($request->all());
			
			$rules = $this->getRules('category');
			
			// Make sure all the input data is what we actually save
			$validator = $this->validatorInput($cleanedCategory, $rules);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput();
			}

			DB::beginTransaction();

			try {
				// Get our model
				$category = $this->getCategory($id);
				
				// Set our field data
				$category->title = $cleanedCategory['title'];
				$category->slug = $cleanedCategory['slug'];
				$category->description = $cleanedCategory['description'];
				$category->banner = $this->fixProtocol($cleanedCategory['banner']);
				$category->image = $this->fixProtocol($cleanedCategory['image']);
				$category->status_id = $cleanedCategory['status_id'];
				$category->updated_at = $this->datetime;
				
				$category->save();
				
				if (!empty($cleanedCategory['sector_ids'])) {
					$category->setSectors($cleanedCategory['sector_ids']);
				}
				
				$this->setCache($this->cacheKey, $this->getCategories());
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

			flash('Category updated successfully.', $level = 'success');

			return redirect('/cp/categories');
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Shows a form for deleting a category.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
	public function confirm(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('delete_categories')) {
			$category = $this->getCategory($id);
			
			$category->title = $this->htmlEntityDecode($category->title);
			
			$title = 'Delete Category';
			
			$subTitle = 'Categories';
			
			$leadParagraph = 'Categories belong to Sectors.';
			
			return view('cp.categories.delete', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'category'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Deletes a specific category.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
	public function delete(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('delete_categories')) {
			$category = $this->getCategory($id);
		
			DB::beginTransaction();

			try {
				$category->delete();
				
				$this->setCache($this->cacheKey, $this->getCategories());
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

			flash('Category deleted successfully.', $level = 'info');

			return redirect('/cp/categories');
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Sorts categories.
	 *
	 * @params	Request 	$request
	 * @return 	Response
	 */
	public function sort(Request $request)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('edit_categories')) {
			$cleanedOrder = $this->sanitizerInput($request->all());
			
			DB::beginTransaction();

			try {
				Category::setNewOrder($cleanedOrder['order']);
				
				$this->setCache($this->cacheKey, $this->getCategories());
			} catch (QueryException $queryException) {
				DB::rollback();
			
				Log::info('SQL: '.$queryException->getSql());

				Log::info('Bindings: '.implode(', ', $queryException->getBindings()));

				return response()->json([
					'error' => true,
					'queryException' => true,
					'message' => $queryException->getMessage()
				]);
			} catch (Exception $exception) {
				DB::rollback();

				return response()->json([
					'error' => true,
					'exception' => true,
					'message' => $exception->getMessage()
				]);
			}

			DB::commit();

			return response()->json([
				'message' => 'Order successfully saved.'
			]);
		} else {
			abort(403, 'Unauthorised action');
		}
	}
}
