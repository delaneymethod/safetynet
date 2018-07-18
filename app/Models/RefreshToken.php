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

class RefreshToken extends Model
{
	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'refresh_tokens';
    
	protected $characterSet = 'UTF-8';
	
	protected $flags = ENT_QUOTES;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'id',
		'service',
		'value',
		'user_id',
	];

	/**
	 * Get the user records associated with the access token.
	 */
	public function user() : BelongsTo
	{
		return $this->belongsTo(User::class);
	}
}
