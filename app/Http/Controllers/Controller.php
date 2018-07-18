<?php
/**
 * @link	  https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license	  https://www.delaneymethod.com/cms/license
 */

namespace App\Http\Controllers;

use DB;
use Log;
use Request;
use App\User;
use stdClass;
use Carbon\Carbon;
use StorageHelper;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Validator as ValidatorResponse;
use Illuminate\Support\Facades\{File, Auth, Cache, Validator};
use Illuminate\Support\Collection as SupportCollectionResponse;
use App\Http\Traits\{UserTrait, AssetTrait, SupportingFileTrait};
use Illuminate\Database\Eloquent\Collection as EloquentCollectionResponse;

class Controller extends BaseController
{
	use UserTrait, AssetTrait, DispatchesJobs, AuthorizesRequests, ValidatesRequests, SupportingFileTrait;
	
	protected $env;
	
	protected $limit;

	public $visibility;
	
	protected $minutes;
	
	protected $datetime;
	
	protected $maxLimit;
	
	protected $cacheKey;
	
	protected $cachingEnabled;
	
	protected $httpStatusCode;
	
	public $temporaryUrlMinutes;
	
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->limit = 100;
		
		$this->cacheKey = '';
		
		$this->maxLimit = 100;
		
		$this->env = env('APP_ENV');
		
		$this->httpStatusCode = 200;
		
		$this->visibility = 'private';
		
		$this->temporaryUrlMinutes = 4320; // 72 hours
		
		$this->cachingEnabled = config('cache.enabled');
		
		$this->minutes = config('cache.expiry_in_minutes');
		
		$this->datetime = Carbon::now()->format('Y-m-d H:i:s');
	
		$this->checkDBConnection();
	}
	
	/**
	 * Checks if we have a current database connection and reconnects if required.
	 */
	private function checkDBConnection()
	{
		try {
			DB::connection()->getDatabaseName();
		} catch (PDOException $pdoException) {
			DB::reconnect(); 
		}
	}
	
	public function htmlEntityDecode($value) 
	{
		if (!empty($value)) {
			return html_entity_decode($value, ENT_QUOTES, 'UTF-8');
		} else {
			return $value;
		}
	}

	/**
	 * Does what it says on the tin!
	 */
	public function parseDate($date, $format) : string
	{
		return Carbon::parse($date)->format($format);	
	}
	
	/**
	 * Does what it says on the tin!
	 */
	public function getBaseName(string $path) : string 
	{
		return strtolower(pathinfo($path, PATHINFO_BASENAME));
	}
	
	/**
	 * Does what it says on the tin!
	 */
	public function getExtension(string $path) : string 
	{
		return strtolower(pathinfo($path, PATHINFO_EXTENSION));
	}
	
	/**
	 * Does what it says on the tin!
	 */
	public function getHumanSize(int $bytes) : string 
	{
		$i = floor(log($bytes, 1024));
		
		return round($bytes / pow(1024, $i), [0, 0, 2, 2, 3][$i]).['B', 'kB', 'MB', 'GB', 'TB'][$i];
	}
	
	/**
	 * Does what it says on the tin!
	 */
	public function sanitizeFilename(string $filename, string $extension) : string
	{		
		return str_slug(str_replace($extension, '', $filename), '-').'.'.$extension;
	}
	
	/**
	 * Does what it says on the tin!
	 */
	public function generateTitle($modelRelations, string $modelTitle) : string
	{
		// Loop over all relations and add its title to an array
		$titles = $modelRelations->map->title->toArray();
			
		// Add title itself to the array	
		array_push($titles, $modelTitle);
			
		$titles = array_reverse($titles);
		
		return implode(' - ', $titles);
	}
		
	/**
	 * Does what it says on the tin!
	 */
	public function generateKeywords(string $string) : string
	{
		$stopWords = ['i', 'a', 'about', 'an', 'and', 'are', 'as', 'at', 'be', 'by', 'com', 'de', 'en', 'for', 'from', 'how', 'in', 'is', 'it', 'la', 'of', 'on', 'or', 'that', 'the', 'this', 'to', 'was', 'what', 'when', 'where', 'who', 'will', 'with', 'und', 'the', 'www'];
	
		// Replace whitespace
		$string = preg_replace('/\s\s+/i', '', $string);		
		
		$string = trim($string);
		
		// Only take alphanumerical characters, but keep the spaces and dashes too
		$string = preg_replace('/[^a-zA-Z0-9 -]/', '', $string); 
		
		$string = strtolower($string);
	
		preg_match_all('/\b.*?\b/i', $string, $matchWords);
		
		$matchWords = $matchWords[0];
	
		foreach ($matchWords as $key => $item) {
			if ($item == '' || in_array(strtolower($item), $stopWords) || strlen($item) <= 3) {
				unset($matchWords[$key]);
			}
		}
		
		$wordCountArr = [];
		
		if (is_array($matchWords)) {
			foreach ($matchWords as $key => $val) {
				$val = strtolower($val);
				
				if (isset($wordCountArr[$val])) {
					$wordCountArr[$val]++;
				} else {
					$wordCountArr[$val] = 1;
				}
			}
		}
		
		arsort($wordCountArr);
		
		$wordCountArr = array_slice($wordCountArr, 0, 10);
		
		return implode(',', array_keys($wordCountArr));
	}
	
	protected function uploadSupportingFiles(array $formData) : array
	{
		if (!empty($formData['supporting_files'])) {
			$files = $formData['supporting_files'];
			
			if (count($files) > 0) {
				$files = [];
				
				array_push($files, $formData['supporting_files']);
			}
			
			$formData['supporting_files'] = [];
			
			foreach ($files as $file) {
				$fileName = $file->getClientOriginalName();
					
				$extension = $file->getClientOriginalExtension();
				
				$fileName = $this->sanitizeFilename($fileName, $extension);
				
				// Check if asset exists
				$supportingFile = $this->getSupportingFileByFileName($fileName);
				
				// If the assets doesn't exist, then upload it
				if (empty($supportingFile)) {
					$supportingFile = StorageHelper::uploadFile('supporting-files', $file, $fileName, $this->visibility);
				}
				
				// $url = $publicUrl.DIRECTORY_SEPARATOR.'supporting-files'.DIRECTORY_SEPARATOR.$fileName;
				
				$url = Storage::temporaryUrl('supporting-files'.DIRECTORY_SEPARATOR.$fileName, now()->addMinutes($this->temporaryUrlMinutes));

				array_push($formData['supporting_files'], $url);
			}
		}
		
		return $formData;
	}

	/**
	 * Gets url protocol and fixes if broken/missing
	 *
	 * @params	string			$url
	 * @return 	string
	 */
	protected function fixProtocol($url)
	{
		if (!empty($url)) {
			$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';
			
			if (stripos($url, 'http')) {
				$url = str_replace('////', '//', $protocol.$url);
			}
		}
		
		return $url;
	}
	
	/**
	 * Gets data for the specified model
	 *
	 * @params	string			$model
	 * @param	string			$cacheKey
	 * @return 	Collection
	 */
	protected function getData(string $model, $cacheKey = null)
	{
		if (!empty($cacheKey)) {
			$data = $this->getCache($cacheKey);
		
			if (is_null($data)) {
				$data = ($this->{$model}());
				
				$this->setCache($cacheKey, $data);
			}
		} else {
			$data = ($this->{$model}());
		}
		
		return $data;
	}
	
	/**
	 * We need to filter the models in the current url segment (whether that be sector, category or content type). 
	 * Before this step, a url segment will return back all models as the many to many relationships are only filtering 1 level up. See: category_product table.
	 * But we actually need to filter based on 3 relationships as we only want models that have matching department, sector, category or content type.
	 *
	 * Note the use of ... operator instead of using func_get_args() as dynamic number of arguments can be passed in.
	 */
	public function filterData($models, ...$relationships)
	{
		$matchingRelationships = 0;
		
		return $models->filter(function($model) use ($matchingRelationships, $relationships) {
			foreach ($relationships as $relationship) {
				$relation = (clone $relationship);
				
				$relation = get_class($relation);
				
				$relation = str_replace('\\', DIRECTORY_SEPARATOR, $relation);
				
				$relation = basename($relation);
				
				$relation = str_plural($relation);
				
				$relation = strtolower($relation);
				
				if ($model->{$relation}->pluck('slug')->contains($relationship->slug)) {
					$matchingRelationships++;
				}
			}
			
			if ($matchingRelationships == count($relationships)) {
				return $model;
			}
		});
	}
	
	/**
	 * Maps an banner image to an asset modal object if the image exists in the assets table.
	 */
	public function mapBannersToAssets($models)
	{
		if (is_a($models, 'Illuminate\Database\Eloquent\Collection')) {
			return $models->each(function ($model) {
				$this->bannerToAsset($model);
			});
		} else {
			return $this->bannerToAsset($models);
		}
	}
	
	/**
	 * Maps an image to an asset modal object if the image exists in the assets table.
	 */
	public function mapImagesToAssets($models)
	{
		if (is_a($models, 'Illuminate\Database\Eloquent\Collection')) {
			return $models->each(function ($model) {
				$this->imageToAsset($model);
			});
		} else {
			return $this->imageToAsset($models);
		}
	}
	
	/**
	 * Maps an image to an asset modal object if the image exists in the assets table.
	 */
	private function bannerToAsset($model)
	{
		$disk = StorageHelper::getDisk();
		
		$path = explode(DIRECTORY_SEPARATOR, $model->banner);
		
		$path = array_reverse($path);
		
		$fileName = $path[0];
		
		if ($model->banner) {
			$asset = $this->getAssetByFileNameDiskAndPath($fileName, $disk, $model->banner);
		} else {
			$asset = $this->getAssetByFileNameAndDisk($fileName, $disk);
		}
		
		if (!empty($asset)) {
			$asset->focus_point = json_decode($asset->focus_point);
			
			$model->banner = $asset;
		} else {
			$modelAsset = new stdClass;
			
			$modelAsset->url = $model->banner;
			$modelAsset->path = $model->banner;
			
			$model->banner = $modelAsset;
		}
		
		return $model;
	}
	
	/**
	 * Maps an image to an asset modal object if the image exists in the assets table.
	 */
	private function imageToAsset($model)
	{
		$disk = StorageHelper::getDisk();
		
		$path = explode(DIRECTORY_SEPARATOR, $model->image);
		
		$path = array_reverse($path);
		
		$fileName = $path[0];
		
		if ($model->image) {
			$asset = $this->getAssetByFileNameDiskAndPath($fileName, $disk, $model->image);
		} else {
			$asset = $this->getAssetByFileNameAndDisk($fileName, $disk);
		}
		
		if (!empty($asset)) {
			$asset->focus_point = json_decode($asset->focus_point);
			
			$model->image = $asset;
		} else {
			$modelAsset = new stdClass;
			
			if (empty($model->image)) {
				$model->image = '/assets/img/placeholder.png';	
			}
			
			$modelAsset->url = $model->image;
			$modelAsset->path = $model->image;
			
			$model->image = $modelAsset;
		}
		
		return $model;
	}
	
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @param	string 		$model
	 * @return 	array
	 */
	public function getRules(string $model) : array
	{
		return config('cms.validation_rules.'.$model);
	}

	/**
	 * Creates a new token, used for API and user activations.
	 *
	 * @return	String
	 */
	public function getToken($length = 191) : string
	{
		return hash_hmac('sha256', str_random($length), config('app.key'));
	}
	
	/**
	 * Validate the incoming request.
	 *
	 * @param		array		$data
	 * @param	array	$data
	 * @return 	\Illuminate\Contracts\Validation\Validator
	 */
	protected function validatorInput(array $data, array $rules) : ValidatorResponse
	{
		return Validator::make($data, $rules);
	}
	
	/**
	 * Sanitise the incoming request.
	 *
	 * @param		array	$data
	 * @return 	\Illuminate\Contracts\Validation\Validator
	 */
	protected function sanitizerInput(array $data) : array
	{
		$integers = [
			'id',
			'product_id',
			'content_type_id',
			'solution_id',
			'model_id',
			'minimum_number_of_units',
			'article_id',
			'article_category_id',
			'field_id',
			'page_id',
			'content_id',
			'field_type_id',
			'template_id',
			'user_id',
			'required',
			'status_id',
			'default_location_id',
			'delivery_method_id',
			'location_id',
			'county_id',
			'country_id',
			'company_id',
			'parent_id',
			'order_id',
			'product_id',
			'quantity',
			'tax_rate',
			'lft',
			'rgt',
			'depth',
			'role_id',
			'count',
			'permission_id',
			'location_id',
			'size',
			'hide_from_nav',
			'order',
			'event_id',
			'sort_order',
			'import_id',
			'product_id',
			'product_category_id',
			'supplier_id',
		];

		$floats = [
			'price',
			'price_tax',
			'tax',
			'subtotal',
			'total',
		];

		$strings = [
			'banner',
			'short_name',
			'due_date',
			'model',
			'modal',
			'action',
			'comments',
			'overview',
			'commodity_name_protocol',
			'commodity_code_protocol',
			'commodity_short_description_protocol',
			'image_uri',
			'website',
			'code',
			'bio',
			'skype',
			'image',
			'logo_image',
			'cms_page_name',
			'timecheck',
			'futher_details',
			'rate',
			'rate_display',
			'event_type',
			'action',
			'identifier',
			'content',
			'instance',
			'row_id',
			'first_name',
			'last_name',
			'jot_title',
			'telephone',
			'mobile',
			'remember_token',
			'title',
			'type',
			'handle',
			'instructions',
			'description',
			'keywords',
			'options',
			'data',
			'slug',
			'unit',
			'building',
			'street_address_1',
			'street_address_2',
			'street_address_3',
			'street_address_4',
			'town_city',
			'postal_code',
			'order_number',
			'po_number',
			'notes',
			'order_type',
			'mime_type',
			'extension',
			'path',
			'filename',
		];

		$booleans = [
		];

		$emails = [
			'email',
		];

		$urls = [
		];

		$passwords = [
			'password',
			'password_confirm',
		];

		foreach ($data as $key => &$value) {
			if (is_array($value)) {
				$this->sanitizerInput($value);
			} else {
				if (in_array($key, $integers)) {
					$value = filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]);
				} elseif (in_array($key, $strings)) {
					$value = filter_var($value, FILTER_SANITIZE_STRING, FILTER_SANITIZE_MAGIC_QUOTES);
				} elseif (in_array($key, $floats)) {
					$value = filter_var($value, FILTER_VALIDATE_FLOAT);
				} elseif (in_array($key, $booleans)) {
					$value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
				} elseif (in_array($key, $emails)) {
					$value = filter_var($value, FILTER_VALIDATE_EMAIL);
				} elseif (in_array($key, $urls)) {
					$value = filter_var($value, FILTER_VALIDATE_URL);
				} elseif (in_array($key, $passwords)) {
					$value = strip_tags($value);
				}
			}
		}

		return $data;
	}
	
	/**
	 * Checks if we are running in console
	 *
	 * @return 	boolean
	 */
	protected function runningInConsole() : bool
	{
		return (php_sapi_name() === 'cli');
	}

	/**
	 * Retrieves the current authenticated guard type.
	 *
	 * @return String
	 */
	protected function getRequestType() : string
	{
		return Auth::guard('api')->check() ? 'api' : 'web';
	}

	/**
	 * Retrieves the current authenticated user's id.
	 *
	 * @return Object
	 */
	protected function getAuthenticatedUserId()
	{
		return Auth::id();
	}

	/**
	 * Retrieves the current authenticated user.
	 *
	 * @return Object
	 */
	protected function getAuthenticatedUser()
	{
		return Auth::user();
	}

	/**
	 * Checks if account is authenticated.
	 *
	 * @return 	Response
	 */
	protected function checkAuthentication()
	{
		if (is_null(Auth::guard('api')->user())) {
			// The request is using an invalid api token or none at all, so stop the request dead in its tracks - needs to be authenicated first!
			return new AuthenticationException();
		}

		return true;
	}

	/**
	 * Get status code for an api response.
	 *
	 * @return 	Response
	 */
	protected function getStatusCode() : int
	{
		return $this->httpStatusCode;
	}

	/**
	 * Set status code for an api response.
	 *
	 * @param 	int 		$httpStatusCode
	 * @return	 Response
	 */
	protected function setStatusCode(int $httpStatusCode)
	{
		$this->httpStatusCode = $httpStatusCode;

		// Added so we can chain calls
		return $this;
	}

	/**
	 * Get limit for an api response.
	 *
	 * @return 	Response
	 */
	protected function getLimit() : int
	{
		return $this->limit;
	}

	/**
	 * Set limit for an api response.
	 *
	 * @param 	int 		$limit
	 * @return	 Response
	 */
	protected function setLimit(int $limit) : int
	{
		$this->limit = ($limit === 0 || $limit > $this->maxLimit) ? $this->maxLimit : $limit;
	}

	/**
	 * Check if the limit is valid for an api response.
	 *
	 * @param 	mixed 		$limit
	 * @return	 Response
	 */
	protected function isValidLimit($limit)
	{
		return (!is_int($limit) ? (ctype_digit($limit)) : true);
	}

	/**
	 * Carries out a few checks on the limit param and if valid, sets the limit for pagination.
	 *
	 * @param	$limit
	 */
	protected function validateLimit($limit)
	{
		$requestType = $this->getRequestType();

		$isValid = $this->isValidLimit($limit);

		if (!$isValid) {
			if ($requestType === 'api') {
				// If API request, we want to throw the invalid limit in the JSON response
				return $this->setStatusCode(400)->respondWithInvalidLimit();
			} else {
				// If other request, e.g via cp, we want to throw the invalid limit as an onscreen error message.
				abort(500, 'Invalid limit');
			}
		}

		$this->setLimit($limit);
	}
	
	/**
	 * Creates a comma separated string.
	 *
	 * @param	String 	$string
	 * @return String
	 */
	protected function commaSeparate($string) : string
	{
		if (!empty($string)) {
			// Stripe whitespace
			$string = trim($string);
			
			// Split the string first
			$string = explode(' ', $string);
			
			// Join the string again with commas
			$string = implode(',', $string);
			
			// Tidy up commas
			$string = str_replace([' ,', ', ', ',,,', ',,'], ',', $string);
			
			// Tidy up spaces
			$string = str_replace('	', ' ', $string);
			
			// If last char is a comma, remove it
			if ($string{-1} === ',') {
				$string = substr($string, 0, strlen($string) - 1);
			}
		}
		
		// Stripe whitespace again
		$string = trim($string);
		
		return $string;
	}
	
	/**
	 * Simple gets the cache
	 *
	 * @param	String 		$key
	 * @return Collection
	 */
	public function getCache(string $key) 
	{
		if ($this->cachingEnabled) {
			return Cache::get($key);
		}
		
		return null;
	}
	
	/**
	 * Simple sets the cache
	 *
	 * @param	String 		$key
	 * @param	Mixed 		$data
	 * @return Collection
	 */
	public function setCache(string $key, $data) 
	{
		if ($this->cachingEnabled) {
			Cache::put($key, $data, $this->minutes);
		}
		
		return $this;
	}
	
	/**
	 * Simple forgets the cache
	 *
	 * @param	String 		$key
	 */
	public function forgetCache(string $key) 
	{
		if ($this->cachingEnabled) {
			Cache::forget($key);
		}
		
		return $this;
	}
	
	/**
	 * Simple flush entire cache
	 */
	public function flushCache() 
	{
		Cache::flush();
		
		return $this;
	}
	
	/**
	 * Instantiates our very own LengthAwarePaginator used on custom collections
	 *
	 * @param	Collection 	$collection
	 * @param	int 			$perPage
	 * @param	string 		$pageName
	 * @param	mixed 		$fragment
	 * @return Collection
	 */
	protected function paginateCollection($collection, $perPage, $pageName = 'page', $fragment = null) : LengthAwarePaginator
	{
		$currentPage = LengthAwarePaginator::resolveCurrentPage($pageName);

		$currentPageItems = $collection->slice(($currentPage - 1) * $perPage, $perPage);

		$accessToken = Request::header('Authorization');

		parse_str(request()->getQueryString(), $query);

		unset($query[$pageName]);

		if (!is_null($accessToken) && str_contains($accessToken, 'Bearer')) {
			$query['api_token'] = trim(str_replace('Bearer', '', $accessToken));
		}

		$paginator = new LengthAwarePaginator($currentPageItems, $collection->count(), $perPage, $currentPage, [
			'pageName' => $pageName,
			'path' => LengthAwarePaginator::resolveCurrentPath(),
			'query' => $query,
			'fragment' => $fragment
		]);

		return $paginator;
	}
}
