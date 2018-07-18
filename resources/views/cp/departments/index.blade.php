@extends('_layouts.cp')

@section('title', 'Departments - '.config('app.name'))
@section('description', 'Departments - '.config('app.name'))
@section('keywords', 'Departments, '.config('app.name'))

@push('styles')
	@include('cp._partials.styles')
@endpush

@push('headScripts')
	@include('cp._partials.headScripts')
@endpush

@push('bodyScripts')
	<script async>
	'use strict';
	
	window.CMS.searchBySuggestions = ' Departments by Categories & Products';
	</script>
	@include('cp._partials.bodyScripts')
@endpush

@section('content')
		@include('cp._partials.sidebar')
		<div class="{{ $mainSmCols }} {{ $mainMdCols }} {{ $mainLgCols }} {{ $mainXlCols }} main">
			@include('cp._partials.message')
			@include('cp._partials.pageTitle')
			@if ($currentUser->hasPermission('create_departments'))
				<div class="row">
					<div class="col-12 text-center text-sm-center text-md-left text-lg-left text-xl-left">
						<ul class="list-unstyled list-inline buttons">
							<li class="list-inline-item"><a href="/cp/departments/create" title="Add Department" class="btn btn-success"><i class="icon fa fa-plus" aria-hidden="true"></i>Add Department</a></li>
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
							<th class="align-middle">Title</th>
							<th class="align-middle d-none d-sm-none d-md-table-cell d-lg-table-cell d-xl-table-cell">Description</th>
							<th class="align-middle no-sort">&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($departments as $department)
							<tr class="{{ str_slug($department->status->title) }}">
								<td class="align-middle status text-center"><i class="fa fa-circle fa-1 status_id-{{ $department->status->id }}" title="{{ $department->status->title }}" data-toggle="tooltip" data-placement="top" aria-hidden="true"></i></td>
								<td class="align-middle d-none d-sm-none d-md-table-cell d-lg-table-cell d-xl-table-cell text-center image-cell">
									@if (!empty($department->image->url))
										<img src="{{ $department->image->url }}" alt="{{ $department->title }}" class="img-fluid rounded">
									@endif
								</td>
								<td class="align-middle" data-search="{{ $department->status->title }} {{ implode(', ', $department->sectors->pluck('title')->toArray()) }} {{ implode(', ', $department->products->pluck('title')->toArray()) }}">{{ $department->title }}</td>
								<td class="align-middle d-none d-sm-none d-md-table-cell d-lg-table-cell d-xl-table-cell">{{ str_limit($department->description, 200, '...') }}</td>
								@if ($currentUser->hasPermission('edit_departments') || $currentUser->hasPermission('delete_departments'))
									<td class="align-middle actions dropdown text-center" id="submenu">
										<a href="javascript:void(0);" title="Department Actions" rel="nofollow" class="dropdown-toggle needsclick" id="pageActions" data-toggle="dropdown"><i class="fa fa-ellipsis-v" aria-hidden="true"></i></a>
										<ul class="dropdown-menu dropdown-menu-right">
											@if ($currentUser->hasPermission('edit_departments'))
												<li class="dropdown-item text-safetynet-orange"><a href="/cp/departments/{{ $department->id }}/edit" title="Edit Department"><i class="icon fa fa-pencil" aria-hidden="true"></i>Edit Department</a></li>
											@endif
											@if ($currentUser->hasPermission('delete_departments'))
												<li class="dropdown-item text-safetynet-orange"><a href="/cp/departments/{{ $department->id }}/delete" title="Delete Department"><i class="icon fa fa-trash" aria-hidden="true"></i>Delete Department</a></li>
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
