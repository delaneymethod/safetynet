<?php 
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */
 	
namespace App\Helpers\Facades;

use Illuminate\Support\Facades\Facade;

class StorageHelper extends Facade 
{
	protected static function getFacadeAccessor()
	{
		return 'helpers.storage';
	}
}
