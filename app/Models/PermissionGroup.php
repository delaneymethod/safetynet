<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Models;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PermissionGroup extends Model
{
	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'permission_groups';
    
	protected $characterSet = 'UTF-8';
	
	protected $flags = ENT_QUOTES;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'id',
		'title',
	];

	/**
	 * Get the permissions records associated with the permission group.
	 */
	public function permissions() : HasMany
	{
		return $this->hasMany(Permission::class);
	}
}
