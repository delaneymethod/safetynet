@extends('_layouts.cp')

@section('title', 'Categories - '.config('app.name'))
@section('description', 'Categories - '.config('app.name'))
@section('keywords', 'Categories, '.config('app.name'))

@push('styles')
	@include('cp._partials.styles')
@endpush

@push('headScripts')
	@include('cp._partials.headScripts')
@endpush

@push('bodyScripts')
	<script async>
	'use strict';
	
	window.CMS.searchBySuggestions = ' Categories by Sectors & Content Types';
	</script>
	@include('cp._partials.bodyScripts')
@endpush

@section('content')
		@include('cp._partials.sidebar')
		<div class="{{ $mainSmCols }} {{ $mainMdCols }} {{ $mainLgCols }} {{ $mainXlCols }} main">
			@include('cp._partials.message')
			@include('cp._partials.pageTitle')
			@if ($currentUser->hasPermission('create_categories'))
				<div class="row">
					<div class="col-12 text-center text-sm-center text-md-left text-lg-left text-xl-left">
						<ul class="list-unstyled list-inline buttons">
							<li class="list-inline-item"><a href="/cp/categories/create" title="Add Category" class="btn btn-success"><i class="icon fa fa-plus" aria-hidden="true"></i>Add Category</a></li>
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
							<th class="align-middle d-none d-sm-none d-md-none d-lg-table-cell d-xl-table-cell">Description</th>
							<th class="align-middle d-none d-sm-none d-md-table-cell d-lg-table-cell d-xl-table-cell">Sectors</th>
							<th class="align-middle no-sort">&nbsp;</th>
						</tr>
					</thead>
					<tbody {!! ($currentUser->hasPermission('edit_categories')) ? 'id="sortable" class="categories"' : '' !!}>
						@foreach ($categories as $category)
							<tr id="{{ $category->id }}" class="{{ str_slug($category->status->title) }}">
								<td class="align-middle status text-center"><i class="fa fa-circle fa-1 status_id-{{ $category->status->id }}" title="{{ $category->status->title }}" data-toggle="tooltip" data-placement="top" aria-hidden="true"></i></td>
								<td class="align-middle d-none d-sm-none d-md-table-cell d-lg-table-cell d-xl-table-cell text-center image-cell">
									@if (!empty($category->image->url))
										<img src="{{ $category->image->url }}" alt="{{ $category->title }}" class="img-fluid rounded">
									@endif
								</td>
								<td class="align-middle" data-search="{{ $category->status->title }} {{ implode(', ', $category->sectors->pluck('title')->toArray()) }} {{ implode(', ', $category->contentTypes->pluck('title')->toArray()) }}">{{ $category->title }}</td>
								<td class="align-middle d-none d-sm-none d-md-none d-lg-table-cell d-xl-table-cell">{{ str_limit($category->description, 200, '...') }}</td>
								<td class="align-middle d-none d-sm-none d-md-table-cell d-lg-table-cell d-xl-table-cell">{{ implode(', ', $category->sectors->pluck('title')->toArray()) }}</td>
								@if ($currentUser->hasPermission('edit_categories') || $currentUser->hasPermission('delete_categories'))
									<td class="align-middle actions dropdown text-center" id="submenu">
										<a href="javascript:void(0);" title="Category Actions" rel="nofollow" class="dropdown-toggle needsclick" id="pageActions" data-toggle="dropdown"><i class="fa fa-ellipsis-v" aria-hidden="true"></i></a>
										<ul class="dropdown-menu dropdown-menu-right">
											@if ($currentUser->hasPermission('edit_categories'))
												<li class="dropdown-item text-safetynet-orange"><a href="/cp/categories/{{ $category->id }}/edit" title="Edit Category"><i class="icon fa fa-pencil" aria-hidden="true"></i>Edit Category</a></li>
											@endif
											@if ($currentUser->hasPermission('delete_categories'))
												<li class="dropdown-item text-safetynet-orange"><a href="/cp/categories/{{ $category->id }}/delete" title="Delete Category"><i class="icon fa fa-trash" aria-hidden="true"></i>Delete Category</a></li>
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
