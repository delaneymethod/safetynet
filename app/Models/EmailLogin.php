<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailLogin extends Model
{
	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'email_logins';
    
	protected $characterSet = 'UTF-8';
	
	protected $flags = ENT_QUOTES;

	public $timestamps = false;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'email',
		'token',
	];
	
	/**
	 * Get the user record associated with the email login.
	 */
	public function user() : BelongsTo
	{
		return $this->belongsTo(User::class, 'email', 'email');
	}
}
