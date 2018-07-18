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
use App\Models\Model;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use App\Http\Traits\{ModelTrait, StatusTrait, ProductTrait};

class ModelController extends Controller
{
	use ModelTrait, StatusTrait, ProductTrait;

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
		
		$this->cacheKey = 'models';
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
		
		if ($currentUser->hasPermission('view_models')) {
			$title = 'Models';
			
			$subTitle = '';
			
			$leadParagraph = 'Models belongs to Products in the Existing Products Sector.';
			
			$models = $this->getCache($this->cacheKey);
			
			if (is_null($models)) {
				$models = $this->getModels();
				
				$this->setCache($this->cacheKey, $models);
			}
			
			$this->mapImagesToAssets($models);
			
			$models = $models->each(function($model) {
				$model->title = $this->htmlEntityDecode($model->title);
			});
			
			return view('cp.models.index', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'models'));
		}
		
		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Shows a form for creating a new model.
	 *
	 * @params	Request 	$request
	 * @return 	Response
	 */
	public function create(Request $request)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('create_models')) {
			$title = 'Create Model';
		
			$subTitle = 'Models';
			
			$leadParagraph = 'Models belongs to Products in the Existing Products Sector.';
			
			// Used to set status_id
			$statuses = $this->getData('getStatuses', 'statuses');
			
			// Used to set product_id
			$products = $this->getData('getProducts', 'products');
			
			// Filter out existing product sector products only
			$products = $products->filter(function($product) {
				return in_array(6, $product->sectors->pluck('id')->toArray());
			});
			
			return view('cp.models.create', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'statuses', 'products'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Creates a new model.
	 *
	 * @params Request 	$request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$currentUser = $this->getAuthenticatedUser();

		if ($currentUser->hasPermission('create_models')) {
			// Remove any Cross-site scripting (XSS)
			$cleanedModel = $this->sanitizerInput($request->all());
			
			$rules = $this->getRules('model');
			
			// Make sure all the input data is what we actually save
			$validator = $this->validatorInput($cleanedModel, $rules);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput();
			}

			DB::beginTransaction();
			
			try {
				// Create new model
				$model = new Model;
	
				// Set our field data
				$model->title = $cleanedModel['title'];
				$model->slug = $cleanedModel['slug'];
				$model->minimum_number_of_units = $cleanedModel['minimum_number_of_units'];
				$model->status_id = $cleanedModel['status_id'];
				
				$model->save();
				
				if (!empty($cleanedModel['product_ids'])) {
					$model->setProducts($cleanedModel['product_ids']);
				}
				
				$this->setCache($this->cacheKey, $this->getModels());
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

			flash('Model created successfully.', $level = 'success');

			return redirect('/cp/models');
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Shows a form for editing a model.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
	public function edit(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('edit_models')) {
			$title = 'Edit Model';
			
			$subTitle = 'Models';
			
			$leadParagraph = 'Models belongs to Products in the Existing Products Sector.';
			
			$model = $this->getModel($id);
			
			$model->title = $this->htmlEntityDecode($model->title);
			
			// Used to set status_id
			$statuses = $this->getData('getStatuses', 'statuses');
			
			// Used to set product_id
			$products = $this->getData('getProducts', 'products');
			
			// Filter out existing product sector products only
			$products = $products->filter(function($product) {
				return in_array(6, $product->sectors->pluck('id')->toArray());
			});
			
			return view('cp.models.edit', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'model', 'statuses', 'products'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Updates a specific model.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
	public function update(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();

		if ($currentUser->hasPermission('edit_models')) {
			// Remove any Cross-site scripting (XSS)
			$cleanedModel = $this->sanitizerInput($request->all());
			
			$rules = $this->getRules('model');
			
			// Make sure all the input data is what we actually save
			$validator = $this->validatorInput($cleanedModel, $rules);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput();
			}

			DB::beginTransaction();

			try {
				// Get our model
				$model = $this->getModel($id);
				
				// Set our field data
				$model->title = $cleanedModel['title'];
				$model->slug = $cleanedModel['slug'];
				$model->minimum_number_of_units = $cleanedModel['minimum_number_of_units'];
				$model->status_id = $cleanedModel['status_id'];
				$model->updated_at = $this->datetime;
				
				$model->save();
				
				if (!empty($cleanedModel['product_ids'])) {
					$model->setProducts($cleanedModel['product_ids']);
				}
				
				$this->setCache($this->cacheKey, $this->getModels());
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

			flash('Model updated successfully.', $level = 'success');

			return redirect('/cp/models');
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Shows a form for deleting a model.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
	public function confirm(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('delete_models')) {
			$model = $this->getModel($id);
			
			$model->title = $this->htmlEntityDecode($model->title);
			
			$title = 'Delete Model';
			
			$subTitle = 'Models';
			
			$leadParagraph = 'Models belongs to Products in the Existing Products Sector.';
			
			return view('cp.models.delete', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'model'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Deletes a specific model.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
	public function delete(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('delete_models')) {
			$model = $this->getModel($id);
		
			DB::beginTransaction();

			try {
				$model->delete();
				
				$this->setCache($this->cacheKey, $this->getModels());
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

			flash('Model deleted successfully.', $level = 'info');

			return redirect('/cp/models');
		}

		abort(403, 'Unauthorised action');
	}
}
