<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Http\Controllers;

use DB;
use Log;
use Exception;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use App\Http\Traits\{StatusTrait, LocationTrait, TeamMemberTrait};

class TeamMemberController extends Controller
{
	use StatusTrait, LocationTrait, TeamMemberTrait;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->middleware('auth');
		
		$this->middleware('auth.accessToken');
		
		$this->cacheKey = 'team_members';
	}
	
	/**
	 * Get templates view.
	 *
	 * @params	Request 	$request
	 * @return 	Response
	 */
   	public function index(Request $request)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('view_team_members')) {
			$title = 'Team Members';
			
			$subTitle = '';
			
			$leadParagraph = '';
			
			$teamMembers = $this->getCache($this->cacheKey);
			
			if (is_null($teamMembers)) {
				$teamMembers = $this->getTeamMembersByOrder();
				
				$this->setCache($this->cacheKey, $teamMembers);
			}
			
			$this->mapImagesToAssets($teamMembers);
			
			return view('cp.teamMembers.index', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'teamMembers'));
		}
		
		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Shows a form for creating a new team member.
	 *
	 * @params	Request 	$request
	 * @return 	Response
	 */
	public function create(Request $request)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('create_team_members')) {
			$title = 'Create Team Member';
		
			$subTitle = 'Team Members';
			
			$leadParagraph = '.';
			
			// Used to set status_id
			$statuses = $this->getData('getStatuses');
			
			// Used to set location_id
			$locations = $this->getData('getLocations', 'locations');
			
			return view('cp.teamMembers.create', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'statuses', 'locations'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Creates a new team member.
	 *
	 * @params Request 	$request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$currentUser = $this->getAuthenticatedUser();

		if ($currentUser->hasPermission('create_team_members')) {
			// Remove any Cross-site scripting (XSS)
			$cleanedTeamMember = $this->sanitizerInput($request->all());
			
			$rules = $this->getRules('team_member');
			
			// Make sure all the input data is what we actually save
			$validator = $this->validatorInput($cleanedTeamMember, $rules);
			
			if ($validator->fails()) {
				dd($validator->errors());
				return back()->withErrors($validator)->withInput();
			}

			DB::beginTransaction();
			
			try {
				// Create new team member
				$teamMember = new TeamMember;
	
				// Set our field data
				$teamMember->full_name = $cleanedTeamMember['full_name'];
				$teamMember->email = $cleanedTeamMember['email'];
				$teamMember->image = $cleanedTeamMember['image'];
				$teamMember->job_title = $cleanedTeamMember['job_title'];
				$teamMember->bio = $cleanedTeamMember['bio'];
				$teamMember->status_id = $cleanedTeamMember['status_id'];
				$teamMember->location_id = $cleanedTeamMember['location_id'];
				
				$teamMember->save();
				
				$this->setCache($this->cacheKey, $this->getTeamMembers());
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

			flash('Team Member created successfully.', $level = 'success');

			return redirect('/cp/team-members');
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Shows a form for editing a team member.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
	public function edit(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('edit_team_members')) {
			$title = 'Edit Team Member';
			
			$subTitle = 'Team Members';
			
			$leadParagraph = '';
			
			$teamMember = $this->getTeamMember($id);
			
			$this->mapImagesToAssets($teamMember);
			
			// Used to set status_id
			$statuses = $this->getData('getStatuses');
			
			// Used to set location_id
			$locations = $this->getData('getLocations', 'locations');
			
			return view('cp.teamMembers.edit', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'teamMember', 'statuses', 'locations'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Updates a specific team member.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
	public function update(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();

		if ($currentUser->hasPermission('edit_team_members')) {
			// Remove any Cross-site scripting (XSS)
			$cleanedTeamMember = $this->sanitizerInput($request->all());
			
			$rules = $this->getRules('team_member');
			
			$rules['email'] = 'required|email|unique:team_members,email,'.$id.'|max:255';
			
			// Make sure all the input data is what we actually save
			$validator = $this->validatorInput($cleanedTeamMember, $rules);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput();
			}

			DB::beginTransaction();

			try {
				// Get our team member
				$teamMember = $this->getTeamMember($id);
				
				// Set our field data
				$teamMember->full_name = $cleanedTeamMember['full_name'];
				$teamMember->email = $cleanedTeamMember['email'];
				$teamMember->image = $cleanedTeamMember['image'];
				$teamMember->job_title = $cleanedTeamMember['job_title'];
				$teamMember->bio = $cleanedTeamMember['bio'];
				$teamMember->status_id = $cleanedTeamMember['status_id'];
				$teamMember->location_id = $cleanedTeamMember['location_id'];
				$teamMember->updated_at = $this->datetime;
				
				$teamMember->save();
				
				$this->setCache($this->cacheKey, $this->getTeamMembers());
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

			flash('Team Member updated successfully.', $level = 'success');

			return redirect('/cp/team-members');
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Shows a form for deleting a team member.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
	public function confirm(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('delete_team_members')) {
			$teamMember = $this->getTeamMember($id);
			
			$title = 'Delete Team Member';
			
			$subTitle = 'Team Members';
			
			$leadParagraph = '';
			
			return view('cp.teamMembers.delete', compact('currentUser', 'title', 'subTitle', 'leadParagraph', 'teamMember'));
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Deletes a specific team member.
	 *
	 * @params	Request 	$request
	 * @param	int			$id
	 * @return 	Response
	 */
	public function delete(Request $request, int $id)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('delete_team_members')) {
			$teamMember = $this->getTeamMember($id);
		
			DB::beginTransaction();

			try {
				$teamMember->delete();
				
				$this->setCache($this->cacheKey, $this->getTeamMembers());
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

			flash('Team Member deleted successfully.', $level = 'info');

			return redirect('/cp/team-members');
		}

		abort(403, 'Unauthorised action');
	}
	
	/**
	 * Sorts team members.
	 *
	 * @params	Request 	$request
	 * @return 	Response
	 */
	public function sort(Request $request)
	{
		$currentUser = $this->getAuthenticatedUser();
		
		if ($currentUser->hasPermission('edit_team_members')) {
			$cleanedTeamMember = $this->sanitizerInput($request->all());
			
			DB::beginTransaction();

			try {
				TeamMember::setNewOrder($cleanedTeamMember['order']);
				
				$this->setCache($this->cacheKey, $this->getTeamMembers());
			} catch (QueryException $queryException) {
				DB::rollback();
			
				Log::info('SQL: '.$queryException->getSql());

				Log::info('Bindings: '.implode(', ', $queryException->getBindings()));

				return response()->json([
					'error' => true,
					'queryException' => true,
					'message' => $queryException->getMessage()
				]);
			} catch (Exception $exception) {
				DB::rollback();

				return response()->json([
					'error' => true,
					'exception' => true,
					'message' => $exception->getMessage()
				]);
			}

			DB::commit();

			return response()->json([
				'message' => 'Order successfully saved.'
			]);
		} else {
			abort(403, 'Unauthorised action');
		}
	}
}
