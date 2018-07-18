@extends('_layouts.cp')

@section('title', 'Roles - Advanced - '.config('app.name'))
@section('description', 'Roles - Advanced - '.config('app.name'))
@section('keywords', 'Roles, Advanced, '.config('app.name'))

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
			@if ($currentUser->hasPermission('create_roles'))
				<div class="row">
					<div class="col-12 text-center text-sm-center text-md-left text-lg-left text-xl-left">
						<ul class="list-unstyled list-inline buttons">
							<li class="list-inline-item"><a href="/cp/advanced/roles/create" title="Add Role" class="btn btn-success"><i class="icon fa fa-plus" aria-hidden="true"></i>Add Role</a></li>
						</ul>
					</div>
				</div>
			@endif
			<div class="content padding bg-white">	
				<div class="spacer"></div>
				<table id="datatable" class="table table-hover" cellspacing="0" border="0" cellpadding="0" width="100%">
					<thead>
						<tr>
							<th class="align-middle">Title</th>
							<th class="align-middle no-sort">&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($roles as $role)
							<tr>
								<td class="align-middle">{{ $role->title }}</td>
								@if ($currentUser->hasPermission('edit_roles') || $currentUser->hasPermission('delete_roles'))
									<td class="align-middle actions dropdown text-center" id="submenu">
										<a href="javascript:void(0);" title="Role Actions" rel="nofollow" class="dropdown-toggle needsclick" id="pageActions" data-toggle="dropdown"><i class="fa fa-ellipsis-v" aria-hidden="true"></i></a>
										<ul class="dropdown-menu dropdown-menu-right">
											@if ($currentUser->hasPermission('edit_roles'))
												<li class="dropdown-item text-safetynet-orange"><a href="/cp/advanced/roles/{{ $role->id }}/edit" title="Edit Role"><i class="icon fa fa-pencil" aria-hidden="true"></i>Edit Role</a></li>
											@endif
											@if ($currentUser->hasPermission('delete_roles'))
												<li class="dropdown-item text-safetynet-orange"><a href="/cp/advanced/roles/{{ $role->id }}/delete" title="Delete Role"><i class="icon fa fa-trash" aria-hidden="true"></i>Delete Role</a></li>
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
