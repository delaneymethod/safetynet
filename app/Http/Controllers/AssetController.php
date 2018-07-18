<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Http\Controllers;

use DB;
use Log;
use Storage;
use StorageHelper;
use App\Models\Asset;
use Illuminate\Http\Request;
use App\Http\Traits\AssetTrait;
use App\Http\Controllers\Controller;

class AssetController extends Controller
{
	use AssetTrait;
	
	private $urlPath;
	
	private $fileTypes;
	
	private $hiddenAssets;
	
	private $directoryUrl;
	
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->middleware('auth', [
			'except' => [
				'browse'
			]
		]);
		
		$this->middleware('auth.accessToken');
		
		$this->hiddenAssets = [
			'.gitkeep',
			'.DS_Store',
			'.gitignore',
			'supporting-files',
		];
		
		$this->urlPath = '/cp/assets';
		
		$this->directoryUrl = '?directory=';
		
		$this->fileTypes = config('cms.file_types');
	}
	
	/**
	 * Get assets view.
	 *
	 * @params	Request 	$request
	 * @return 	Response
	 */
   	public function index(Request $request)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('view_assets')) {
			$disk = StorageHelper::getDisk();
			
			$title = 'Assets';

			$subTitle = '';
			
			$leadParagraph = 'Assets will be uploaded to the <i>'.title_case($disk).'</i> storage disk.';
			
			$type = $request->get('type');
			
			$format = $request->get('format');
			
			// Filter based on type and/or format 
			if (!empty($type) && $type === 'image' && !empty($format) && $format === 'json') {
				$assets = $this->getAssets();
				
				$assets = $this->filterByImage($assets);
				
				$json = $this->convertToJson($assets, $type);
				
				return response()->json($json);
			} 
			
			// Filter based on format 	
			if (!empty($format) && $format === 'json') {
				$assets = $this->getAssets();
				
				$json = $this->convertToJson($assets);
				
				return response()->json($json);
			} 
			
			// List all assets	
			$download = $request->get('download');
				
			$directory = $request->get('directory') ?? '';
			
			if (!empty($directory)) {
				$exists = StorageHelper::exists($directory);
			
				if (!$exists) {
					abort(404);
				}
			}
			
			if (!empty($download)) {
				$this->downloadAssets($directory);
			}
			
			$assets = $this->getFilesAndFolders($directory);
			
			// If we are viewing top level folder, remove up icon.
			$deleteFolder = true;
			
			if (empty($directory) || $directory === DIRECTORY_SEPARATOR) {
				$deleteFolder = false;
			
				unset($assets['..']);
			}
		
			if (count($assets) > 0) {
				if (count($assets) == 1 && $assets['..']) {
					$deleteFolder = true;
				} else {
					$deleteFolder = false;
				}
				
				$assets = recursiveObject($assets);
			}
			
			$breadcrumbs = $this->getBreadcrumbs($directory);
		
			if (count($breadcrumbs) > 0) {
				$breadcrumbs = recursiveObject($breadcrumbs);
			}
		
			$directoryUrl = $this->directoryUrl;
			
			// dd($assets);
			
			return view('cp.assets.index', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'breadcrumbs', 'directory', 'directoryUrl', 'assets', 'deleteFolder'));
		}
		
		abort(403, 'Unauthorised action');
	}

	/**
	 * Get assets view file browser used in custom file pickers.
	 *
	 * @params	Request 	$request
	 * @return 	Response
	 */
	public function browse(Request $request)
	{
		$html = '';
		
		$directory = $request->get('directory') ?? '';
		
		if ($directory === DIRECTORY_SEPARATOR) {
			$directory = '';
		}
		
		$assets = $this->getFilesAndFolders($directory);
		
		unset($assets['..']);
			
		if (count($assets) > 0) {
			$html .= '<ul class="list-unstyled browse" style="display: none;">';
			
			foreach ($assets as $asset) {
				if ($asset['mod_time'] === null) {
					$html .= '<li class="directory collapsed"><a href="javascript:void(0);" rel="'.htmlentities($asset['file_path']).'/"><i class="fa '.$asset['icon_class'].'" aria-hidden="true"></i> '.htmlentities($asset['file_path']).'</a></li>';
				}
			}
			
			foreach ($assets as $asset) {
				if ($asset['mod_time'] !== null) {
					$html .= '<li class="file"><a href="javascript:void(0);" data-path="'.htmlentities($asset['path']).'" data-url="'.htmlentities($asset['url']).'" rel="'.htmlentities($asset['file_name']).'"><i class="fa fa-file '.$asset['icon_class'].'" aria-hidden="true"></i> '.htmlentities($asset['file_name']).'</a></li>';
				}
			}
							
			$html .= '</ul>';
		}
		
		return response($html, 200);
	}

	/**
	 * Shows a form for uploading files.
	 *
	 * @params	Request 	$request
	 * @return 	Response
	 */
   	public function upload(Request $request)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('upload_assets')) {
			$disk = StorageHelper::getDisk();
			
			$directory = $request->get('directory') ?? DIRECTORY_SEPARATOR;
		
			$title = 'Upload Assets';
		
			$subTitle = 'Assets';
			
			$leadParagraph = 'Assets will be uploaded to the <i>'.title_case($disk).'</i> storage disk.';
			
			return view('cp.assets.upload', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'directory'));
		}
		
		abort(403, 'Unauthorised action');
	}
	
	/**
     * Creates a new asset.
     *
	 * @params	Request 	$request
     * @return Response
     */
    public function store(Request $request)
    {
	    $currentUser = $this->getAuthenticatedUser();
	    
		if ($currentUser->hasPermission('upload_assets')) {
			// Remove any Cross-site scripting (XSS)
			$rules = [];
			
			$cleanedAssets = $this->sanitizerInput($request->all());
			
			// If its a single file upload ("file" instead of "files"), then user is uploading via modal window so we need to track this.
			$multiple = false;
			
			if (!empty($cleanedAssets['files'])) {
				$multiple = true;
			}
			
			// Request has come from Redactor Image Upload Plugin so we require some custom validation.
			if ($request->query('type') === 'image') {
				// Only allow files of specific extensions ['jpg', 'jpeg', 'png', 'gif'] and under 600MB
				$rules['file'] = 'required|file|max:600000|mimes:jpg,jpeg,png,gif';
			// Request has come from Redactor File Upload Plugin so we require some custom validation.	
			} else if ($request->query('type') === 'file') {
				$rules['file'] = 'required|file|max:600000';
			// Request has come from the Assets upload view so standard validation	
			} else {
				if ($multiple) {
					// Create some custom validation rules
					$files = count($cleanedAssets['files']) - 1;
			
					foreach (range(0, $files) as $index) {
						$rules['files.'.$index] = 'required|file|max:600000';
					}
				} else {
					$rules['file'] = 'required|file|max:600000';
				}
				
				$rules['directory'] = 'required|string|max:255';
			}
			
			// Make sure all the input data is what we actually save
			$validator = $this->validatorInput($cleanedAssets, $rules);
			
			if ($validator->fails()) {
				// Request has come from Redactor Image Upload Plugin so we require some custom validation.
				if ($request->query('type') === 'image' || $request->query('type') === 'file') {
					$messages = $validator->errors();
					
					$message = '';
					
					if ($messages->has('file')) {
						$message = $messages->get('file');
						
						$message = $message[0];
					}

					return response()->json([
						'error' => true,
						'message' => $message[0]
					]);
				} else {
					return back()->withErrors($validator)->withInput();
				}
			}
			
			DB::beginTransaction();
			
			if ($multiple) {
				$files = $cleanedAssets['files'];
			} else {
				// Keep structure consistent so push single asset into an array.
				$files = [];
				
				array_push($files, $cleanedAssets['file']);
			}
			
			try {
				$directory = $cleanedAssets['directory'];
				
				// Remove the leading slash
				if ($directory === DIRECTORY_SEPARATOR) {
					$directory = '';
				}
					
				$disk = StorageHelper::getDisk();
				
				$publicUrl = StorageHelper::getPublicUrl();
					
				foreach ($files as $file) {
					$fileName = $file->getClientOriginalName();
					
					$extension = $file->getClientOriginalExtension();
					
					$mimeType = $file->getClientMimeType();
					
					$size = $file->getClientSize();
					
					$name = str_replace('.'.$extension, '', $fileName);
					
					$width = null;
					
					$height = null;
					
					if (in_array($extension, ['png', 'jpg', 'jpeg', 'svg', 'gif'])) {
						list($width, $height) = getimagesize($file);
					}
					
					$fileName = $this->sanitizeFilename($fileName, $extension);
					
					$path = $fileName;
					
					if (!empty($directory)) {
						$path = str_replace('//', DIRECTORY_SEPARATOR, $directory.DIRECTORY_SEPARATOR.$fileName);
					}
					
					// Check if asset exists
					$asset = $this->getAssetByFileNameDiskAndPath($fileName, $disk, $path);
					
					// If the assets doesn't exist, then upload it
					if (empty($asset)) {
						// Create new model
						$asset = new Asset;
						
						StorageHelper::uploadFile($directory, $file, $fileName, $this->visibility);
					}
					
					if (in_array($extension, ['png', 'jpg', 'jpeg', 'svg', 'gif'])) {
						$focusPoint = [];
					
						$focusPoint['percentageX'] = '50%';
						$focusPoint['percentageY'] = '50%';
						$focusPoint['focusX'] = 0;
						$focusPoint['focusY'] = 0;
					
						// Update our field data
						$asset->focus_point = json_encode($focusPoint);
					} else {
						$asset->focus_point = null;
					}
					
					$asset->name = $name;
					$asset->file_name = $fileName;
					$asset->mime_type = $mimeType;
					$asset->disk = $disk;
					$asset->size = $size;
					$asset->width = $width;
					$asset->height = $height;
					$asset->extension = $extension;
					$asset->path = $path;
					
					$url = Storage::temporaryUrl($path, now()->addMinutes($this->temporaryUrlMinutes)); 
				
					$asset->url = $url;
				
					$asset->save();
				}
			} catch (QueryException $queryException) {
				DB::rollback();
			
				Log::info('SQL: '.$queryException->getSql());

				Log::info('Bindings: '.implode(', ', $queryException->getBindings()));
				
				if ($multiple) {
					abort(500, $queryException);
				} else {
					return response()->json([
						'error' => true,
						'queryException' => true,
						'message' => $queryException->getMessage()
					]);			
				}
			} catch (Exception $exception) {
				DB::rollback();
				
				if ($multiple) {
					abort(500, $exception);
				} else {
					return response()->json([
						'error' => true,
						'exception' => true,
						'message' => $exception->getMessage()
					]);
				}
			}	
					
			DB::commit();
			
			if ($multiple) {
				flash('Assets uploaded successfully.', $level = 'success');

				return redirect('/cp/assets?directory='.$directory);
			} else {
				$type = $request->get('type');
				
				if (!empty($type) && $type === 'image') {
					return response()->json([
						'id' => $asset->id,
						'url' => $asset->url,
					]);
				} else {
					return response()->json([
						'id' => $asset->id,
						'filename' => $asset->file_name,
						'filelink' => $asset->url,
					]);
				}
			}
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Shows a form for selecting an assets folder.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
	public function select(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('move_assets')) {
			$disk = StorageHelper::getDisk();
			
			$directory = $request->get('directory') ?? DIRECTORY_SEPARATOR;
		
			$directories = [];
			
			array_push($directories, [
				'path' => DIRECTORY_SEPARATOR,
				'depth' => 1,
			]);
			
			// Start at the root
			$folders = StorageHelper::getAllFolders('');
			
			$folders = $this->cleanUp($folders);
			
			// Remove current dir from the list
			foreach ($folders as $key => $folder) {
				if ($folder === $directory) {
					unset($folders[$key]);
				}
			}
			
			foreach ($folders as $folder) {
				$depth = count(explode(DIRECTORY_SEPARATOR, $folder));
					
				array_push($directories, [
					'path' => DIRECTORY_SEPARATOR.$folder,
					'depth' => $depth,
				]);
			}
			
			$directories = recursiveObject($directories);
			
			$asset = $this->getAsset($id);
			
			$title = 'Move Asset';
			
			$subTitle = 'Assets';
			
			$leadParagraph = 'Assets will be uploaded to the <i>'.title_case($disk).'</i> storage disk.';
			
			return view('cp.assets.move', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'asset', 'directory', 'directories'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Moves a specific asset.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
   	public function move(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('move_assets')) {
			// Remove any Cross-site scripting (XSS)
			$rules = [];
			
			$cleanedAssets = $this->sanitizerInput($request->all());

			$rules['new_path'] = 'required|string|max:255';
			
			// Make sure all the input data is what we actually save
			$validator = $this->validatorInput($cleanedAssets, $rules);
			
			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput();
			}

			$asset = $this->getAsset($id);
			
			$oldPath = $asset->path;
			
			// Basically chop off the leading slash
			if ($cleanedAssets['new_path'] === DIRECTORY_SEPARATOR) {
				$newPath = $asset->file_name;
			} else {
				$newPath = substr($cleanedAssets['new_path'], 1, strlen($cleanedAssets['new_path'])).DIRECTORY_SEPARATOR.$asset->file_name;
			}
			
			DB::beginTransaction();

			try {
				$asset->path = $newPath;
				
				$url = Storage::temporaryUrl($newPath, now()->addMinutes($this->temporaryUrlMinutes)); 
				
				$asset->url = $url;
				
				$asset->save();
				
				StorageHelper::moveFile($oldPath, $newPath);
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
				
			flash('Asset moved successfully.', $level = 'info');
			
			return redirect('/cp/assets');
		}
		
		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Shows a form for deleting an asset.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
	public function confirm(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('delete_assets')) {
			$disk = StorageHelper::getDisk();
				
			$asset = $this->getAsset($id);
				
			$title = 'Delete Asset';
			
			$subTitle = 'Assets';
			
			$leadParagraph = 'Assets will be uploaded to the <i>'.title_case($disk).'</i> storage disk.';
			
			return view('cp.assets.delete', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'asset'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Deletes a specific asset.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
   	public function delete(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('delete_assets')) {
			$asset = $this->getAsset($id);
			
			DB::beginTransaction();

			try {
				StorageHelper::deleteFile($asset->path);
				
				$asset->delete();
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

			flash('Asset deleted successfully.', $level = 'info');

			return redirect('/cp/assets');
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
     * Creates a new asset focus point.
     *
	 * @params	Request 	$request
     * @return Response
     */
	public function focusPoint(Request $request)
    {
	    $currentUser = $this->getAuthenticatedUser();
	    
		if ($currentUser->hasPermission('upload_assets')) {
			// Remove any Cross-site scripting (XSS)
			$rules = [];
			
			$cleanedAsset = $this->sanitizerInput($request->all());
			
			$rules['id'] = 'required|integer';
			$rules['percentageX'] = 'required|string';
			$rules['percentageY'] = 'required|string';
			$rules['focusX'] = 'required|numeric';
			$rules['focusY'] = 'required|numeric';
			
			// Make sure all the input data is what we actually save
			$validator = $this->validatorInput($cleanedAsset, $rules);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput();
			}
			
			DB::beginTransaction();
			
			try {
				$asset = $this->getAsset($cleanedAsset['id']);
				
				unset($cleanedAsset['id']);
				
				$asset->focus_point = json_encode($cleanedAsset);
					
				$asset->save();
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
				'message' => 'Focus point successfully saved.'
			]);
		} else {
			abort(403, 'Unauthorised action');
		}
	}
	
	/**
	 * Shows a form for creating an asset folder.
	 *
	 * @params	Request 	$request
	 * @return 	Response
	 */
	public function folderCreate(Request $request)
	{	
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('move_assets')) {
			$disk = StorageHelper::getDisk();
			
			$directory = $request->get('directory') ?? DIRECTORY_SEPARATOR;
				
			$title = 'Create Folder';
			
			$subTitle = 'Assets';
			
			$leadParagraph = 'Assets will be uploaded to the <i>'.title_case($disk).'</i> storage disk.';
			
			return view('cp.assets.folder.create', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'directory'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Creates a new asset folder.
	 *
	 * @params	Request 	$request
	 * @return 	Response
	 */
	public function folderStore(Request $request)
	{	
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('move_assets')) {
			// Remove any Cross-site scripting (XSS)
			$rules = [];
			
			$cleanedAssets = $this->sanitizerInput($request->all());
			
			$rules['directory'] = 'required|string|max:255';
			$rules['folder'] = 'required|string|max:255';
			
			// Make sure all the input data is what we actually save
			$validator = $this->validatorInput($cleanedAssets, $rules);
			
			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput();
			}
			
			if ($cleanedAssets['directory'] === DIRECTORY_SEPARATOR) {
				$path = $cleanedAssets['folder'];
			} else {
				$path = str_replace('//', DIRECTORY_SEPARATOR, $cleanedAssets['directory'].DIRECTORY_SEPARATOR.$cleanedAssets['folder']);
			}
			
			$path = strtolower($path);
			
			StorageHelper::createFolder($path);
			
			flash('Folder created successfully.', $level = 'success');
			
			return redirect('/cp/assets');
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Shows a form for deleting an folder.
	 *
	 * @params	Request 	$request
	 * @return 	Response
	 */
	public function folderConfirm(Request $request)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('move_assets')) {
			$disk = StorageHelper::getDisk();
			
			$directory = $request->get('directory') ?? DIRECTORY_SEPARATOR;
				
			$folders = explode(DIRECTORY_SEPARATOR, $directory);
			
			$folders = array_reverse($folders);
			
			$folder = $folders[0];
			
			$title = 'Delete Folder';
			
			$subTitle = 'Assets';
			
			$leadParagraph = 'Assets will be uploaded to the <i>'.title_case($disk).'</i> storage disk.';
			
			return view('cp.assets.folder.delete', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'folder', 'directory'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Deletes an asset folder.
	 *
	 * @params	Request 	$request
	 * @return 	Response
	 */
	public function folderDelete(Request $request)
	{	
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('move_assets')) {
			// Remove any Cross-site scripting (XSS)
			$rules = [];
			
			$cleanedAssets = $this->sanitizerInput($request->all());
			
			$rules['directory'] = 'required|string|max:255';
			$rules['folder'] = 'required|string|max:255';
			
			// Make sure all the input data is what we actually save
			$validator = $this->validatorInput($cleanedAssets, $rules);
			
			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput();
			}

			try {
				$directory = $cleanedAssets['directory'];
				
				StorageHelper::deleteFolder($directory);
				
				// Pull file structure from database
				$files = $this->getAssets();
				
				// Simple logic to only list files with path matching current directory
				$files = $files->filter(function ($file) use ($directory) {
					return $directory.DIRECTORY_SEPARATOR.$file->file_name === $file->path;
				});
				
				if (count($files) > 0) {
					foreach ($files as $file) {
						$file->delete();
					}
				}
			} catch (Exception $exception) {
				abort(500, $exception);
			}	
					
			flash('Folder deleted successfully.', $level = 'success');
			
			return redirect('/cp/assets');
		}

		abort(403, 'Unauthorised action');
	}
		
	/**
	 * Does what it says on the tin!
	 */
	private function convertToJson($assets, string $type = null) : array
	{
		$json = [];
		
		if (!empty($type) && $type === 'image') {
			foreach ($assets as $asset) {
				array_push($json, $asset);
			}
		} else {
			foreach ($assets as $asset) {
				array_push($json, [
					'id' => $asset->id,
					'title' => $asset->file_name,
					'name' => $asset->file_name,
					'url' => $asset->url,
					'size' => $this->getHumanSize($asset->size),
				]);
			}
		}
		
		return $json;
	}
	
	/**
	 * Does what it says on the tin!
	 */
	private function filterByImage($assets) : array
	{
		$images = [];
		
		$mimeTypes = [
			'image/jpg',
			'image/jpeg',
			'image/png',
			'image/gif',
		];
		
		$assets = $assets->whereIn('mime_type', $mimeTypes);
		
		foreach ($assets as $asset) {
			array_push($images, [
				'id' => $asset->id,
				'title' => $asset->file_name, 
				'thumb' => $asset->url, 
				'url' => $asset->url,
			]);
		}
		
		return $images;
	}
	
	private function cleanUp(array $assets) : array
	{
		foreach ($assets as $key => $asset) {
			foreach ($this->hiddenAssets as $hiddenAsset) {
				if (strpos($asset, $hiddenAsset) !== false) {
					unset($assets[$key]);
				}
			}
		}
		
		$assets = array_filter($assets, 'strlen');
		
		$assets = array_unique($assets);
		
		return array_values($assets);
	}
	
	private function getBreadcrumbs(string $directory) : array
	{
		$this->urlPath = '/cp/assets';
		
		$segments = explode(DIRECTORY_SEPARATOR, $directory);
	
		$breadcrumbs[] = [
			'title' => 'Assets',
			'url' => $this->urlPath,
		];
		
		$directoryPath = '';
		
		foreach ($segments as $key => $segment) {
			if ($segment != '.') {
				$directoryPath = empty($directoryPath) ? $segment : $directoryPath.DIRECTORY_SEPARATOR.$segment;
				
				if (!empty($segment)) {
					array_push($breadcrumbs, [
						'title' => title_case($segment),
						'url' => $this->urlPath.'?directory='.rawurlencode($directoryPath),
					]);
				}
			}
		}
		
		return $breadcrumbs;
	}
	
	private function getFilesAndFolders(string $directory) : array
	{
		$disk = StorageHelper::getDisk();
		
		$assets = [];
		
		if (!empty($directory)) {
			$segments = explode(DIRECTORY_SEPARATOR, $directory);
			
			unset($segments[count($segments) - 1]);
			
			$segments = implode(DIRECTORY_SEPARATOR, $segments);
			
			if (!empty($segments)) {
				$this->urlPath = $this->urlPath.$this->directoryUrl.rawurlencode($segments);
			}
				
			$this->directoryUrl = $this->directoryUrl.rawurlencode($directory);
			
			$assets['..'] = [
				'url_path' => $this->urlPath,
				'mod_time' => null,
				'file_path' => $this->urlPath,
				'file_size' => null,
				'icon_class' => 'fa-level-up',
			];
		}
		
		// Pull folder structure from Storage
		$folders = StorageHelper::getFolders($directory);
		
		$folders = $this->cleanUp($folders);
		
		if (count($folders) > 0) {
			foreach ($folders as $folder) {
				$filename = $this->getBaseName($folder);
				
				$assets[$filename] = [
					'url_path' => '/cp/assets?directory='.rawurlencode($folder),
					'mod_time' => null,
					'file_path' => $folder,
					'file_size' => null,
					'icon_class' => 'fa-folder',
				];
			}
		}
		
		// Pull file structure from database
		$files = $this->getAssets();
		
		$files = $files->filter(function ($file) use ($disk) {
			return $file->disk === $disk;
		});
		
		// Simple logic to only list files with path matching current directory
		if (!empty($directory)) {
			$directory = $directory.DIRECTORY_SEPARATOR;
		}
		
		// Browsing adds an extra slash at the end of paths 
		$directory = str_replace('//', DIRECTORY_SEPARATOR, $directory);
		
		$files = $files->filter(function ($file) use ($directory) {
			return $directory.$file->file_name === $file->path;
		});
		
		if (count($files) > 0) {
			foreach ($files as $file) {
				if (!empty($this->fileTypes[$file->extension])) {
					$iconClass = $this->fileTypes[$file->extension];
				} else {
					$iconClass = $this->fileTypes['blank'];
				}
				
				$filename = $file->file_name;
				
				$assets[$filename] = [
					'url_path' => $file->url,
					'mod_time' => $file->updated_at->timestamp,
					'file_path' => $file->path,
					'file_size' => $file->size,
					'icon_class' => $iconClass,
					'id' => $file->id,
					'name' => $file->name,
					'file_name' => $filename,
					'mime_type' => $file->mime_type,
					'disk' => $file->disk,
					'path' => $file->path,
					'url' => $file->url,
					'size' => $file->size,
					'human_size' => $this->getHumanSize($file->size),
					'extension' => $file->extension,
					'width' => $file->width,
					'height' => $file->height,
				];
				
				if (starts_with($file->mime_type, 'image')) {
					if (!empty($file->focus_point)) {
						$focusPoint = json_decode($file->focus_point, true);
					} else {
						$focusPoint = [
							'percentageX' => '50%',
							'percentageY' => '50%', 
							'focusX' => 0, 
							'focusY' => 0
						];
					}
					
					$assets[$filename]['focus_point'] = $focusPoint;
				}
			}
		}
		
		return $assets;
	}
}
