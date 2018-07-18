@extends('_layouts.cp')

@section('title', 'Events - '.config('app.name'))
@section('description', 'Events - '.config('app.name'))
@section('keywords', 'Events, '.config('app.name'))

@push('styles')
	@include('cp._partials.styles')
@endpush

@push('headScripts')
	@include('cp._partials.headScripts')
@endpush

@push('bodyScripts')
	<script async>
	'use strict';
	
	window.CMS.searchBySuggestions = ' Events by Date / Time & Sectors';
	</script>
	@include('cp._partials.bodyScripts')
@endpush

@section('content')
		@include('cp._partials.sidebar')
		<div class="{{ $mainSmCols }} {{ $mainMdCols }} {{ $mainLgCols }} {{ $mainXlCols }} main">
			@include('cp._partials.message')
			@include('cp._partials.pageTitle')
			@if ($currentUser->hasPermission('create_events'))
				<div class="row">
					<div class="col-12 text-center text-sm-center text-md-left text-lg-left text-xl-left">
						<ul class="list-unstyled list-inline buttons">
							<li class="list-inline-item"><a href="/cp/events/create" title="Add Event" class="btn btn-success"><i class="icon fa fa-plus" aria-hidden="true"></i>Add Event</a></li>
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
							<th class="align-middle">Title</th>
							<th class="align-middle d-none d-sm-none d-md-table-cell d-lg-table-cell d-xl-table-cell">Description</th>
							<th class="align-middle no-sort">&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($events as $event)
							<tr>
								<td class="align-middle status text-center"><i class="fa fa-circle fa-1 status_id-{{ $event->status->id }}" title="{{ $event->status->title }}" data-toggle="tooltip" data-placement="top" aria-hidden="true"></i></td>
								<td class="align-middle" data-search="{{ $event->status->title }} {{ $event->start }} {{ $event->end }} {{ implode(', ', $event->sectors->pluck('title')->toArray()) }}">{{ $event->title }}</td>
								<td class="align-middle d-none d-sm-none d-md-table-cell d-lg-table-cell d-xl-table-cell">{{ str_limit($event->description, 200, '...') }}</td>
								@if ($currentUser->hasPermission('edit_events') || $currentUser->hasPermission('delete_events'))
									<td class="align-middle actions dropdown text-center" id="submenu">
										<a href="javascript:void(0);" title="Event Actions" rel="nofollow" class="dropdown-toggle needsclick" id="pageActions" data-toggle="dropdown"><i class="fa fa-ellipsis-v" aria-hidden="true"></i></a>
										<ul class="dropdown-menu dropdown-menu-right">
											@if ($currentUser->hasPermission('edit_events'))
												<li class="dropdown-item text-safetynet-orange"><a href="/cp/events/{{ $event->id }}/edit" title="Edit Event"><i class="icon fa fa-pencil" aria-hidden="true"></i>Edit Event</a></li>
											@endif
											@if ($currentUser->hasPermission('delete_events'))
												<li class="dropdown-item text-safetynet-orange"><a href="/cp/events/{{ $event->id }}/delete" title="Delete Event"><i class="icon fa fa-trash" aria-hidden="true"></i>Delete Event</a></li>
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
