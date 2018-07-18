@extends('_layouts.cp')

@section('title', 'Globals - '.config('app.name'))
@section('description', 'Globals - '.config('app.name'))
@section('keywords', 'Globals, '.config('app.name'))

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
			@if ($currentUser->hasPermission('create_globals'))
				<div class="row">
					<div class="col-12 text-center text-sm-center text-md-left text-lg-left text-xl-left">
						<ul class="list-unstyled list-inline buttons">
							<li class="list-inline-item"><a href="/cp/globals/create" title="Add Global" class="btn btn-success"><i class="icon fa fa-plus" aria-hidden="true"></i>Add Global</a></li>
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
							<th class="align-middle d-none d-sm-none d-md-none d-lg-table-cell d-xl-table-cell">Handle</th>
							<th class="align-middle d-none d-sm-none d-md-table-cell d-lg-table-cell d-xl-table-cell">Data</th>
							<th class="align-middle no-sort">&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($globals as $global)
							<tr>
								<td class="align-middle">{{ $global->title }}</td>
								<td class="align-middle d-none d-sm-none d-md-none d-lg-table-cell d-xl-table-cell">{{ $global->handle }}</td>
								<td class="align-middle d-none d-sm-none d-md-table-cell d-lg-table-cell d-xl-table-cell">{{ $global->data }}</td>
								@if ($currentUser->hasPermission('edit_globals') || $currentUser->hasPermission('delete_globals'))
									<td class="align-middle actions dropdown text-center" id="submenu">
										<a href="javascript:void(0);" title="Global Actions" rel="nofollow" class="dropdown-toggle needsclick" id="pageActions" data-toggle="dropdown"><i class="fa fa-ellipsis-v" aria-hidden="true"></i></a>
										<ul class="dropdown-menu dropdown-menu-right">
											@if ($currentUser->hasPermission('edit_globals'))
												<li class="dropdown-item text-safetynet-orange"><a href="/cp/globals/{{ $global->id }}/edit" title="Edit Global"><i class="icon fa fa-pencil" aria-hidden="true"></i>Edit Global</a></li>
											@endif
											@if ($currentUser->hasPermission('delete_globals'))
												<li class="dropdown-item text-safetynet-orange"><a href="/cp/globals/{{ $global->id }}/delete" title="Delete Global"><i class="icon fa fa-trash" aria-hidden="true"></i>Delete Global</a></li>
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
