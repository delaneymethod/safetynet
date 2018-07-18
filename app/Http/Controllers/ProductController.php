<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Http\Controllers;

use DB;
use Log;
use Artisan;
use Exception;
use StorageHelper;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use App\Http\Traits\{StatusTrait, SectorTrait, ProductTrait, CategoryTrait, ContentTypeTrait, DepartmentTrait, SupportingFileTrait};

class ProductController extends Controller
{
	use StatusTrait, SectorTrait, ProductTrait, CategoryTrait, ContentTypeTrait, DepartmentTrait, SupportingFileTrait;
	
	protected $disk;
	
	protected $allowSupportingFileDownload;
	
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
		
		$this->cacheKey = 'products';
		
		$this->disk = StorageHelper::getDisk();
		
		$driver = config('filesystems.disks.'.$this->disk.'.driver');
		
		$this->allowSupportingFileDownload = $driver !== 'sharepoint' ? true : false;
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
		
		if ($currentUser->hasPermission('view_products')) {
			$title = 'Products';
			
			$subTitle = '';
			
			$leadParagraph = 'Products belongs to Content Types, Categories, Sectors and Departments.';
			
			$products = $this->getCache($this->cacheKey);
			
			if (is_null($products)) {
				$products = $this->getProducts();
				
				$this->setCache($this->cacheKey, $products);
			}
			
			$this->mapImagesToAssets($products);
			
			$this->mapBannersToAssets($products);
			
			$products = $products->each(function($model) {
				$model->title = $this->htmlEntityDecode($model->title);
				$model->description = $this->htmlEntityDecode($model->description);
			});
			
			return view('cp.products.index', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'products'));
		}
		
		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Shows a form for creating a new product.
	 *
	 * @params	Request 	$request
	 * @return 	Response
	 */
	public function create(Request $request)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('create_products')) {
			$title = 'Create Product';
		
			$subTitle = 'Products';
			
			$leadParagraph = 'Products belongs to Content Types, Categories, Sectors and Departments.';
			
			// Used to set status_id
			$statuses = $this->getData('getStatuses');
			
			// Used to set department_id
			$departments = $this->getData('getDepartments', 'departments');
			
			// Used to set sector_id
			$sectors = $this->getData('getSectors', 'sectors');
			
			// Used to set category_id
			$categories = $this->getData('getCategories', 'categories');
			
			// Used to set content_type_id
			$contentTypes = $this->getData('getContentTypes', 'contentTypes');
			
			return view('cp.products.create', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'statuses', 'departments', 'sectors', 'categories', 'contentTypes'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Shows a form for creating a new product support file thumbnail.
	 *
	 * @params	Request 	$request
	 * @return 	Response
	 */
	public function createSupportingFileThumbnail(Request $request, int $id, int $supportingFileId)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('create_products')) {
			$title = 'Create Thumbnail';
		
			$subTitle = 'Supporting Files';
			
			$leadParagraph = '';
			
			return view('cp.products.supportingFiles.thumbnail.create', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'id', 'supportingFileId'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Creates a new product.
	 *
	 * @params Request 	$request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$currentUser = $this->getAuthenticatedUser();

		if ($currentUser->hasPermission('create_products')) {
			// Remove any Cross-site scripting (XSS)
			$cleanedProduct = $this->sanitizerInput($request->all());
			
			$rules = $this->getRules('product');
			
			// Make sure all the input data is what we actually save
			$validator = $this->validatorInput($cleanedProduct, $rules);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput();
			}

			DB::beginTransaction();
			
			try {
				// Create new model
				$product = new Product;
	
				// Set our field data
				$product->title = $cleanedProduct['title'];
				$product->slug = $cleanedProduct['slug'];
				$product->description = $cleanedProduct['description'];
				$product->banner = $this->fixProtocol($cleanedProduct['banner']);
				$product->image = $this->fixProtocol($cleanedProduct['image']);
				$product->video = $this->fixProtocol($cleanedProduct['video']);
				$product->overview = $cleanedProduct['overview'];
				$product->due_date = $cleanedProduct['due_date'];
				$product->minimum_number_of_units = $cleanedProduct['minimum_number_of_units'];
				$product->npd_feedback_recipient = $cleanedProduct['npd_feedback_recipient'];
				$product->ex_feedback_recipient = $cleanedProduct['ex_feedback_recipient'];
				$product->status_id = $cleanedProduct['status_id'];
				
				$product->save();
				
				if (!empty($cleanedProduct['department_ids'])) {
					$product->setDepartments($cleanedProduct['department_ids']);
				}
				
				if (!empty($cleanedProduct['sector_ids'])) {
					$product->setSectors($cleanedProduct['sector_ids']);
				}
				
				if (!empty($cleanedProduct['category_ids'])) {
					$product->setCategories($cleanedProduct['category_ids']);
				}
				
				if (!empty($cleanedProduct['content_type_ids'])) {
					$product->setContentTypes($cleanedProduct['content_type_ids']);
				}
				
				if (!empty($cleanedProduct['supporting_files'])) {
					foreach ($cleanedProduct['supporting_files'] as $supportingFile) {
						$this->addSupportingFile($product, $supportingFile);
					}
				}
				
				$this->setCache($this->cacheKey, $this->getProducts());
			
				Artisan::queue('storage:get-temporary-urls')->onQueue('default');
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

			// Gives the storage provider/helper time to upload the file and generate the access urls
			sleep(2);
			
			flash('Product created successfully.', $level = 'success');

			return redirect('/cp/products');
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Creates a new product supporting file thumbnail.
	 *
	 * @params Request 	$request
	 * @param	int			$id
	 * @param	int			$supportingFileId
	 * @param	int			$supportingFileThumbnailId
	 * @return Response
	 */
	public function storeSupportingFileThumbnail(Request $request, int $id, int $supportingFileId)
	{
		$currentUser = $this->getAuthenticatedUser();

		if ($currentUser->hasPermission('create_products')) {
			// Remove any Cross-site scripting (XSS)
			$cleanedThumnbail = $this->sanitizerInput($request->all());
			
			$rules['file'] = 'required|file|max:600000|mimes:jpg,jpeg,png,gif';
			
			// Make sure all the input data is what we actually save
			$validator = $this->validatorInput($cleanedThumnbail, $rules);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput();
			}

			DB::beginTransaction();
			
			try {
				$file = $cleanedThumnbail['file'];
				
				$directory = 'supporting-files/'.$supportingFileId;
				
				$fileName = 'thumbnail';
					
				$extension = $file->getClientOriginalExtension();
				
				$mimeType = $file->getClientMimeType();
				
				$size = $file->getClientSize();
				
				$name = str_replace('.'.$extension, '', $fileName);
				
				$fileName = $this->sanitizeFilename($fileName, $extension);
				
				$thumbnail = [];
					
				$thumbnail['fileName'] = $fileName;
				$thumbnail['name'] = $name;
				$thumbnail['mimeType'] = $mimeType;
				$thumbnail['extension'] = $extension;
				$thumbnail['size'] = $size;
				
				$supportingFile = $this->getSupportingFile($supportingFileId);
				
				$customProperties = $supportingFile->custom_properties;
				
				$customProperties['thumbnail'] = $thumbnail;
				
				$supportingFile->custom_properties = $customProperties;
				
				StorageHelper::uploadFile($directory, $file, $fileName, $this->visibility);
				
				$supportingFile->save();
				
				Artisan::queue('storage:get-temporary-urls')->onQueue('default');
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

			// Gives the storage provider/helper time to upload the file and generate the access urls
			sleep(2);
			
			flash('Supporting File Thumbnail created successfully.', $level = 'success');
			
			return redirect('/cp/products/'.$id.'/edit');
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Shows a form for editing a product.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
	public function edit(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('edit_products')) {
			$title = 'Edit Product';
			
			$subTitle = 'Products';
			
			$leadParagraph = 'Products belongs to Content Types, Categories, Sectors and Departments.';
			
			$product = $this->getProduct($id);
			
			$product->title = $this->htmlEntityDecode($product->title);
			$product->description = $this->htmlEntityDecode($product->description);
			
			$this->mapImagesToAssets($product);
			
			$this->mapBannersToAssets($product);
			
			$supportingFiles = $product->getMedia();
			
			$supportingFiles = $supportingFiles->filter(function ($supportingFile) {
				return $supportingFile->disk === $this->disk;
			});
			
			$supportingFiles = $supportingFiles->sortByDesc('created_at');
			
			// Used to set status_id
			$statuses = $this->getData('getStatuses');
			
			// Used to set department_id
			$departments = $this->getData('getDepartments', 'departments');
			
			// Used to set sector_id
			$sectors = $this->getData('getSectors', 'sectors');
			
			// Used to set category_id
			$categories = $this->getData('getCategories', 'categories');
			
			// Used to set content_type_id
			$contentTypes = $this->getData('getContentTypes', 'contentTypes');
			
			$allowSupportingFileDownload = $this->allowSupportingFileDownload;
			
			return view('cp.products.edit', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'product', 'supportingFiles', 'statuses', 'departments', 'sectors', 'categories', 'contentTypes', 'allowSupportingFileDownload'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Updates a specific product.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
	public function update(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();

		if ($currentUser->hasPermission('edit_products')) {
			// Remove any Cross-site scripting (XSS)
			$cleanedProduct = $this->sanitizerInput($request->all());
			
			$rules = $this->getRules('product');
			
			// Make sure all the input data is what we actually save
			$validator = $this->validatorInput($cleanedProduct, $rules);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput();
			}
			
			DB::beginTransaction();

			try {
				// Get our model
				$product = $this->getProduct($id);
				
				// Set our field data
				$product->title = $cleanedProduct['title'];
				$product->slug = $cleanedProduct['slug'];
				$product->description = $cleanedProduct['description'];
				$product->banner = $this->fixProtocol($cleanedProduct['banner']);
				$product->image = $this->fixProtocol($cleanedProduct['image']);
				$product->video = $this->fixProtocol($cleanedProduct['video']);
				$product->overview = $cleanedProduct['overview'];
				$product->due_date = $cleanedProduct['due_date'];
				$product->minimum_number_of_units = $cleanedProduct['minimum_number_of_units'];
				$product->npd_feedback_recipient = $cleanedProduct['npd_feedback_recipient'];
				$product->ex_feedback_recipient = $cleanedProduct['ex_feedback_recipient'];
				$product->status_id = $cleanedProduct['status_id'];
				$product->updated_at = $this->datetime;
				
				$product->save();
				
				if (!empty($cleanedProduct['department_ids'])) {
					$product->setDepartments($cleanedProduct['department_ids']);
				}
				
				if (!empty($cleanedProduct['sector_ids'])) {
					$product->setSectors($cleanedProduct['sector_ids']);
				}
				
				if (!empty($cleanedProduct['category_ids'])) {
					$product->setCategories($cleanedProduct['category_ids']);
				}
				
				if (!empty($cleanedProduct['content_type_ids'])) {
					$product->setContentTypes($cleanedProduct['content_type_ids']);
				}
				
				if (!empty($cleanedProduct['supporting_files'])) {
					foreach ($cleanedProduct['supporting_files'] as $supportingFile) {
						$this->addSupportingFile($product, $supportingFile);
					}
				}
				
				$this->setCache($this->cacheKey, $this->getProducts());
				
				Artisan::queue('storage:get-temporary-urls')->onQueue('default');
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

			// Gives the storage provider/helper time to upload the file and generate the access urls
			sleep(2);
			
			flash('Product updated successfully.', $level = 'success');

			return redirect('/cp/products');
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Shows a form for deleting a product.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
	public function confirm(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('delete_products')) {
			$product = $this->getProduct($id);
			
			$title = 'Delete Product';
			
			$subTitle = 'Products';
			
			$leadParagraph = 'Products belongs to Content Types, Categories, Sectors and Departments.';
			
			return view('cp.products.delete', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'product'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Shows a form for deleting a products supporting file.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @param	int			$supportingFileId
	 * @return 	Response
	 */
	public function confirmSupportingFile(Request $request, int $id, int $supportingFileId)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('delete_products')) {
			$product = $this->getProduct($id);
			
			$supportingFiles = $product->getMedia();
			
			$supportingFile = $supportingFiles->filter(function ($supportingFile) use ($supportingFileId) {
				return $supportingFile->disk === $this->disk && $supportingFile->id === $supportingFileId;
			})->first();
			
			$title = 'Delete Supporting File';
			
			$subTitle = $product->title;
			
			$leadParagraph = 'Products belongs to Content Types, Categories, Sectors and Departments.';
			
			return view('cp.products.supportingFiles.delete', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'product', 'supportingFile'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Shows a form for deleting a products supporting file thumbnail.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @param	int			$supportingFileId
	 * @return 	Response
	 */
	public function confirmSupportingFileThumbnail(Request $request, int $id, int $supportingFileId)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('delete_products')) {
			$title = 'Delete Thumbnail';
			
			$subTitle = 'Supporting File';
			
			$leadParagraph = '';
			
			return view('cp.products.supportingFiles.thumbnail.delete', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'id', 'supportingFileId'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Deletes a specific product.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
	public function delete(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('delete_products')) {
			$product = $this->getProduct($id);
		
			DB::beginTransaction();

			try {
				$supportingFiles = $product->getMedia();
				
				$supportingFiles->each(function ($supportingFile) use ($request, $id) {
					$this->deleteSupportingFile($request, $id, $supportingFile->id);
				});
			
				// Makes sure we also delete all Media Library entries.
				$product->forceDelete();
				
				$this->setCache($this->cacheKey, $this->getProducts());
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

			flash('Product deleted successfully.', $level = 'info');

			return redirect('/cp/products');
		}

		abort(403, 'Unauthorised action');
	}
	
	public function addSupportingFile(Product $product, UploadedFile $supportingFile)
	{
		$width = null;
						
		$height = null;
		
		$fileName = $supportingFile->getClientOriginalName();
		
		$extension = $supportingFile->getClientOriginalExtension();
		
		$name = str_replace('.'.$extension, '', $fileName);
		
		$fileName = $this->sanitizeFilename($fileName, $extension);
		
		$customProperties = [
			'extension' => $extension,
		];
		
		if (starts_with($supportingFile->getClientMimeType(), 'image')) {
			list($width, $height) = getimagesize($supportingFile);
			
			$customProperties['dimensions'] = [
				'width' => $width,
				'height' => $height,
			];
		}
		
		$product->addMedia($supportingFile)->setFileName($fileName)->setName($name)->withCustomProperties($customProperties)->toMediaCollection();
	}
	
	/**
	 * Deletes a specific products supporting file.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @param	int			$supportingFileId
	 * @return 	Response
	 */
	public function deleteSupportingFile(Request $request, int $id, int $supportingFileId)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('delete_products')) {
			$product = $this->getProduct($id);
			
			$supportingFiles = $product->getMedia();
			
			$supportingFile = $supportingFiles->filter(function ($supportingFile) use ($supportingFileId) {
				return $supportingFile->disk === $this->disk && $supportingFile->id === $supportingFileId;
			})->first();
				
			DB::beginTransaction();
			
			try {
				// This is a hack to delete the conversions folder and file first due to how deleting files/folders on Sharepoint works.
				StorageHelper::deleteFile('supporting-files/'.$supportingFile->id.'/'.$supportingFile->file_name);
				
				StorageHelper::deleteFolder('supporting-files/'.$supportingFile->id.'/conversions');
				
				StorageHelper::deleteFolder('supporting-files/'.$supportingFile->id);
				
				DB::delete('DELETE FROM supporting_files WHERE id = '.$supportingFile->id);
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

			flash('Supporting File deleted successfully.', $level = 'info');

			return redirect('/cp/products/'.$id.'/edit');
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Deletes a specific products supporting file thumbnail.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @param	int			$supportingFileId
	 * @param	int			$supportingFileThumbnailId
	 * @return 	Response
	 */
	public function deleteSupportingFileThumbnail(Request $request, int $id, int $supportingFileId)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('delete_products')) {
			$supportingFile = $this->getSupportingFile($supportingFileId);
			
			DB::beginTransaction();
			
			try {
				StorageHelper::deleteFile('supporting-files/'.$supportingFile->id.'/'.$supportingFile->custom_properties['thumbnail']['fileName']);
				
				$customProperties = $supportingFile->custom_properties;
				
				unset($customProperties['thumbnail']);
				
				$supportingFile->custom_properties = $customProperties;
				
				$supportingFile->save();
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

			flash('Supporting File Thumbnail deleted successfully.', $level = 'info');

			return redirect('/cp/products/'.$id.'/edit');
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Downloads a specific products supporting file.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
	public function downloadSupportingFile(Request $request, int $id, int $supportingFileId)
	{
		$currentUser = $this->getAuthenticatedUser();
			
		if ($currentUser->hasPermission('view_products') && $this->allowSupportingFileDownload) {
			$product = $this->getProduct($id);
			
			$supportingFiles = $product->getMedia();
			
			$supportingFile = $supportingFiles->filter(function ($supportingFile) use ($supportingFileId) {
				return $supportingFile->disk === $this->disk && $supportingFile->id === $supportingFileId;
			})->first();
			
			$path = $supportingFile->getPath();
			
			$diskRootPath = StorageHelper::getDiskRootPath();
			
			return response()->download($diskRootPath.DIRECTORY_SEPARATOR.$path);
		}
		
		abort(403, 'Unauthorised action');
	}
}
