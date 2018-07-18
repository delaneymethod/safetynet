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
use App\Models\ContentType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use App\Http\Traits\{StatusTrait, CategoryTrait, ContentTypeTrait};

class ContentTypeController extends Controller
{
	use StatusTrait, CategoryTrait, ContentTypeTrait;

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
		
		$this->cacheKey = 'content_types';
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
		
		if ($currentUser->hasPermission('view_content_types')) {
			$title = 'Content Types';
			
			$subTitle = '';
			
			$leadParagraph = 'Content Types belong to Categories.';
			
			$contentTypes = $this->getCache($this->cacheKey);
			
			if (is_null($contentTypes)) {
				$contentTypes = $this->getContentTypes();
				
				$this->setCache($this->cacheKey, $contentTypes);
			}
			
			$this->mapImagesToAssets($contentTypes);
			
			$this->mapBannersToAssets($contentTypes);
			
			$contentTypes = $contentTypes->each(function($model) {
				$model->title = $this->htmlEntityDecode($model->title);
				$model->description = $this->htmlEntityDecode($model->description);
			});
			
			return view('cp.contentTypes.index', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'contentTypes'));
		}
		
		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Shows a form for creating a new content type.
	 *
	 * @params	Request 	$request
	 * @return 	Response
	 */
	public function create(Request $request)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('create_content_types')) {
			$title = 'Create Content Type';
		
			$subTitle = 'Content Types';
			
			$leadParagraph = 'Content Types belong to Categories.';
			
			// Used to set status_id
			$statuses = $this->getData('getStatuses');
			
			// Used to set category_id
			$categories = $this->getData('getCategories', 'categories');
			
			return view('cp.contentTypes.create', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'statuses', 'categories'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Creates a new content type.
	 *
	 * @params Request 	$request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$currentUser = $this->getAuthenticatedUser();

		if ($currentUser->hasPermission('create_content_types')) {
			// Remove any Cross-site scripting (XSS)
			$cleanedContentType = $this->sanitizerInput($request->all());
			
			$rules = $this->getRules('content_type');
			
			// Make sure all the input data is what we actually save
			$validator = $this->validatorInput($cleanedContentType, $rules);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput();
			}

			DB::beginTransaction();
			
			try {
				// Create new model
				$contentType = new ContentType;
	
				// Set our field data
				$contentType->title = $cleanedContentType['title'];
				$contentType->slug = $cleanedContentType['slug'];
				$contentType->description = $cleanedContentType['description'];
				$contentType->banner = $this->fixProtocol($cleanedContentType['banner']);
				$contentType->image = $this->fixProtocol($cleanedContentType['image']);
				$contentType->status_id = $cleanedContentType['status_id'];
				
				$contentType->save();
				
				if (!empty($cleanedContentType['category_ids'])) {
					$contentType->setCategories($cleanedContentType['category_ids']);
				}
				
				$this->setCache($this->cacheKey, $this->getContentTypes());
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

			flash('Content Type created successfully.', $level = 'success');

			return redirect('/cp/content-types');
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Shows a form for editing a content type.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
	public function edit(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('edit_content_types')) {
			$title = 'Edit Content Type';
			
			$subTitle = 'Content Types';
			
			$leadParagraph = 'Content Types belong to Categories.';
			
			$contentType = $this->getContentType($id);
			
			$contentType->title = $this->htmlEntityDecode($contentType->title);
			$contentType->description = $this->htmlEntityDecode($contentType->description);

			$this->mapImagesToAssets($contentType);
			
			$this->mapBannersToAssets($contentType);
			
			// Used to set status_id
			$statuses = $this->getData('getStatuses');
			
			// Used to set category_id
			$categories = $this->getData('getCategories', 'categories');
			
			return view('cp.contentTypes.edit', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'contentType', 'statuses', 'categories'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Updates a specific content type.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
	public function update(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();

		if ($currentUser->hasPermission('edit_content_types')) {
			// Remove any Cross-site scripting (XSS)
			$cleanedContentType = $this->sanitizerInput($request->all());
			
			$rules = $this->getRules('content_type');
			
			// Make sure all the input data is what we actually save
			$validator = $this->validatorInput($cleanedContentType, $rules);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput();
			}

			DB::beginTransaction();

			try {
				// Get our model
				$contentType = $this->getContentType($id);
				
				// Set our field data
				$contentType->title = $cleanedContentType['title'];
				$contentType->slug = $cleanedContentType['slug'];
				$contentType->description = $cleanedContentType['description'];
				$contentType->banner = $this->fixProtocol($cleanedContentType['banner']);
				$contentType->image = $this->fixProtocol($cleanedContentType['image']);
				$contentType->status_id = $cleanedContentType['status_id'];
				$contentType->updated_at = $this->datetime;
				
				$contentType->save();
				
				if (!empty($cleanedContentType['category_ids'])) {
					$contentType->setCategories($cleanedContentType['category_ids']);
				}
				
				$this->setCache($this->cacheKey, $this->getContentTypes());
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

			flash('Content Type updated successfully.', $level = 'success');

			return redirect('/cp/content-types');
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Shows a form for deleting a content type.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
	public function confirm(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('delete_content_types')) {
			$contentType = $this->getContentType($id);
			
			$contentType->title = $this->htmlEntityDecode($contentType->title);

			$title = 'Delete Content Type';
			
			$subTitle = 'Content Types';
			
			$leadParagraph = 'Content Types belong to Categories.';
			
			return view('cp.contentTypes.delete', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'contentType'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Deletes a specific content type.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
	public function delete(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('delete_content_types')) {
			$contentType = $this->getContentType($id);
		
			DB::beginTransaction();

			try {
				$contentType->delete();
				
				$this->setCache($this->cacheKey, $this->getContentTypes());
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

			flash('Content Type deleted successfully.', $level = 'info');

			return redirect('/cp/content-types');
		}

		abort(403, 'Unauthorised action');
	}
}
