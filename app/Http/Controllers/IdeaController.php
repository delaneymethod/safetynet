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
use App\Models\Idea;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use App\Http\Traits\{IdeaTrait, StatusTrait, DepartmentTrait, SupportingFileTrait};

class IdeaController extends Controller
{
	use IdeaTrait, StatusTrait, DepartmentTrait, SupportingFileTrait;

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
		
		$this->cacheKey = 'ideas';
		
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
		
		if ($currentUser->hasPermission('view_ideas')) {
			$title = 'Ideas';
			
			$subTitle = '';
			
			$leadParagraph = 'Ideas belongs to Departments.';
			
			$ideas = $this->getCache($this->cacheKey);
			
			if (is_null($ideas)) {
				$ideas = $this->getIdeas();
				
				$this->setCache($this->cacheKey, $ideas);
			}
			
			$this->mapImagesToAssets($ideas);
			
			$this->mapBannersToAssets($ideas);
			
			$ideas = $ideas->each(function($model) {
				$model->title = $this->htmlEntityDecode($model->title);
				$model->description = $this->htmlEntityDecode($model->description);
			});
			
			return view('cp.ideas.index', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'ideas'));
		}
		
		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Shows a form for creating a new idea.
	 *
	 * @params	Request 	$request
	 * @return 	Response
	 */
	public function create(Request $request)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('create_ideas')) {
			$title = 'Create Idea';
		
			$subTitle = 'Ideas';
			
			$leadParagraph = 'Ideas belongs to Departments.';
			
			// Used to set status_id
			$statuses = $this->getData('getStatuses');
			
			// Used to set department_id
			$departments = $this->getData('getDepartments', 'departments');
			
			// Removes the Source department.
			$departments->forget(0);
			
			return view('cp.ideas.create', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'statuses', 'departments'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Shows a form for creating a new idea support file thumbnail.
	 *
	 * @params	Request 	$request
	 * @return 	Response
	 */
	public function createSupportingFileThumbnail(Request $request, int $id, int $supportingFileId)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('create_ideas')) {
			$title = 'Create Thumbnail';
		
			$subTitle = 'Supporting Files';
			
			$leadParagraph = '';
			
			return view('cp.ideas.supportingFiles.thumbnail.create', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'id', 'supportingFileId'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Creates a new ideas.
	 *
	 * @params Request 	$request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$currentUser = $this->getAuthenticatedUser();

		if ($currentUser->hasPermission('create_ideas')) {
			// Remove any Cross-site scripting (XSS)
			$cleanedIdea = $this->sanitizerInput($request->all());
			
			if ($cleanedIdea['new_idea']) {
				$cleanedIdea['slug'] = str_slug($cleanedIdea['title']);
			}
			
			$rules = $this->getRules('idea');
			
			// Make sure all the input data is what we actually save
			$validator = $this->validatorInput($cleanedIdea, $rules);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput();
			}

			DB::beginTransaction();
			
			try {
				// Create new model
				$idea = new Idea;
	
				// Set our field data
				$idea->title = $cleanedIdea['title'];
				$idea->slug = $cleanedIdea['slug'];
				$idea->description = $cleanedIdea['redactor'];
				$idea->submitted_by = $cleanedIdea['submitted_by'];
				
				if (!$cleanedIdea['new_idea']) {
					$idea->image = $this->fixProtocol($cleanedIdea['image']);
				}
				
				$idea->status_id = $cleanedIdea['status_id'];
				
				$idea->save();
				
				if (!empty($cleanedIdea['department_ids'])) {
					$idea->setDepartments($cleanedIdea['department_ids']);
				}
				
				if (!empty($cleanedIdea['supporting_files'])) {
					foreach ($cleanedIdea['supporting_files'] as $supportingFile) {
						$this->addSupportingFile($idea, $supportingFile);
					}
				}
				
				$this->setCache($this->cacheKey, $this->getIdeas());
				
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
			
			if ($cleanedIdea['new_idea']) {
				flash('Idea submitted successfully.', $level = 'success');

				return redirect('/nexo/ideas');
			} else {
				flash('Idea created successfully.', $level = 'success');

				return redirect('/cp/ideas');
			}
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Creates a new idea supporting file thumbnail.
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

		if ($currentUser->hasPermission('create_ideas')) {
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
			
			return redirect('/cp/ideas/'.$id.'/edit');
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Shows a form for editing a idea.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
	public function edit(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('edit_ideas')) {
			$title = 'Edit Idea';
			
			$subTitle = 'Ideas';
			
			$leadParagraph = 'Ideas belongs to Departments.';
			
			$idea = $this->getIdea($id);
			
			$idea->title = $this->htmlEntityDecode($idea->title);
			$idea->description = $this->htmlEntityDecode($idea->description);
			
			$this->mapImagesToAssets($idea);
			
			$this->mapBannersToAssets($idea);
			
			$supportingFiles = $idea->getMedia();
			
			$supportingFiles = $supportingFiles->filter(function ($supportingFile) {
				return $supportingFile->disk === $this->disk;
			});
			
			$supportingFiles = $supportingFiles->sortByDesc('created_at');
			
			// Used to set status_id
			$statuses = $this->getData('getStatuses');
			
			// Used to set department_id
			$departments = $this->getData('getDepartments', 'departments');
			
			// Removes the Source department.
			$departments->forget(0);
			
			$allowSupportingFileDownload = $this->allowSupportingFileDownload;
			
			return view('cp.ideas.edit', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'idea', 'supportingFiles', 'statuses', 'departments', 'allowSupportingFileDownload'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Updates a specific idea.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
	public function update(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();

		if ($currentUser->hasPermission('edit_ideas')) {
			// Remove any Cross-site scripting (XSS)
			$cleanedIdea = $this->sanitizerInput($request->all());
			
			$rules = $this->getRules('idea');
			
			// Make sure all the input data is what we actually save
			$validator = $this->validatorInput($cleanedIdea, $rules);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput();
			}

			DB::beginTransaction();

			try {
				// Get our model
				$idea = $this->getIdea($id);
				
				// Set our field data
				$idea->title = $cleanedIdea['title'];
				$idea->slug = $cleanedIdea['slug'];
				$idea->description = $cleanedIdea['redactor'];
				$idea->image = $this->fixProtocol($cleanedIdea['image']);
				$idea->status_id = $cleanedIdea['status_id'];
				$idea->submitted_by = $cleanedIdea['submitted_by'];
				$idea->updated_at = $this->datetime;
				
				$idea->save();
				
				if (!empty($cleanedIdea['department_ids'])) {
					$idea->setDepartments($cleanedIdea['department_ids']);
				}
				
				if (!empty($cleanedIdea['supporting_files'])) {
					foreach ($cleanedIdea['supporting_files'] as $supportingFile) {
						$this->addSupportingFile($idea, $supportingFile);
					}
				}
								
				$this->setCache($this->cacheKey, $this->getIdeas());
			
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
			
			flash('Idea updated successfully.', $level = 'success');

			return redirect('/cp/ideas');
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Shows a form for deleting a idea.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
	public function confirm(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('delete_ideas')) {
			$idea = $this->getIdea($id);
			
			$idea->title = $this->htmlEntityDecode($idea->title);
			
			$title = 'Delete Idea';
			
			$subTitle = 'Ideas';
			
			$leadParagraph = 'Ideas belongs to Departments.';
			
			return view('cp.ideas.delete', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'idea'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Shows a form for deleting a ideas supporting file.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @param	int			$supportingFileId
	 * @return 	Response
	 */
	public function confirmSupportingFile(Request $request, int $id, int $supportingFileId)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('delete_ideas')) {
			$idea = $this->getIdea($id);
			
			$supportingFiles = $idea->getMedia();
			
			$supportingFile = $supportingFiles->filter(function ($supportingFile) use ($supportingFileId) {
				return $supportingFile->disk === $this->disk && $supportingFile->id === $supportingFileId;
			})->first();
			
			$title = 'Delete Supporting File';
			
			$subTitle = $idea->title;
			
			$leadParagraph = 'Ideas belongs to Departments.';
			
			return view('cp.ideas.supportingFiles.delete', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'idea', 'supportingFile'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Shows a form for deleting a ideas supporting file thumbnail.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @param	int			$supportingFileId
	 * @return 	Response
	 */
	public function confirmSupportingFileThumbnail(Request $request, int $id, int $supportingFileId)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('delete_ideas')) {
			$title = 'Delete Thumbnail';
			
			$subTitle = 'Supporting File';
			
			$leadParagraph = '';
			
			return view('cp.ideas.supportingFiles.thumbnail.delete', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'id', 'supportingFileId'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Deletes a specific idea.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
	public function delete(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('delete_ideas')) {
			$idea = $this->getIdea($id);
		
			DB::beginTransaction();

			try {
				$supportingFiles = $idea->getMedia();
				
				$supportingFiles->each(function ($supportingFile) use ($request, $id) {
					$this->deleteSupportingFile($request, $id, $supportingFile->id);
				});
			
				// Makes sure we also delete all Media Library entries.
				$idea->forceDelete();
				
				$this->setCache($this->cacheKey, $this->getIdeas());
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

			flash('Idea deleted successfully.', $level = 'info');

			return redirect('/cp/ideas');
		}

		abort(403, 'Unauthorised action');
	}
	
	public function addSupportingFile(Idea $idea, UploadedFile $supportingFile)
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
		
		$idea->addMedia($supportingFile)->setFileName($fileName)->setName($name)->withCustomProperties($customProperties)->toMediaCollection();
	}
	
	/**
	 * Deletes a specific ideas supporting file.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @param	int			$supportingFileId
	 * @return 	Response
	 */
	public function deleteSupportingFile(Request $request, int $id, int $supportingFileId)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('delete_ideas')) {
			$idea = $this->getIdea($id);
			
			$supportingFiles = $idea->getMedia();
			
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

			return redirect('/cp/ideas/'.$id.'/edit');
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Deletes a specific idea supporting file thumbnail.
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
		
		if ($currentUser->hasPermission('delete_ideas')) {
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

			return redirect('/cp/ideas/'.$id.'/edit');
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Downloads a specific ideas supporting file.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
	public function downloadSupportingFile(Request $request, int $id, int $supportingFileId)
	{
		$currentUser = $this->getAuthenticatedUser();
			
		if ($currentUser->hasPermission('view_ideas') && $this->allowSupportingFileDownload) {
			$idea = $this->getIdea($id);
			
			$supportingFiles = $idea->getMedia();
			
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
