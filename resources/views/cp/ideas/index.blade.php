@extends('_layouts.cp')

@section('title', 'Ideas - '.config('app.name'))
@section('description', 'Ideas - '.config('app.name'))
@section('keywords', 'Ideas, '.config('app.name'))

@push('styles')
	@include('cp._partials.styles')
@endpush

@push('headScripts')
	@include('cp._partials.headScripts')
@endpush

@push('bodyScripts')
	<script async>
	'use strict';
	
	window.CMS.searchBySuggestions = ' Ideas by Departments';
	</script>
	@include('cp._partials.bodyScripts')
@endpush

@section('content')
		@include('cp._partials.sidebar')
		<div class="{{ $mainSmCols }} {{ $mainMdCols }} {{ $mainLgCols }} {{ $mainXlCols }} main">
			@include('cp._partials.message')
			@include('cp._partials.pageTitle')
			@if ($currentUser->hasPermission('create_ideas'))
				<div class="row">
					<div class="col-12 text-center text-sm-center text-md-left text-lg-left text-xl-left">
						<ul class="list-unstyled list-inline buttons">
							<li class="list-inline-item"><a href="/cp/ideas/create" title="Add Idea" class="btn btn-success"><i class="icon fa fa-plus" aria-hidden="true"></i>Add Idea</a></li>
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
							<th class="align-middle d-none d-sm-none d-md-table-cell d-lg-table-cell d-xl-table-cell">Submitted By</th>
							<th class="align-middle no-sort">&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($ideas as $idea)
							<tr class="{{ str_slug($idea->status->title) }}">
								<td class="align-middle status text-center"><i class="fa fa-circle fa-1 status_id-{{ $idea->status->id }}" title="{{ $idea->status->title }}" data-toggle="tooltip" data-placement="top" aria-hidden="true"></i></td>
								<td class="align-middle d-none d-sm-none d-md-table-cell d-lg-table-cell d-xl-table-cell text-center image-cell">
									@if (!empty($idea->image->url))
										<img src="{{ $idea->image->url }}" alt="{{ $idea->title }}" class="img-fluid rounded">
									@endif
								</td>
								<td class="align-middle" data-search="{{ $idea->status->title }} {{ implode(', ', $idea->departments->pluck('title')->toArray()) }}">{{ $idea->title }}</td>
								<td class="align-middle d-none d-sm-none d-md-table-cell d-lg-table-cell d-xl-table-cell">{{ str_limit(strip_tags($idea->description), 200, '...') }}</td>
								<td class="align-middle d-none d-sm-none d-md-table-cell d-lg-table-cell d-xl-table-cell">{{ $idea->submitted_by }}</td>
								@if ($currentUser->hasPermission('edit_ideas') || $currentUser->hasPermission('delete_ideas'))
									<td class="align-middle actions dropdown text-center" id="submenu">
										<a href="javascript:void(0);" title="Update Actions" rel="nofollow" class="dropdown-toggle needsclick" id="pageActions" data-toggle="dropdown"><i class="fa fa-ellipsis-v" aria-hidden="true"></i></a>
										<ul class="dropdown-menu dropdown-menu-right">
											@if ($currentUser->hasPermission('edit_ideas'))
												<li class="dropdown-item text-safetynet-orange"><a href="/cp/ideas/{{ $idea->id }}/edit" title="Edit Idea"><i class="icon fa fa-pencil" aria-hidden="true"></i>Edit Idea</a></li>
											@endif
											@if ($currentUser->hasPermission('delete_ideas'))
												<li class="dropdown-item text-safetynet-orange"><a href="/cp/ideas/{{ $idea->id }}/delete" title="Delete Idea"><i class="icon fa fa-trash" aria-hidden="true"></i>Delete Idea</a></li>
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
