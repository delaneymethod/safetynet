@extends('_layouts.cp')

@section('title', 'Models - '.config('app.name'))
@section('description', 'Models - '.config('app.name'))
@section('keywords', 'Models, '.config('app.name'))

@push('styles')
	@include('cp._partials.styles')
@endpush

@push('headScripts')
	@include('cp._partials.headScripts')
@endpush

@push('bodyScripts')
	<script async>
	'use strict';
	
	window.CMS.searchBySuggestions = ' Models by Products';
	</script>
	@include('cp._partials.bodyScripts')
@endpush

@section('content')
		@include('cp._partials.sidebar')
		<div class="{{ $mainSmCols }} {{ $mainMdCols }} {{ $mainLgCols }} {{ $mainXlCols }} main">
			@include('cp._partials.message')
			@include('cp._partials.pageTitle')
			@if ($currentUser->hasPermission('create_models'))
				<div class="row">
					<div class="col-12 text-center text-sm-center text-md-left text-lg-left text-xl-left">
						<ul class="list-unstyled list-inline buttons">
							<li class="list-inline-item"><a href="/cp/models/create" title="Add Model" class="btn btn-success"><i class="icon fa fa-plus" aria-hidden="true"></i>Add Model</a></li>
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
							<th class="align-middle no-sort">&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($models as $model)
							<tr>
								<td class="align-middle status text-center"><i class="fa fa-circle fa-1 status_id-{{ $model->status->id }}" title="{{ $model->status->title }}" data-toggle="tooltip" data-placement="top" aria-hidden="true"></i></td>
								<td class="align-middle" data-search="{{ $model->status->title }} {{ implode(', ', $model->products->pluck('title')->toArray()) }}">{{ $model->title }}</td>
								@if ($currentUser->hasPermission('edit_models') || $currentUser->hasPermission('delete_models'))
									<td class="align-middle actions dropdown text-center" id="submenu">
										<a href="javascript:void(0);" title="Model Actions" rel="nofollow" class="dropdown-toggle needsclick" id="pageActions" data-toggle="dropdown"><i class="fa fa-ellipsis-v" aria-hidden="true"></i></a>
										<ul class="dropdown-menu dropdown-menu-right">
											@if ($currentUser->hasPermission('edit_models'))
												<li class="dropdown-item text-safetynet-orange"><a href="/cp/models/{{ $model->id }}/edit" title="Edit Model"><i class="icon fa fa-pencil" aria-hidden="true"></i>Edit Model</a></li>
											@endif
											@if ($currentUser->hasPermission('delete_models'))
												<li class="dropdown-item text-safetynet-orange"><a href="/cp/models/{{ $model->id }}/delete" title="Delete Model"><i class="icon fa fa-trash" aria-hidden="true"></i>Delete Model</a></li>
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
