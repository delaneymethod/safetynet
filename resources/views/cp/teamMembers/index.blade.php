@extends('_layouts.cp')

@section('title', 'Team Members - '.config('app.name'))
@section('description', 'Team Members - '.config('app.name'))
@section('keywords', 'Team Members, '.config('app.name'))

@push('styles')
	@include('cp._partials.styles')
@endpush

@push('headScripts')
	@include('cp._partials.headScripts')
@endpush

@push('bodyScripts')
	@include('cp._partials.bodyScripts')
@endpush

@section('content')
		@include('cp._partials.sidebar')
		<div class="{{ $mainSmCols }} {{ $mainMdCols }} {{ $mainLgCols }} {{ $mainXlCols }} main">
			@include('cp._partials.message')
			@include('cp._partials.pageTitle')
			@if ($currentUser->hasPermission('create_team_members'))
				<div class="row">
					<div class="col-12 text-center text-sm-center text-md-left text-lg-left text-xl-left">
						<ul class="list-unstyled list-inline buttons">
							<li class="list-inline-item"><a href="/cp/team-members/create" title="Add Team Member" class="btn btn-success"><i class="icon fa fa-plus" aria-hidden="true"></i>Add Team Member</a></li>
						</ul>
					</div>
				</div>
			@endif
			<div class="content padding bg-white">
				<div class="spacer"></div>
				<table id="datatable" class="table table-hover" cellspacing="0" border="0" cellpadding="0" width="100%">
					<thead>
						<tr>
							<th class="align-middle no-sort">&nbsp;</th>
							<th class="align-middle">Full Name</th>
							<th class="align-middle">Job Title</th>
							<th class="align-middle no-sort">&nbsp;</th>
						</tr>
					</thead>
					<tbody {!! ($currentUser->hasPermission('edit_team_members')) ? 'id="sortable" class="team-members"' : '' !!}>
						@foreach ($teamMembers as $teamMember)
							<tr id="{{ $teamMember->id }}">
								<td class="align-middle status text-center"><i class="fa fa-circle fa-1 status_id-{{ $teamMember->status->id }}" title="{{ $teamMember->status->title }}" data-toggle="tooltip" data-placement="top" aria-hidden="true"></i></td>
								<td class="align-middle" data-search="{{ $teamMember->status->title }}">{{ $teamMember->full_name }}</td>
								<td class="align-middle">{{ $teamMember->job_title }}</td>
								@if ($currentUser->hasPermission('edit_team_members') || $currentUser->hasPermission('delete_team_members'))
									<td class="align-middle actions dropdown text-center" id="submenu">
										<a href="javascript:void(0);" title="Team Member Actions" rel="nofollow" class="dropdown-toggle needsclick" id="pageActions" data-toggle="dropdown"><i class="fa fa-ellipsis-v" aria-hidden="true"></i></a>
										<ul class="dropdown-menu dropdown-menu-right">
											@if ($currentUser->hasPermission('edit_team_members'))
												<li class="dropdown-item text-safetynet-orange"><a href="/cp/team-members/{{ $teamMember->id }}/edit" title="Edit Team Member"><i class="icon fa fa-pencil" aria-hidden="true"></i>Edit Team Member</a></li>
											@endif
											@if ($currentUser->hasPermission('delete_team_members'))
												<li class="dropdown-item text-safetynet-orange"><a href="/cp/team-members/{{ $teamMember->id }}/delete" title="Delete Team Member"><i class="icon fa fa-trash" aria-hidden="true"></i>Delete Team Member</a></li>
											@endif
										</ul>
									</td>
								@else
									<td class="align-middle">&nbsp;</td>
								@endif
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
			@include('cp._partials.footer')
		</div>
@endsection
