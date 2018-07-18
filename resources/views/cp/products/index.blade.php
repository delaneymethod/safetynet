@extends('_layouts.cp')

@section('title', 'Products - '.config('app.name'))
@section('description', 'Products - '.config('app.name'))
@section('keywords', 'Products, '.config('app.name'))

@push('styles')
	@include('cp._partials.styles')
@endpush

@push('headScripts')
	@include('cp._partials.headScripts')
@endpush

@push('bodyScripts')
	<script async>
	'use strict';
	
	window.CMS.searchBySuggestions = ' Products by Departments, Sectors, Categories & Content Types';
	</script>
	@include('cp._partials.bodyScripts')
@endpush

@section('content')
		@include('cp._partials.sidebar')
		<div class="{{ $mainSmCols }} {{ $mainMdCols }} {{ $mainLgCols }} {{ $mainXlCols }} main">
			@include('cp._partials.message')
			@include('cp._partials.pageTitle')
			@if ($currentUser->hasPermission('create_products'))
				<div class="row">
					<div class="col-12 text-center text-sm-center text-md-left text-lg-left text-xl-left">
						<ul class="list-unstyled list-inline buttons">
							<li class="list-inline-item"><a href="/cp/products/create" title="Add Product" class="btn btn-success"><i class="icon fa fa-plus" aria-hidden="true"></i>Add Product</a></li>
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
						@foreach ($products as $product)
							<tr class="{{ str_slug($product->status->title) }}">
								<td class="align-middle status text-center"><i class="fa fa-circle fa-1 status_id-{{ $product->status->id }}" title="{{ $product->status->title }}" data-toggle="tooltip" data-placement="top" aria-hidden="true"></i></td>
								<td class="align-middle d-none d-sm-none d-md-table-cell d-lg-table-cell d-xl-table-cell text-center image-cell">
									@if (!empty($product->image->url))
										<img src="{{ $product->image->url }}" alt="{{ $product->title }}" class="img-fluid rounded">
									@endif
								</td>
								<td class="align-middle" data-search="{{ $product->status->title }} {{ implode(', ', $product->departments->pluck('title')->toArray()) }} {{ implode(', ', $product->sectors->pluck('title')->toArray()) }} {{ implode(', ', $product->categories->pluck('title')->toArray()) }} {{ implode(', ', $product->contentTypes->pluck('title')->toArray()) }}">{{ $product->title }}</td>
								<td class="align-middle d-none d-sm-none d-md-table-cell d-lg-table-cell d-xl-table-cell">{{ str_limit($product->description, 200, '...') }}</td>
								@if ($currentUser->hasPermission('edit_products') || $currentUser->hasPermission('delete_products'))
									<td class="align-middle actions dropdown text-center" id="submenu">
										<a href="javascript:void(0);" title="Product Actions" rel="nofollow" class="dropdown-toggle needsclick" id="pageActions" data-toggle="dropdown"><i class="fa fa-ellipsis-v" aria-hidden="true"></i></a>
										<ul class="dropdown-menu dropdown-menu-right">
											@if ($currentUser->hasPermission('edit_products'))
												<li class="dropdown-item text-safetynet-orange"><a href="/cp/products/{{ $product->id }}/edit" title="Edit Product"><i class="icon fa fa-pencil" aria-hidden="true"></i>Edit Product</a></li>
											@endif
											@if ($currentUser->hasPermission('delete_products'))
												<li class="dropdown-item text-safetynet-orange"><a href="/cp/products/{{ $product->id }}/delete" title="Delete Product"><i class="icon fa fa-trash" aria-hidden="true"></i>Delete Product</a></li>
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
