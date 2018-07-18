<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Http\Controllers\Auth;

use DB;
use Log;
use Exception;
use GuzzleHttp\Psr7;
use GuzzleHttp\Client;
use App\Models\RefreshToken;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\RefreshTokenTrait;
use Illuminate\Database\QueryException;
use GuzzleHttp\Exception\{ClientException, RequestException};

class SharepointLoginController extends Controller
{
	use RefreshTokenTrait;
	
	protected $verify;
	
	protected $client;
	
	protected $siteUrl;
	
	protected $siteName;
	
	protected $clientId;
	
	protected $expiresAt;
	
	protected $redirectUri;
	
	protected $accessToken;
	
	protected $clientSecret;
	
	protected $refreshToken;
	
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
		
		$config = config('filesystems.disks.sharepoint');	
		
		$this->verify = $config['verify'];
		
		$this->siteUrl = $config['site_url'];
		
		$this->siteName = $config['site_name'];
		
		$this->clientId = $config['client_id'];
		
		$this->redirectUri = $config['redirect_uri'];
		
		$this->clientSecret = $config['client_secret'];
		
		$this->client = new Client([
			'verify' => $this->verify,
		]);
	}

	/**
	 * Redirects the user to the provider authentication page 
	 * 
	 * See: https://docs.microsoft.com/en-us/sharepoint/dev/sp-add-ins/authorization-code-oauth-flow-for-sharepoint-add-ins#Scope
	 *
	 * - User authorises.
	 * - User get back an auth code which allows us to get a access token.
	 * - User makes API requests using access token.
	 *
	 * @params	Request 	$request
	 * @return 	Response
	 */
   	public function index(Request $request)
	{
		return redirect($this->siteUrl.'/sites/'.$this->siteName.'/_layouts/15/OAuthAuthorize.aspx?client_id='.$this->clientId.'&response_type=code&scope=List.Write&redirect_uri='.$this->redirectUri);
	}
	
	/**
	 * Obtain the user information from the provider
	 *
	 * @return Response
	 */
	public function callback(Request $request)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		// Short life and determines who the user is and what access they have.
		$authCode = $request->get('code');
		
		if (!empty($authCode)) {
			DB::beginTransaction();
			
			try {
				// Longer life (6 months)
				$this->refreshToken = $this->getRefreshTokenFromAuthCode($authCode);
				
				// Wipe all old refresh tokens
				$refreshToken = $this->getRefreshTokenByUserIdService($currentUser->id, 'sharepoint');
				
				if ($refreshToken) {
					$refreshToken->forceDelete();
				}
				
				// Create new model
				$refreshToken = new RefreshToken;
				
				// Set our field data
				$refreshToken->service = 'sharepoint';
				$refreshToken->value = $this->refreshToken;
				$refreshToken->user_id = $currentUser->id;
					
				$refreshToken->save();
				
				// Short life (1 hour)
				$sharepoint = [
					'user_id' => $currentUser->id,
					'expires_at' => $this->expiresAt,
					'access_token' => $this->accessToken,
					'refresh_token' => $this->refreshToken,
				];
				
				$request->session()->forget('oauth.sharepoint');
				
				$request->session()->put('oauth.sharepoint', $sharepoint);
			} catch (QueryException $queryException) {
				DB::rollback();
			
				Log::info('SQL: '.$queryException->getSql());

				Log::info('Bindings: '.implode(', ', $queryException->getBindings()));

				abort(500, $queryException);
			} catch (Exception $exception) {
				DB::rollback();

				abort(500, $exception);
			}

			DB::commit();
			
			return redirect('/cp/dashboard');
		}
		
		abort(404, 'Authorisation code not found');
	}
	
	/**
	 * Obtain a new access token using refresh token
	 *
	 * @return Response
	 */
	public function refresh(Request $request)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		$refreshToken = $request->session()->get('oauth.sharepoint.refresh_token');
		
		// If we have no refresh token value, somethings not right so probably best to start over.
		if (empty($refreshToken)) {
			// We have a refresh token so we can continue and get a new access token
			return redirect('/login/sharepoint');
		} else {
			// Short life (1 hour)
			$accessToken = $this->getAccessToken($refreshToken);
		
			$sharepoint = [
				'user_id' => $currentUser->id,
				'expires_at' => $this->expiresAt,
				'access_token' => $accessToken,
				'refresh_token' => $refreshToken,
			];
				
			$request->session()->forget('oauth.sharepoint');
				
			$request->session()->put('oauth.sharepoint', $sharepoint);
		
			return redirect('/cp/dashboard');
		}
	}
	
	/**
	 * Get refresh token based on auth_code, obtained by user consent
	 *
	 * @param string $auth_code
	 *
	 * @return string
	 * @throws Exception
	 */
	private function getRefreshTokenFromAuthCode($authCode)
	{
		return $this->getOAuthToken('authorization_code', $authCode, 'refresh_token');
	}
	
	/**
	 * Get new short-lived access token based on refresh token
	 *
	 * @param string $refresh_token
	 *
	 * @return string
	 */
	private function getAccessToken($refreshToken) 
	{
		return $this->getOAuthToken('refresh_token', $refreshToken, 'access_token');
	}
	
	/**
	 * Make OAuth request for access and refresh tokens
	 *
	 * Supports request based on auth_code or refresh_token
	 *
	 * @param string $grant_type [ 'authorization_code', 'refresh_token' ]
	 * @param string $auth_principal
	 * @param string $result_property Proprty of response, which to return
	 *
	 * @return string
	 * @throws Exception
	 */
	private function getOAuthToken($grantType, $authPrincipal, $resultProperty) 
	{
		try {
			$realmId = $this->getRealm();
		
			$rootSiteHost = parse_url($this->siteUrl, PHP_URL_HOST);
		
			$data = [];
			
			$data['form_params'] = [];
			$data['form_params']['grant_type'] = $grantType;
			$data['form_params']['client_id'] = $this->clientId.'@'.$realmId;
			$data['form_params']['client_secret'] = $this->clientSecret;
			$data['form_params']['redirect_uri'] = $this->redirectUri;
			$data['form_params']['resource'] = '00000003-0000-0ff1-ce00-000000000000/'.$rootSiteHost.'@'.$realmId;
			
			if ($grantType == 'authorization_code') {
				$data['form_params']['code'] = $authPrincipal;
			} elseif ($grantType == 'refresh_token') {
				$data['form_params']['refresh_token'] = $authPrincipal;
			} else {
				throw new Exception('Unknown grant type');
			}
			
			$url = 'https://accounts.accesscontrol.windows.net/'.$realmId.'/tokens/OAuth/2';
			
			$response = $this->client->request('POST', $url, $data);
		
			$response = json_decode($response->getBody());
		
			if (!property_exists($response, $resultProperty)) {
				throw new Exception('Required tokens are not present in OAuth response.');
			}
			
			if (!empty($response->expires_on)) {
				$this->expiresAt = $response->expires_on;
			}
			
			if (!empty($response->refresh_token)) {
				$this->refreshToken = $response->refresh_token;
			}
			
			if (!empty($response->access_token)) {
				$this->accessToken = $response->access_token;
			}
			
			return $response->$resultProperty;
		} catch (RequestException $requestException) {
			throw new Exception(Psr7\str($requestException->getResponse()));
		} catch (ClientException $clientException) {
			throw new Exception(Psr7\str($clientException->getResponse()));
		}
	}
	
	/**
	 * Get Realm ID from unauthorized request headers to client.svc
	 *
	 * @return string
	 * @throws Exception
	 */
	private function getRealm() 
	{
		try {
			$url = $this->siteUrl.'/_vti_bin/client.svc';
			
			$response = $this->client->request('GET', $url, [
				'http_errors' => false,
				'headers' => [
					'Authorization' => 'Bearer'
				]
			]);
			
			$autheticateHeader = $response->getHeader('WWW-Authenticate');
		
			if (empty($autheticateHeader)) {
				throw new Exception('Invalid response for Realm lookup.');
			}
		
			preg_match('/realm\=\"(.+?)\"/i', $autheticateHeader[0], $realm);
		
			if (!isset($realm[1])) {
				throw new Exception('Invalid response for Realm lookup. No Realm ID in the response header.');
			}
		
			return $realm[1];
		} catch (RequestException $requestException) {
			throw new Exception(Psr7\str($requestException->getResponse()));
		} catch (ClientException $clientException) {
			throw new Exception(Psr7\str($clientException->getResponse()));
		}
	}
}
