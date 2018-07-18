<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Http\Traits;

use App\Models\RefreshToken;
use Illuminate\Database\Eloquent\Collection as CollectionResponse;

trait RefreshTokenTrait
{
	/**
	 * Get the specified refresh token based on id.
	 *
	 * @param 	int 		$id
	 * @return 	Object
	 */
	public function getRefreshToken(int $id) : RefreshToken
	{
		return RefreshToken::findOrFail($id);
	}
	
	/**
	 * Get the specified access token based on user id and service.
	 *
	 * @param 	int 		$id
	 * @param 	string 		$service
	 */
	public function getRefreshTokenByUserIdService(int $userId, string $service)
	{
		return RefreshToken::where('user_id', $userId)->where('service', $service)->first();
	}
	
	/**
	 * Get all the refresh tokens.
	 *
	 * @return 	Response
	 */
	public function getRefreshTokens() : CollectionResponse
	{
		return RefreshToken::all();
	}
}
