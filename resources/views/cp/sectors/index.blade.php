@extends('_layouts.cp')

@section('title', 'Sectors - '.config('app.name'))
@section('description', 'Sectors - '.config('app.name'))
@section('keywords', 'Sectors, '.config('app.name'))

@push('styles')
	@include('cp._partials.styles')
@endpush

@push('headScripts')
	@include('cp._partials.headScripts')
@endpush

@push('bodyScripts')
	<script async>
	'use strict';
	
	window.CMS.searchBySuggestions = ' Sectors by Departments, Categories & Products';
	</script>
	@include('cp._partials.bodyScripts')
@endpush

@section('content')
		@include('cp._partials.sidebar')
		<div class="{{ $mainSmCols }} {{ $mainMdCols }} {{ $mainLgCols }} {{ $mainXlCols }} main">
			@include('cp._partials.message')
			@include('cp._partials.pageTitle')
			@if ($currentUser->hasPermission('create_sectors'))
				<div class="row">
					<div class="col-12 text-center text-sm-center text-md-left text-lg-left text-xl-left">
						<ul class="list-unstyled list-inline buttons">
							<li class="list-inline-item"><a href="/cp/sectors/create" title="Add Sector" class="btn btn-success"><i class="icon fa fa-plus" aria-hidden="true"></i>Add Sector</a></li>
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
							<th class="align-middle d-none d-sm-none d-md-table-cell d-lg-table-cell d-xl-table-cell">Colour</th>
							<th class="align-middle no-sort">&nbsp;</th>
						</tr>
					</thead>
					<tbody {!! ($currentUser->hasPermission('edit_sectors')) ? 'id="sortable" class="sectors"' : '' !!}>
						@foreach ($sectors as $sector)
							<tr id="{{ $sector->id }}" class="{{ str_slug($sector->status->title) }}">
								<td class="align-middle status text-center"><i class="fa fa-circle fa-1 status_id-{{ $sector->status->id }}" title="{{ $sector->status->title }}" data-toggle="tooltip" data-placement="top" aria-hidden="true"></i></td>
								<td class="align-middle d-none d-sm-none d-md-table-cell d-lg-table-cell d-xl-table-cell text-center image-cell">
									@if (!empty($sector->image->url))
										<img src="{{ $sector->image->url }}" alt="{{ $sector->title }}" class="img-fluid rounded">
									@endif
								</td>
								<td class="align-middle" data-search="{{ $sector->status->title }} {{ implode(', ', $sector->departments->pluck('title')->toArray()) }} {{ implode(', ', $sector->categories->pluck('title')->toArray()) }} {{ implode(', ', $sector->products->pluck('title')->toArray()) }}">{{ $sector->title }}</td>
								<td class="align-middle d-none d-sm-none d-md-table-cell d-lg-table-cell d-xl-table-cell">{{ str_limit($sector->description, 200, '...') }}</td>
								<td class="align-middle d-none d-sm-none d-md-table-cell d-lg-table-cell d-xl-table-cell text-center"><i class="fa fa-circle fa-1" style="color: {{ $sector->colour }}" aria-hidden="true"></i></td>
								@if ($currentUser->hasPermission('edit_sectors') || $currentUser->hasPermission('delete_sectors'))
									<td class="align-middle actions dropdown text-center" id="submenu">
										<a href="javascript:void(0);" title="Sector Actions" rel="nofollow" class="dropdown-toggle needsclick" id="pageActions" data-toggle="dropdown"><i class="fa fa-ellipsis-v" aria-hidden="true"></i></a>
										<ul class="dropdown-menu dropdown-menu-right">
											@if ($currentUser->hasPermission('edit_sectors'))
												<li class="dropdown-item text-safetynet-orange"><a href="/cp/sectors/{{ $sector->id }}/edit" title="Edit Sector"><i class="icon fa fa-pencil" aria-hidden="true"></i>Edit Sector</a></li>
											@endif
											@if ($currentUser->hasPermission('delete_sectors'))
												<li class="dropdown-item text-safetynet-orange"><a href="/cp/sectors/{{ $sector->id }}/delete" title="Delete Sector"><i class="icon fa fa-trash" aria-hidden="true"></i>Delete Sector</a></li>
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
