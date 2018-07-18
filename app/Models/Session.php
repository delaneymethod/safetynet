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

class Session extends Model
{
	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sessions';
    
	protected $characterSet = 'UTF-8';
	
	protected $flags = ENT_QUOTES;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'id',
		'user_id',
		'ip_address',
		'user_agent',
		'payload',
		'last_activity',
	];

	/**
	 * Get the user record associated with the user activation.
	 */
	public function user() : BelongsTo
	{
		return $this->belongsTo(User::class);
	}
}
