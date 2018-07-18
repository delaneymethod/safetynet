<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// FRONT END ROUTES

// Authentication routes
Auth::routes();

// Home routes
Route::get('/', 'HomeController@index');

Route::get('/events/filter/{sectors?}', 'EventController@filter');

// Source routes
Route::group(['prefix' => 'source'], function () {
	Route::get('/', 'SourceController@index');
	Route::get('/shop', 'SourceController@shop');
	Route::get('/events', 'SourceController@events');
	Route::get('/request-an-event', 'EventController@requestAnEvent');
	Route::post('/request-an-event', 'EventController@requestAnEventSubmission');
	Route::get('/request-a-post', 'SourceController@requestAPost');
	Route::get('/{sector}', 'SourceController@sector');
	Route::get('/{sector}/events', 'SourceController@events');
	Route::get('/{sector}/{category}', 'SourceController@category');
	Route::get('/{sector}/{category}/{contentType}', 'SourceController@contentType');
	Route::get('/{sector}/{category}/{contentType}/{product}', 'SourceController@product');
	Route::post('/form-submission', 'SourceController@formSubmission');
});

// Nexo routes
Route::group(['prefix' => 'nexo'], function () {
	// Nexo > Home routes
	Route::get('/', 'NexoController@index');
	Route::get('/events', 'NexoController@events');
	Route::get('/request-an-event', 'EventController@requestAnEvent');
	Route::post('/request-an-event', 'EventController@requestAnEventSubmission');
	Route::get('/ideas', 'NexoController@ideas');
	Route::get('/meet-the-team', 'NexoController@meetTheTeam');
	Route::get('/{sector}', 'NexoController@sector');
	Route::get('/{sector}/events', 'NexoController@events');
	Route::get('/{sector}/{category}', 'NexoController@category');
	Route::get('/{sector}/{category}/{contentType}', 'NexoController@contentType');
	Route::get('/{sector}/{category}/{contentType}/{product}', 'NexoController@product');
	Route::post('/form-submission', 'NexoController@formSubmission');	
});
	
// Email Login Routes
Route::get('/login/email', 'Auth\EmailLoginController@showLoginForm');
Route::get('/login/email/{token}', 'Auth\EmailLoginController@authenticate');
Route::post('/login/email', 'Auth\EmailLoginController@login');

// Sharepoint Login Routes
Route::get('/login/sharepoint', 'Auth\SharepointLoginController@index');
Route::get('/login/sharepoint/callback', 'Auth\SharepointLoginController@callback');
Route::get('/login/sharepoint/refresh', 'Auth\SharepointLoginController@refresh');

// BACK END ROUTES
Route::group(['prefix' => 'cp'], function () {
	// CP route
	Route::redirect('/', '/cp/dashboard', 301);
	
	// CP > Dashboard routes
	Route::get('/dashboard', 'DashboardController@index');

	// CP > Users routes
	Route::get('/users', 'UserController@index');
	Route::get('/users/create', 'UserController@create');
	Route::get('/users/{id}/edit', 'UserController@edit');
	Route::get('/users/{id}/edit/password', 'UserController@editPassword');
	Route::get('/users/{id}/delete', 'UserController@confirm');
	Route::post('/users', 'UserController@store');
	Route::put('/users/{id}', 'UserController@update');
	Route::patch('/users/{id}', 'UserController@update');
	Route::delete('/users/{id}', 'UserController@delete');
	
	// CP > Meet the Team routes
	Route::get('/team-members', 'TeamMemberController@index');
	Route::get('/team-members/create', 'TeamMemberController@create');
	Route::get('/team-members/{id}/edit', 'TeamMemberController@edit');
	Route::get('/team-members/{id}/delete', 'TeamMemberController@confirm');
	Route::post('/team-members', 'TeamMemberController@store');
	Route::post('/team-members/sort', 'TeamMemberController@sort');
	Route::put('/team-members/{id}', 'TeamMemberController@update');
	Route::patch('/team-members/{id}', 'TeamMemberController@update');
	Route::delete('/team-members/{id}', 'TeamMemberController@delete');
	
	// CP > Globals routes
	Route::get('/globals', 'GlobalController@index');
	Route::get('/globals/create', 'GlobalController@create');
	Route::get('/globals/{id}/edit', 'GlobalController@edit');
	Route::get('/globals/{id}/delete', 'GlobalController@confirm');
	Route::post('/globals', 'GlobalController@store');
	Route::put('/globals/{id}', 'GlobalController@update');
	Route::patch('/globals/{id}', 'GlobalController@update');
	Route::delete('/globals/{id}', 'GlobalController@delete');
	
	// CP > Departments routes
	Route::get('/departments', 'DepartmentController@index');
	Route::get('/departments/create', 'DepartmentController@create');
	Route::get('/departments/{id}/edit', 'DepartmentController@edit');
	Route::get('/departments/{id}/delete', 'DepartmentController@confirm');
	Route::post('/departments', 'DepartmentController@store');
	Route::put('/departments/{id}', 'DepartmentController@update');
	Route::patch('/departments/{id}', 'DepartmentController@update');
	Route::delete('/departments/{id}', 'DepartmentController@delete');
	
	// CP > Sectors routes
	Route::get('/sectors', 'SectorController@index');
	Route::get('/sectors/create', 'SectorController@create');
	Route::get('/sectors/{id}/edit', 'SectorController@edit');
	Route::get('/sectors/{id}/delete', 'SectorController@confirm');
	Route::post('/sectors', 'SectorController@store');
	Route::post('/sectors/sort', 'SectorController@sort');
	Route::put('/sectors/{id}', 'SectorController@update');
	Route::patch('/sectors/{id}', 'SectorController@update');
	Route::delete('/sectors/{id}', 'SectorController@delete');
	
	// CP > Categories routes
	Route::get('/categories', 'CategoryController@index');
	Route::get('/categories/create', 'CategoryController@create');
	Route::get('/categories?sector={sector}', 'CategoryController@index');
	Route::get('/categories/{id}/edit', 'CategoryController@edit');
	Route::get('/categories/{id}/delete', 'CategoryController@confirm');
	Route::post('/categories', 'CategoryController@store');
	Route::post('/categories/sort', 'CategoryController@sort');
	Route::put('/categories/{id}', 'CategoryController@update');
	Route::patch('/categories/{id}', 'CategoryController@update');
	Route::delete('/categories/{id}', 'CategoryController@delete');
	
	// CP > Content Types routes
	Route::get('/content-types', 'ContentTypeController@index');
	Route::get('/content-types/create', 'ContentTypeController@create');
	Route::get('/content-types/{id}/edit', 'ContentTypeController@edit');
	Route::get('/content-types/{id}/delete', 'ContentTypeController@confirm');
	Route::post('/content-types', 'ContentTypeController@store');
	Route::put('/content-types/{id}', 'ContentTypeController@update');
	Route::patch('/content-types/{id}', 'ContentTypeController@update');
	Route::delete('/content-types/{id}', 'ContentTypeController@delete');
	
	// CP > Products routes
	Route::get('/products', 'ProductController@index');
	Route::get('/products/create', 'ProductController@create');
	Route::get('/products/{id}/edit', 'ProductController@edit');
	Route::get('/products/{id}/delete', 'ProductController@confirm');
	Route::get('/products/{id}/supporting-files/{supportingFileId}/delete/', 'ProductController@confirmSupportingFile');
	Route::get('/products/{id}/supporting-files/{supportingFileId}/download/', 'ProductController@downloadSupportingFile');
	Route::get('/products/{id}/supporting-files/{supportingFileId}/thumbnail/add', 'ProductController@createSupportingFileThumbnail');
	Route::get('/products/{id}/supporting-files/{supportingFileId}/thumbnail/delete', 'ProductController@confirmSupportingFileThumbnail');
	Route::post('/products/', 'ProductController@store');
	Route::post('/products/{id}/supporting-files/{supportingFileId}/thumbnail', 'ProductController@storeSupportingFileThumbnail');
	Route::put('/products/{id}', 'ProductController@update');
	Route::patch('/products/{id}', 'ProductController@update');
	Route::delete('/products/{id}', 'ProductController@delete');
	Route::delete('/products/{id}/supporting-files/{supportingFileId}', 'ProductController@deleteSupportingFile');
	Route::delete('/products/{id}/supporting-files/{supportingFileId}/thumbnail', 'ProductController@deleteSupportingFileThumbnail');
	
	// CP > Models routes
	Route::get('/models', 'ModelController@index');
	Route::get('/models/create', 'ModelController@create');
	Route::get('/models/{id}/edit', 'ModelController@edit');
	Route::get('/models/{id}/delete', 'ModelController@confirm');
	Route::post('/models', 'ModelController@store');
	Route::put('/models/{id}', 'ModelController@update');
	Route::patch('/models/{id}', 'ModelController@update');
	Route::delete('/models/{id}', 'ModelController@delete');
	
	// CP > Experts routes
	Route::get('/experts', 'ExpertController@index');
	Route::get('/experts/create', 'ExpertController@create');
	Route::get('/experts/{id}/edit', 'ExpertController@edit');
	Route::get('/experts/{id}/delete', 'ExpertController@confirm');
	Route::post('/experts', 'ExpertController@store');
	Route::put('/experts/{id}', 'ExpertController@update');
	Route::patch('/experts/{id}', 'ExpertController@update');
	Route::delete('/experts/{id}', 'ExpertController@delete');
	
	// CP > Locations routes
	Route::get('/locations', 'LocationController@index');
	Route::get('/locations/create', 'LocationController@create');
	Route::get('/locations/{id}/edit', 'LocationController@edit');
	Route::get('/locations/{id}/delete', 'LocationController@confirm');
	Route::post('/locations', 'LocationController@store');
	Route::put('/locations/{id}', 'LocationController@update');
	Route::patch('/locations/{id}', 'LocationController@update');
	Route::delete('/locations/{id}', 'LocationController@delete');
	
	// CP > Events routes
	Route::get('/events', 'EventController@index');
	Route::get('/events/create', 'EventController@create');
	Route::get('/events/{id}/edit', 'EventController@edit');
	Route::get('/events/{id}/delete', 'EventController@confirm');
	Route::post('/events', 'EventController@store');
	Route::put('/events/{id}', 'EventController@update');
	Route::patch('/events/{id}', 'EventController@update');
	Route::delete('/events/{id}', 'EventController@delete');
	
	// CP > Ideas routes
	Route::get('/ideas', 'IdeaController@index');
	Route::get('/ideas/create', 'IdeaController@create');
	Route::get('/ideas/{id}/edit', 'IdeaController@edit');
	Route::get('/ideas/{id}/delete', 'IdeaController@confirm');
	Route::get('/ideas/{id}/supporting-files/{supportingFileId}/delete/', 'IdeaController@confirmSupportingFile');
	Route::get('/ideas/{id}/supporting-files/{supportingFileId}/download/', 'IdeaController@downloadSupportingFile');
	Route::get('/ideas/{id}/supporting-files/{supportingFileId}/thumbnail/add', 'IdeaController@createSupportingFileThumbnail');
	Route::get('/ideas/{id}/supporting-files/{supportingFileId}/thumbnail/delete', 'IdeaController@confirmSupportingFileThumbnail');
	Route::post('/ideas', 'IdeaController@store');
	Route::post('/ideas/{id}/supporting-files/{supportingFileId}/thumbnail', 'IdeaController@storeSupportingFileThumbnail');
	Route::put('/ideas/{id}', 'IdeaController@update');
	Route::patch('/ideas/{id}', 'IdeaController@update');
	Route::delete('/ideas/{id}', 'IdeaController@delete');
	Route::delete('/ideas/{id}/supporting-files/{supportingFileId}', 'IdeaController@deleteSupportingFile');
	Route::delete('/ideas/{id}/supporting-files/{supportingFileId}/thumbnail', 'IdeaController@deleteSupportingFileThumbnail');
	
	Route::group(['prefix' => 'advanced'], function () {
		// CP > Advanced routes
		Route::redirect('/', '/cp/advanced/roles', 301);
		
		// CP > Roles routes
		Route::get('/roles', 'RoleController@index');
		Route::get('/roles/create', 'RoleController@create');
		Route::get('/roles/{id}/edit', 'RoleController@edit');
		Route::get('/roles/{id}/delete', 'RoleController@confirm');
		Route::post('/roles', 'RoleController@store');
		Route::post('/roles/permissions', 'RoleController@permissions');
		Route::put('/roles/{id}', 'RoleController@update');
		Route::patch('/roles/{id}', 'RoleController@update');
		Route::delete('/roles/{id}', 'RoleController@delete');
		
		// CP > Permissions routes
		Route::get('/permissions', 'PermissionController@index');
		
		// CP > Statuses routes
		Route::get('/statuses', 'StatusController@index');
		Route::get('/statuses/create', 'StatusController@create');
		Route::get('/statuses/{id}/edit', 'StatusController@edit');
		Route::get('/statuses/{id}/delete', 'StatusController@confirm');
		Route::post('/statuses', 'StatusController@store');
		Route::put('/statuses/{id}', 'StatusController@update');
		Route::patch('/statuses/{id}', 'StatusController@update');
		Route::delete('/statuses/{id}', 'StatusController@delete');
	});
	
	// CP > Assets routes
	Route::get('/assets', 'AssetController@index');
	Route::get('/assets/upload', 'AssetController@upload');
	Route::get('/assets/folder/create', 'AssetController@folderCreate');
	Route::get('/assets/folder/delete', 'AssetController@folderConfirm');
	Route::get('/assets/{id}/move', 'AssetController@select');
	Route::get('/assets/{id}/delete', 'AssetController@confirm');
	Route::post('/assets', 'AssetController@store');
	Route::post('/assets/folder', 'AssetController@folderStore');
	Route::post('/assets/focus-point', 'AssetController@focusPoint');
	Route::post('/assets/browse', 'AssetController@browse');
	Route::put('/assets/{id}/move', 'AssetController@move');
	Route::patch('/assets/{id}/move', 'AssetController@move');
	Route::delete('/assets/folder', 'AssetController@folderDelete');
	Route::delete('/assets/{id}', 'AssetController@delete');
	
	// CP > Support routes
	Route::get('/support', 'SupportController@index');
});
