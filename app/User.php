<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App;

use Illuminate\Notifications\Notifiable;
use App\Notifications\SetPasswordNotification;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\{HasMany, BelongsTo};
use App\Models\{Role, Status, Session, Country, Location, RefreshToken};

class User extends Authenticatable
{
	use Notifiable;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'first_name',
		'last_name',
		'email',
		'telephone',
		'password',
		'image',
		'skype',
		'job_title',
		'bio',		
		'status_id',
		'role_id',
		'location_id',
		'country_id',
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 
		'remember_token',
	];
	
	/**
     * Attributes that get appended on serialization
     *
     * @var array
     */
	protected $appends = [
		'gravatar',
		'location_postal_address',
	];
	
	/**
	 * Get the location postal address
	 *
	 * @return 	string
	 */
	public function getLocationPostalAddressAttribute() : string
	{
		$locationPostalAddress = explode(',', $this->location->postal_address);
		
		$locationPostalAddress = array_map('trim', $locationPostalAddress);
		
		// $locationPostalAddress = array_merge([], [$this->location->title], $locationPostalAddress);
		
		return implode('<br>', $locationPostalAddress);
	}
	
	/**
	 * Get the role record associated with the user.
	 */
	public function role() : BelongsTo
	{
		return $this->belongsTo(Role::class);
	}
	
	/**
	 * Get the status record associated with the user.
	 */
	public function status() : BelongsTo
	{
		return $this->belongsTo(Status::class);
	}
	
	/**
	 * Get the location record associated with the user.
	 */
	public function location() : BelongsTo
	{
		return $this->belongsTo(Location::class);
	}
	
	/**
	 * Get the sessions records associated with the user.
	 */
	public function sessions() : HasMany
	{
		return $this->hasMany(Session::class);
	}
	
	/**
	 * Get the refresh token records associated with the user.
	 */
	public function refreshTokens() : HasMany
	{
		return $this->hasMany(RefreshToken::class);
	}
	
	/**
	 * Find out if user has a specific permission
	 *
	 * $param 	string 		$permission
	 * $return 	boolean
	 */
	public function hasPermission(string $permission) : bool
	{
		return in_array($permission, $this->role->permissions->pluck('title')->toArray());
	}
	
	/**
	 * Checks if user is a super admin.
	 *
	 * @return bool
	 */
	public function isSuperAdmin() : bool
	{
		return $this->role_id == 1;
	}
	
	/**
	 * Checks if user is a admin.
	 *
	 * @return bool
	 */
	public function isAdmin() : bool
	{
		return $this->role_id == 2;
	}
	
	/**
	 * Checks if user is an end user.
	 *
	 * @return bool
	 */
	public function isTeamMember() : bool
	{
		return $this->role_id > 2;
	}
	
	/**
	 * Checks if user is active.
	 *
	 * @return bool
	 */
	public function isActive() : bool
	{
		return $this->status_id == 1;
	}
	
	/**
	 * Checks if user is in active.
	 *
	 * @return bool
	 */
	public function isInactive() : bool
	{
		return $this->status_id == 2;
	}
	
	/**
	 * Route notifications for the mail channel.
	 *
	 * @return string
	 */
	public function routeNotificationForMail() : string
	{
		return $this->email;
	}
	
	/**
	 * Send the password reset notification.
	 *
	 * @param  string  $token
	 * @return void
	 */
	public function sendPasswordResetNotification($token)
	{
		$this->notify(new SetPasswordNotification($token, $this->first_name));
	}
	
	/**
	 * Get the users gravatar
	 *
	 * @return 	string
	 */
	public function getGravatarAttribute() : string
	{
		return $this->getGravatar($this->email, 30);
	}
	
	private function getGravatar($email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = []) 
	{
		$email = md5(strtolower(trim($email)));
		
		$url = 'https://www.gravatar.com/avatar/'.$email.'?s='.$s.'&d='.$d.'&r='.$r;
	
		if ($img) {
			$url = '<img src="'.$url.'"';
	
			foreach ($atts as $key => $val) {
				$url .= ' '.$key.'="'.$val.'"';
			}
			
			$url .= ' />';
		}
		
		return $url;
	}
}
