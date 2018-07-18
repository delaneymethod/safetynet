@extends('_layouts.cp')

@section('title', 'Users - '.config('app.name'))
@section('description', 'Users - '.config('app.name'))
@section('keywords', 'Users, '.config('app.name'))

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
			@if ($currentUser->hasPermission('create_users'))
				<div class="row">
					<div class="col-12 text-center text-sm-center text-md-left text-lg-left text-xl-left">
						<ul class="list-unstyled list-inline buttons">
							<li class="list-inline-item"><a href="/cp/users/create" title="Add User" class="btn btn-success"><i class="icon fa fa-plus" aria-hidden="true"></i>Add User</a></li>
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
							<th class="align-middle no-sort d-none d-sm-none d-md-table-cell d-lg-table-cell d-xl-table-cell text-center">Image</th>
							<th class="align-middle">Full Name</th>
							<th class="align-middle d-none d-sm-table-cell d-md-table-cell d-lg-table-cell d-xl-table-cell">Email</th>
							<th class="align-middle d-none d-sm-none d-md-table-cell d-lg-table-cell d-xl-table-cell">Role</th>
							<th class="align-middle no-sort">&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($users as $user)
							<tr class="{{ str_slug($user->status->title) }}">
								<td class="align-middle status text-center"><i class="fa fa-circle fa-1 status_id-{{ $user->status->id }}" title="{{ $user->status->title }}" data-toggle="tooltip" data-placement="top" aria-hidden="true"></i></td>
								<td class="align-middle d-none d-sm-none d-md-table-cell d-lg-table-cell d-xl-table-cell text-center image-cell">
									@if (!empty($user->gravatar))
										<img src="{{ $user->gravatar }}" alt="{{ $user->first_name }} {{ $user->last_name }}" class="img-fluid rounded">
									@endif
								</td>
								<td class="align-middle">{{ $user->first_name }} {{ $user->last_name }}</td>
								<td class="d-none d-sm-table-cell d-md-table-cell d-lg-table-cell d-xl-table-cell align-middle"><a href="mailto:{{ $user->email }}" title="Email User" class="d-inline text-text-danger">{{ $user->email }}</a></td>
								<td class="d-none d-sm-none d-md-table-cell d-lg-table-cell d-xl-table-cell align-middle">{{ $user->role->title }}</td>
								@if ($currentUser->isAdmin() && $user->isSuperAdmin())
									<td class="align-middle">&nbsp;</td>
								@else
									<td class="align-middle actions dropdown text-center" id="submenu">
										<a href="javascript:void(0);" title="User Actions" rel="nofollow" class="dropdown-toggle needsclick" id="pageActions" data-toggle="dropdown"><i class="fa fa-ellipsis-v" aria-hidden="true"></i></a>
										<ul class="dropdown-menu dropdown-menu-right">
											@if ($currentUser->hasPermission('edit_users') || $currentUser->id == $user->id)
												<li class="dropdown-item text-safetynet-orange"><a href="/cp/users/{{ $user->id }}/edit" title="View / Edit User"><i class="icon fa fa-pencil" aria-hidden="true"></i>View / Edit User</a></li>
											@endif
											@if ($currentUser->hasPermission('edit_passwords_users') || $currentUser->id == $user->id)
												<li class="dropdown-item text-safetynet-orange"><a href="/cp/users/{{ $user->id }}/edit/password" title="Change User"><i class="icon fa fa-key" aria-hidden="true"></i>Change Password</a></li>
											@endif
											@if ($currentUser->hasPermission('delete_users') && $user->id != $currentUser->id)
												<li class="dropdown-item text-safetynet-orange"><a href="/cp/users/{{ $user->id }}/delete" title="Delete User"><i class="icon fa fa-trash" aria-hidden="true"></i>Delete User</a></li>
											@endif
										</ul>
									</td>
								@endif
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
			@include('cp._partials.footer')
		</div>
@endsection
