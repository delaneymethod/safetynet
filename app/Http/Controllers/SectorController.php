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
use App\Models\Sector;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use App\Http\Traits\{StatusTrait, SectorTrait, DepartmentTrait};

class SectorController extends Controller
{
	use StatusTrait, SectorTrait, DepartmentTrait;

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
		
		$this->cacheKey = 'sectors';
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
		
		if ($currentUser->hasPermission('view_sectors')) {
			$title = 'Sectors';
			
			$subTitle = '';
			
			$leadParagraph = 'Sectors belong to Departments.';
			
			$sectors = $this->getCache($this->cacheKey);
			
			if (is_null($sectors)) {
				$sectors = $this->getSectorsByOrder();
				
				$this->setCache($this->cacheKey, $sectors);
			}
			
			$this->mapImagesToAssets($sectors);
			
			$this->mapBannersToAssets($sectors);
			
			$sectors = $sectors->each(function($model) {
				$model->title = $this->htmlEntityDecode($model->title);
				$model->description = $this->htmlEntityDecode($model->description);
			});
			
			return view('cp.sectors.index', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'sectors'));
		}
		
		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Shows a form for creating a new sector.
	 *
	 * @params	Request 	$request
	 * @return 	Response
	 */
	public function create(Request $request)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('create_sectors')) {
			$title = 'Create Sector';
		
			$subTitle = 'Sectors';
			
			$leadParagraph = 'Sectors belong to Departments.';
			
			// Used to set status_id
			$statuses = $this->getData('getStatuses');
			
			// Used to set department_id
			$departments = $this->getData('getDepartments', 'departments');
			
			return view('cp.sectors.create', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'statuses', 'departments'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Creates a new sector.
	 *
	 * @params Request 	$request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$currentUser = $this->getAuthenticatedUser();

		if ($currentUser->hasPermission('create_sectors')) {
			// Remove any Cross-site scripting (XSS)
			$cleanedSector = $this->sanitizerInput($request->all());
			
			$rules = $this->getRules('sector');
			
			// Make sure all the input data is what we actually save
			$validator = $this->validatorInput($cleanedSector, $rules);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput();
			}

			DB::beginTransaction();
			
			try {
				// Create new model
				$sector = new Sector;
	
				// Set our field data
				$sector->title = $cleanedSector['title'];
				$sector->slug = $cleanedSector['slug'];
				$sector->description = $cleanedSector['description'];
				$sector->banner = $this->fixProtocol($cleanedSector['banner']);
				$sector->image = $this->fixProtocol($cleanedSector['image']);
				$sector->yammer = $cleanedSector['yammer'];
				$sector->stream = $cleanedSector['stream'];
				$sector->colour = $cleanedSector['colour'];
				$sector->status_id = $cleanedSector['status_id'];
				
				$sector->save();
				
				if (!empty($cleanedSector['department_ids'])) {
					$sector->setDepartments($cleanedSector['department_ids']);
				}
				
				$this->setCache($this->cacheKey, $this->getSectors());
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

			flash('Sector created successfully.', $level = 'success');

			return redirect('/cp/sectors');
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Shows a form for editing a sector.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
	public function edit(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('edit_sectors')) {
			$title = 'Edit Sector';
			
			$subTitle = 'Sectors';
			
			$leadParagraph = 'Sectors belong to Departments.';
			
			$sector = $this->getSector($id);
			
			$sector->title = $this->htmlEntityDecode($sector->title);
			$sector->description = $this->htmlEntityDecode($sector->description);
			
			$this->mapImagesToAssets($sector);
			
			$this->mapBannersToAssets($sector);
			
			// Used to set status_id
			$statuses = $this->getData('getStatuses');
			
			// Used to set department_id
			$departments = $this->getData('getDepartments', 'departments');
			
			return view('cp.sectors.edit', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'sector', 'statuses', 'departments'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Updates a specific sector.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
	public function update(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();

		if ($currentUser->hasPermission('edit_sectors')) {
			// Remove any Cross-site scripting (XSS)
			$cleanedSector = $this->sanitizerInput($request->all());
			
			$rules = $this->getRules('sector');
			
			// Make sure all the input data is what we actually save
			$validator = $this->validatorInput($cleanedSector, $rules);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput();
			}

			DB::beginTransaction();

			try {
				// Get our model
				$sector = $this->getSector($id);
				
				// Set our field data
				$sector->title = $cleanedSector['title'];
				$sector->slug = $cleanedSector['slug'];
				$sector->description = $cleanedSector['description'];
				$sector->banner = $this->fixProtocol($cleanedSector['banner']);
				$sector->image = $this->fixProtocol($cleanedSector['image']);
				$sector->yammer = $cleanedSector['yammer'];
				$sector->stream = $cleanedSector['stream'];
				$sector->colour = $cleanedSector['colour'];
				$sector->status_id = $cleanedSector['status_id'];
				$sector->updated_at = $this->datetime;
				
				$sector->save();
				
				if (!empty($cleanedSector['department_ids'])) {
					$sector->setDepartments($cleanedSector['department_ids']);
				}
				
				$this->setCache($this->cacheKey, $this->getSectors());
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

			flash('Sector updated successfully.', $level = 'success');

			return redirect('/cp/sectors');
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Shows a form for deleting a sector.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
	public function confirm(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('delete_sectors')) {
			$sector = $this->getSector($id);
			
			$sector->title = $this->htmlEntityDecode($sector->title);
			
			$title = 'Delete Sector';
			
			$subTitle = 'Sectors';
			
			$leadParagraph = 'Sectors belong to Departments.';
			
			return view('cp.sectors.delete', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'sector'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Deletes a specific sector.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
	public function delete(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('delete_sectors')) {
			$sector = $this->getSector($id);
		
			DB::beginTransaction();

			try {
				$sector->delete();
				
				$this->setCache($this->cacheKey, $this->getSectors());
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

			flash('Sector deleted successfully.', $level = 'info');

			return redirect('/cp/sectors');
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Sorts sectors.
	 *
	 * @params	Request 	$request
	 * @return 	Response
	 */
	public function sort(Request $request)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('edit_sectors')) {
			$cleanedOrder = $this->sanitizerInput($request->all());
			
			DB::beginTransaction();

			try {
				Sector::setNewOrder($cleanedOrder['order']);
				
				$this->setCache($this->cacheKey, $this->getSectors());
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
