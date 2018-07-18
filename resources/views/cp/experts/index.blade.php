@extends('_layouts.cp')

@section('title', 'Experts - '.config('app.name'))
@section('description', 'Experts - '.config('app.name'))
@section('keywords', 'Experts, '.config('app.name'))

@push('styles')
	@include('cp._partials.styles')
@endpush

@push('headScripts')
	@include('cp._partials.headScripts')
@endpush

@push('bodyScripts')
	<script async>
	'use strict';
	
	window.CMS.searchBySuggestions = ' Experts by Categories';
	</script>
	@include('cp._partials.bodyScripts')
@endpush

@section('content')
		@include('cp._partials.sidebar')
		<div class="{{ $mainSmCols }} {{ $mainMdCols }} {{ $mainLgCols }} {{ $mainXlCols }} main">
			@include('cp._partials.message')
			@include('cp._partials.pageTitle')
			@if ($currentUser->hasPermission('create_experts'))
				<div class="row">
					<div class="col-12 text-center text-sm-center text-md-left text-lg-left text-xl-left">
						<ul class="list-unstyled list-inline buttons">
							<li class="list-inline-item"><a href="/cp/experts/create" title="Add Expert" class="btn btn-success"><i class="icon fa fa-plus" aria-hidden="true"></i>Add Expert</a></li>
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
							<th class="align-middle">Position</th>
							<th class="align-middle no-sort">&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($experts as $expert)
							<tr>
								<td class="align-middle status text-center"><i class="fa fa-circle fa-1 status_id-{{ $expert->status->id }}" title="{{ $expert->status->title }}" data-toggle="tooltip" data-placement="top" aria-hidden="true"></i></td>
								<td class="align-middle" data-search="{{ $expert->status->title }} {{ implode(', ', $expert->categories->pluck('title')->toArray()) }}">{{ $expert->full_name }}</td>
								<td class="align-middle">{{ $expert->position }}</td>
								@if ($currentUser->hasPermission('edit_experts') || $currentUser->hasPermission('delete_experts'))
									<td class="align-middle actions dropdown text-center" id="submenu">
										<a href="javascript:void(0);" title="Expert Actions" rel="nofollow" class="dropdown-toggle needsclick" id="pageActions" data-toggle="dropdown"><i class="fa fa-ellipsis-v" aria-hidden="true"></i></a>
										<ul class="dropdown-menu dropdown-menu-right">
											@if ($currentUser->hasPermission('edit_experts'))
												<li class="dropdown-item text-safetynet-orange"><a href="/cp/experts/{{ $expert->id }}/edit" title="Edit Expert"><i class="icon fa fa-pencil" aria-hidden="true"></i>Edit Expert</a></li>
											@endif
											@if ($currentUser->hasPermission('delete_experts'))
												<li class="dropdown-item text-safetynet-orange"><a href="/cp/experts/{{ $expert->id }}/delete" title="Delete Expert"><i class="icon fa fa-trash" aria-hidden="true"></i>Delete Expert</a></li>
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
