<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Models;

use App\User;
use App\Models\Permission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{HasMany, BelongsToMany};

class Role extends Model
{
	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'roles';
    
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
	 * Get the user records associated with the role.
	 */
	public function users() : HasMany
	{
		return $this->hasMany(User::class);
	}
	
	/**
	 * Get the permissions records associated with the role.
	 */
	public function permissions() : BelongsToMany
	{
		return $this->belongsToMany(Permission::class, 'role_permission');
	}
	
	/**
	 * Set permissions for the role.
	 *
	 * $param 	array 	$permissions
	 */
	public function setPermissions(array $permissions)
	{
		return $this->permissions()->sync($permissions);
	}
}
