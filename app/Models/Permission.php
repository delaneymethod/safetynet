<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Models;

use App\User;
use App\Models\{Role, PermissionGroup};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany};

class Permission extends Model
{
	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'permissions';
    
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
		'permission_group_id',
	];
	
	/**
	 * Get the permission group record associated with the permission.
	 */
	public function permission_group() : BelongsTo
	{
		return $this->belongsTo(Group::class);
	}

	/**
	 * Get the role records associated with the permission.
	 */
	public function roles() : BelongsToMany
	{
		return $this->belongsToMany(Role::class, 'role_permission');
	}
	
	/**
	 * Set roles for the permission.
	 *
	 * $param 	array 	$roles
	 */
	public function setRoles(array $roles)
	{
		return $this->roles()->sync($roles);
	}
}
