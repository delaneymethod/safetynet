@extends('_layouts.default')

@section('title', $templateTitle.' - '.$siteName)
@section('description', $templateDescription)
@section('keywords', $templateKeywords.','.$siteName)

@push('styles')
	@include('_partials.styles')
@endpush

@push('headScripts')
	@include('_partials.headScripts')
@endpush

@push('bodyScripts')
	@include('_partials.bodyScripts')
@endpush

@php ($breadcrumbs = [])

@if (!empty($department))
	@php (array_push($breadcrumbs, $department->title))
@endif

@if (!empty($sector))
	@php (array_push($breadcrumbs, $sector->title))
@endif

@if (!empty($category))
	@php (array_push($breadcrumbs, $category->title))
@endif

@if (!empty($contentType))
	@php (array_push($breadcrumbs, $contentType->title))
@endif

@if (!empty($product))
	@php (array_push($breadcrumbs, $product->title))
@endif

@section('content')
	@include('_partials.message', [
		'currentUser' => $currentUser
	])
	<main role="main" class="container-fluid">
		<div class="row">
			@include('_partials.source.navigation', [
				'breadcrumbs' => $breadcrumbs,
				'classes' => 'col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3 order-2 order-sm-2 order-md-2 order-lg-1 order-xl-1'
			])
			<div class="col-12 col-sm-12 col-md-12 col-lg-9 col-xl-9 order-1 order-sm-1 order-md-1 order-lg-2 order-xl-2 pl-0 pr-0 bg-white banner">
				@if (!empty($sector->banner->focus_point))
					<div class="focuspoint" data-focus-x="{{ $sector->banner->focus_point->focusX }}" data-focus-y="{{ $sector->banner->focus_point->focusY }}">
						<img src="{{ $sector->banner->url }}" alt="{{ $sector->title }}" class="img-fluid">
						<div class="position-absolute d-flex h-100 align-items-center justify-content-center banner-title">
							<h3 class="font-vtg-stencil display-5 text-uppercase text-white text-center">{{ $sector->title }}</h3>
						</div>
					</div>
				@elseif (!empty($sector->image->focus_point))
					<div class="focuspoint" data-focus-x="{{ $sector->image->focus_point->focusX }}" data-focus-y="{{ $sector->image->focus_point->focusY }}">
						<img src="{{ $sector->image->url }}" alt="{{ $sector->title }}" class="img-fluid">
						<div class="position-absolute d-flex h-100 align-items-center justify-content-center banner-title">
							<h3 class="font-vtg-stencil display-5 text-uppercase text-white text-center">{{ $sector->title }}</h3>
						</div>
					</div>
				@else
					<div class="focuspoint" data-focus-x="0" data-focus-y="0">
						<img src="{{ $sector->image->url }}" alt="{{ $sector->title }}" class="img-fluid">
					</div>
					<div class="position-absolute d-flex h-100 align-items-center justify-content-center banner-title">
						<h3 class="font-vtg-stencil display-5 text-uppercase text-white text-center">{{ $sector->title }}</h3>
					</div>
				@endif
			</div>
			@include('_partials.source.sidebar', [
				'breadcrumbs' => $breadcrumbs,
				'showSidebarFooterTitle' => true,
				'classes' => 'col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3 order-4 order-sm-4 order-md-4 order-lg-3 order-xl-3'
			])
			<div class="col-12 col-sm-12 col-md-12 col-lg-9 col-xl-9 order-3 order-sm-3 order-md-3 order-lg-4 order-xl-4 bg-white grid">
				<div class="row d-flex">
					@include('_partials.breadcrumbs')
					@if ($currentUser->hasPermission('view_categories'))
						@if ($sector->categories->count() > 0)
							@foreach ($sector->categories as $category)
								<div class="col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3 pl-0 pr-0 same-height">
									@if (!empty($category->image->focus_point))
										<div class="focuspoint" data-focus-x="{{ $category->image->focus_point->focusX }}" data-focus-y="{{ $category->image->focus_point->focusY }}">
											<a href="/{{ $department->slug }}/{{ $sector->slug }}/{{ $category->slug }}" title="{{ $category->title }}" class="d-block">
												<img src="{{ $category->image->url }}" alt="{{ $category->title }}" class="img-fluid">
												<h2 class="font-interface-bold position-absolute text-uppercase text-white bg-safetynet-orange">{{ $category->title }}</h2>
											</a>	
										</div>
									@else
										<a href="/{{ $department->slug }}/{{ $sector->slug }}/{{ $category->slug }}" title="{{ $category->title }}" class="d-block">
											<img src="{{ $category->image->url }}" alt="{{ $category->title }}" class="img-fluid">
											<h2 class="font-interface-bold position-absolute text-uppercase text-white bg-safetynet-orange">{{ $category->title }}</h2>
										</a>
									@endif
								</div>
							@endforeach
						@else
							<div class="col-12 p-0 m-0 text-center text-sm-center text-md-center text-lg-left text-xl-left">
								<p class="text-uppercase p-4 m-0 bg-white text-warning"><i class="fa fa-info-circle d-block d-sm-block d-md-inline-block d-lg-inline-block d-xl-inline-block pb-2 pb-sm-2 pb-md-0 pb-lg-0 pb-xl-0" aria-hidden="true"></i> No categories found.</p>
							</div>
						@endif
					@else
						<div class="col-12 p-0 m-0 text-center text-sm-center text-md-center text-lg-left text-xl-left">
							<p class="text-uppercase p-4 m-0 bg-white text-danger"><i class="fa fa-exclamation-triangle d-block d-sm-block d-md-inline-block d-lg-inline-block d-xl-inline-block pb-2 pb-sm-2 pb-md-0 pb-lg-0 pb-xl-0" aria-hidden="true"></i> You do not have permission to view categories.</p>
						</div>
					@endif
				</div>
			</div>
		</div>
	</main>
@endsection	
