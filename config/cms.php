<?php
/**
 * @link	  https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license	  https://www.delaneymethod.com/cms/license
 */

$inDevelopmentMode = config('app.debug');

return [
	
	/*
	|--------------------------------------------------------------------------
	| Client Configuration
	|--------------------------------------------------------------------------
	*/
	
	'app_path_public' => env('APP_PATH_PUBLIC', 'public'),
	
	'delays' => [
		// If in dev mode, no delays
		'jobs' => ($inDevelopmentMode) ? 0 : 10,
		'emails' => ($inDevelopmentMode) ? 0 : 5,
		'notifications' => ($inDevelopmentMode) ? 0 : 1,
	],
	
	'slack_webhook_url' => env('SLACK_WEBHOOK_URL', ''),
	
	'email' => [
		'recipients' => [
			'acquisition_target' => env('EMAIL_RECIPIENTS_ACQUISITION_TARGET', ''),
			'twitter' => env('EMAIL_RECIPIENTS_TWITTER', ''),
			'linkedin' => env('EMAIL_RECIPIENTS_LINKEDIN', ''),
			'instagram' => env('EMAIL_RECIPIENTS_INSTAGRAM', ''),
			'facebook' => env('EMAIL_RECIPIENTS_FACEBOOK', ''),
			'on_target' => env('EMAIL_RECIPIENTS_ON_TARGET', ''),
			'on_board' => env('EMAIL_RECIPIENTS_ON_BOARD', ''),
			'new_horizons' => env('EMAIL_RECIPIENTS_NEW_HORIZONS', ''),
			'defence_aerospace' => env('EMAIL_RECIPIENTS_DEFENCE_AEROSPACE', ''),
			'marine' => env('EMAIL_RECIPIENTS_MARINE', ''),
			'wind_energy' => env('EMAIL_RECIPIENTS_WIND_ENERGY', ''),
			'corporate' => env('EMAIL_RECIPIENTS_CORPORATE', ''),
		],
	],
	
	/*
	|--------------------------------------------------------------------------
	| Model Validation Rules Configuration
	|--------------------------------------------------------------------------
	|
	| Added by Sean - Used for API related controllers when validating POST, PUT and PATCH request.
	|
	*/

	'validation_rules' => [
		'department' => [
			'title' => 'required|string|max:255',
			'slug' => 'required|string|max:255',
			'description' => 'required|string',
			'banner' => 'required|string|max:255',
			'image' => 'required|string|max:255',
			'yammer' => 'nullable|string',
			'stream' => 'nullable|string',
			'status_id' => 'required|integer',
		],
		'sector' => [
			'title' => 'required|string|max:255',
			'slug' => 'required|string|max:255',
			'description' => 'required|string',
			'banner' => 'nullable|string',
			'image' => 'required|string|max:255',
			'yammer' => 'nullable|string',
			'stream' => 'nullable|string',
			'order' => 'nullable|integer',
			'colour' => 'nullable|string',
			'status_id' => 'required|integer',
			'department_ids' => 'required|array|min:1',
		],
		'category' => [
			'title' => 'required|string|max:255',
			'slug' => 'required|string|max:255',
			'description' => 'required|string',
			'banner' => 'nullable|string',
			'image' => 'required|string|max:255',
			'status_id' => 'required|integer',
			'sector_ids' => 'required|array|min:1',
		],
		'content_type' => [
			'title' => 'required|string|max:255',
			'slug' => 'required|string|max:255',
			'description' => 'required|string',
			'banner' => 'nullable|string',
			'image' => 'required|string|max:255',
			'status_id' => 'required|integer',
			'category_ids' => 'required|array|min:1',
		],
		'product' => [
			'title' => 'required|string|max:255',
			'slug' => 'required|string|max:255',
			'description' => 'required|string',
			'banner' => 'nullable|string',
			'image' => 'required|string|max:255',
			'video' => 'nullable|string',
			'overview' => 'nullable|string',
			'due_date' => 'nullable|string',
			'minimum_number_of_units' => 'nullable|integer',
			'npd_feedback_recipient' => 'nullable|string',
			'ex_feedback_recipient' => 'nullable|string',
			'status_id' => 'required|integer',
			'department_ids' => 'required|array|min:1',
			'sector_ids' => 'required|array|min:1',
			'supporting_files' => 'nullable|array',
		],
		'model' => [
			'title' => 'required|string|max:255',
			'slug' => 'required|string|max:255',
			'minimum_number_of_units' => 'nullable|integer',
			'status_id' => 'required|integer',
		],
		'expert' => [
			'full_name' => 'required|string|max:255',
			'email' => 'required|string|email|unique:experts,email|max:255',
			'position' => 'required|string|max:255',
			'image' => 'nullable|string|max:255',
			'status_id' => 'required|integer',
		],
		'event' => [
			'title' => 'required|string|max:255',
			'slug' => 'required|string|max:255',
			'description' => 'required|string',
			'image' => 'required|string|max:255',
			'all_day' => 'required|integer',
			'options' => 'nullable|string|max:255',
			'organiser_name' => 'nullable|string',
			'organiser_email' => 'nullable|string',
			'organiser_contact_number' => 'nullable|string',
			'status_id' => 'required|integer',
			'sector_ids' => 'required|array|min:1',
		],
		'event_date_time' => [
			'event_id' => 'required|integer',
			'start' => 'required|string|max:255',
			'end' => 'required|string|max:255',
		],
		'global' => [
			'title' => 'required|string|max:255',
			'handle' => 'required|string|max:255',
			'data' => 'nullable|string',
			'image' => 'nullable|string|max:255',
		],
		'role' => [
			'title' => 'required|string|max:255',
		],
		'status' => [
			'title' => 'required|string|max:255',
			'description' => 'nullable|string|max:255',
		],
		'permission' => [
			'title' => 'required|string|max:255',
		],
		'user' => [
			'first_name' => 'required|string|max:255',
			'last_name' => 'required|string|max:255',
			'email' => 'required|string|email|unique:users,email|max:255',
			'telephone' => 'nullable|phone:AUTO|string',
			'password' => 'required|string|max:255',
			'image' => 'nullable|string|max:255',
			'skype' => 'nullable|string|max:255',
			'job_title' => 'nullable|string|max:255',
			'bio' => 'nullable|string',
			'status_id' => 'required|integer',
			'role_id' => 'required|integer',
			'location_id' => 'required|integer',
		],
		'team_member' => [
			'full_name' => 'required|string|max:255',
			'email' => 'required|string|email|unique:team_members,email|max:255',
			'image' => 'nullable|string|max:255',
			'job_title' => 'nullable|string|max:255',
			'bio' => 'nullable|string',
			'status_id' => 'required|integer',
			'location_id' => 'required|integer',
		],
		'email_login' => [
			'email' => 'required|string|email|exists:users|max:255',
		],
		'country' => [
			'title' => 'required|string|max:255',
		],
		'county' => [
			'title' => 'required|string|max:255',
			'country_id' => 'required|integer',
		],
		'location' => [
			'title' => 'required|string|max:255',
			'unit' => 'nullable|string|max:255',
			'building' => 'nullable|string|max:255',
			'street_address_1' => 'required|string|max:255',
			'street_address_2' => 'nullable|string|max:255',
			'street_address_3' => 'nullable|string|max:255',
			'street_address_4' => 'nullable|string|max:255',
			'town_city' => 'required|string|max:255',
			'postal_code' => 'nullable|string|max:255',
			'county_id' => 'required|integer',
			'country_id' => 'required|integer',
			'telephone' => 'nullable|phone:AUTO|string',
			'fax' => 'nullable|phone:AUTO|string',
			'email' => 'nullable|string|max:255',
			'status_id' => 'required|integer',
		],
		'idea' => [
			'title' => 'required|string|max:255',
			'slug' => 'required|string|max:255',
			'redactor' => 'required|string',
			'image' => 'required|string|max:255',
			'status_id' => 'required|integer',
			'submitted_by' => 'required|string|max:255',
			'department_ids' => 'required|array|min:1',
			'supporting_files' => 'nullable|array',
		],
		'requestAnEvent' => [
			'name' => 'required|string|max:255',
			'date' => 'required|string|max:255',
			'sector' => 'required|string',
			'region' => 'required|string',
			'scopeOfAttendance' => 'required|string',
			'partnershipName' => 'string|max:255',
			'justificationForAttendance' => 'required|string',
			'detailsOfStand' => 'required|string',
			'estimatedCostOfStand' => 'required|string',
			'objectiveOfEvent' => 'required|string',
			'messagingAtEvent' => 'required|string',
			'productsToBeDisplayed' => 'required|string',
			'customerInviteGroups' => 'required|string',
			'customerPreArrangedMeetings' => 'string',
			'papersToBeSubmitted' => 'string',
			'pipelineOpportunities' => 'string',
			'attendees' => 'required|string',
		],
	],
	
	/*
	|--------------------------------------------------------------------------
	| Grid Configuration
	|--------------------------------------------------------------------------
	*/
	
	'column_widths' => [
		'cp' => [
			'sidebar' => [
				'sm' => 'col-sm-12',
				'md' => 'col-md-12',
				'lg' => 'col-lg-2',
				'xl' => 'col-xl-2',
			],
			
			'main' => [
				'sm' => 'col-sm-12',
				'md' => 'col-md-12',
				'lg' => 'col-lg-10',
				'xl' => 'col-xl-10',
			],
		],
	],
	
	/*
	|--------------------------------------------------------------------------
	| Error Configuration
	|--------------------------------------------------------------------------
	|
	| Used for exception error handling and other API related controllers.
	|
	*/

	'api_error_messages' => [
		'400' => [
			'error' => [
				'type' => 'bad_request',
				'message' => 'A 400 status code indicates that the server did not understand the request, possibility due to bad syntax.',
				'code' => 10,
			],
		],
		'401' => [
			'error' => [
				'type' => 'unauthorised',
				'message' => 'A 401 status code indicates that before a resource can be accessed, the client must be authorised by the server.',
				'code' => 20,
			],
		],
		'403' => [
			'error' => [
				'type' => 'forbidden',
				'message' => 'A 403 status code indicates that the client cannot access the requested resource. That might mean that the wrong username and password were sent in the request, or that the permissions on the server do not allow what was being asked.',
				'code' => 30,
			],
		],
		'404' => [
			'error' => [
				'type' => 'not_found',
				'message' => 'A 404 status code indicates that the requested resource was not found at the URL given, and the server has no idea how long for.',
				'code' => 40,
			],
		],
		'405' => [
			'error' => [
				'type' => 'method_not_allowed',
				'message' => 'A 405 status code indicates that the client has tried to use a request method that the server does not allow.',
				'code' => 50,
			],
		],
		'410' => [
			'error' => [
				'type' => 'gone',
				'message' => 'A 410 status code indicates that the resource has permanently gone, and no new address is known for it.',
				'code' => 60,
			],
		],
		'500' => [
			'error' => [
				'type' => 'internal_server_error',
				'message' => 'A 500 status code indicates that the server encountered something it didn\'t expect and was unable to complete the request.',
				'code' => 70,
			],
		],
	],
	
	'file_types' => [
		// Archives
		'7z' => 'fa-file-archive-o',
		'bz' => 'fa-file-archive-o',
		'gz' => 'fa-file-archive-o',
		'rar' => 'fa-file-archive-o',
		'tar' => 'fa-file-archive-o',
		'zip' => 'fa-file-archive-o',
		
		// Audio
		'aac' => 'fa-music',
		'flac' => 'fa-music',
		'mid' => 'fa-music',
		'midi' => 'fa-music',
		'mp3' => 'fa-music',
		'ogg' => 'fa-music',
		'wma' => 'fa-music',
		'wav' => 'fa-music',
		
		// Code
		'c' => 'fa-code',
		'class' => 'fa-code',
		'cpp' => 'fa-code',
		'css' => 'fa-code',
		'erb' => 'fa-code',
		'htm' => 'fa-code',
		'html' => 'fa-code',
		'java' => 'fa-code',
		'js' => 'fa-code',
		'php' => 'fa-code',
		'pl' => 'fa-code',
		'py' => 'fa-code',
		'rb' => 'fa-code',
		'xhtml' => 'fa-code',
		'xml' => 'fa-code',
		
		// Databases
		'accdb' => 'fa-hdd-o',
		'db' => 'fa-hdd-o',
		'dbf' => 'fa-hdd-o',
		'mdb' => 'fa-hdd-o',
		'pdb' => 'fa-hdd-o',
		'sql' => 'fa-hdd-o',
		
		// Documents
		'csv' => 'fa-file-text',
		'doc' => 'fa-file-text',
		'docx' => 'fa-file-text',
		'odt' => 'fa-file-text',
		'pdf' => 'fa-file-text',
		'xls' => 'fa-file-text',
		'xlsx' => 'fa-file-text',
		
		// Executables
		'app' => 'fa-list-alt',
		'bat' => 'fa-list-alt',
		'com' => 'fa-list-alt',
		'exe' => 'fa-list-alt',
		'jar' => 'fa-list-alt',
		'msi' => 'fa-list-alt',
		'vb' => 'fa-list-alt',
		
		// Fonts
		'eot' => 'fa-font',
		'otf' => 'fa-font',
		'ttf' => 'fa-font',
		'woff' => 'fa-font',
		
		// Game Files
		'gam' => 'fa-gamepad',
		'nes' => 'fa-gamepad',
		'rom' => 'fa-gamepad',
		'sav' => 'fa-floppy-o',
		
		// Images
		'bmp' => 'fa-picture-o',
		'gif' => 'fa-picture-o',
		'jpg' => 'fa-picture-o',
		'jpeg' => 'fa-picture-o',
		'png' => 'fa-picture-o',
		'psd' => 'fa-picture-o',
		'tga' => 'fa-picture-o',
		'tif' => 'fa-picture-o',
		
		// Package Files
		'box' => 'fa-archive',
		'deb' => 'fa-archive',
		'rpm' => 'fa-archive',
		
		// Scripts
		'bat' => 'fa-terminal',
		'cmd' => 'fa-terminal',
		'sh' => 'fa-terminal',
		
		// Text
		'cfg' => 'fa-file-text',
		'ini' => 'fa-file-text',
		'log' => 'fa-file-text',
		'md' => 'fa-file-text',
		'rtf' => 'fa-file-text',
		'txt' => 'fa-file-text',
		
		// Vector Images
		'ai' => 'fa-picture-o',
		'drw' => 'fa-picture-o',
		'eps' => 'fa-picture-o',
		'ps' => 'fa-picture-o',
		'svg' => 'fa-picture-o',
		
		// Video
		'avi' => 'fa-youtube-play',
		'flv' => 'fa-youtube-play',
		'mkv' => 'fa-youtube-play',
		'mov' => 'fa-youtube-play',
		'mp4' => 'fa-youtube-play',
		'mpg' => 'fa-youtube-play',
		'ogv' => 'fa-youtube-play',
		'webm' => 'fa-youtube-play',
		'wmv' => 'fa-youtube-play',
		'swf' => 'fa-youtube-play',
		
		// Other
		'bak' => 'fa-floppy',
		'msg' => 'fa-envelope',
		
		// Blank
		'blank' => 'fa-file',
	],

];
