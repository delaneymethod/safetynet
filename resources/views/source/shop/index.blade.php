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

@php (array_push($breadcrumbs, 'Shop'))
						
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
				@if (!empty($department->banner->focus_point))
					<div class="focuspoint" data-focus-x="{{ $department->banner->focus_point->focusX }}" data-focus-y="{{ $department->banner->focus_point->focusY }}">
						<img src="{{ $department->banner->url }}" alt="{{ $department->title }}" class="img-fluid">
						<div class="position-absolute d-flex h-100 align-items-center justify-content-center banner-title">
							<h3 class="font-vtg-stencil display-5 text-uppercase text-white text-center">{{ $department->title }}</h3>
						</div>
					</div>
				@else
					<div class="focuspoint" data-focus-x="0" data-focus-y="0">
						<img src="{{ $department->banner->url }}" alt="{{ $department->title }}" class="img-fluid">
					</div>
					<div class="position-absolute d-flex h-100 align-items-center justify-content-center banner-title">
						<h3 class="font-vtg-stencil display-5 text-uppercase text-white text-center">{{ $department->title }}</h3>
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
					@if (!empty($shopGiveawaysData))
						<div class="col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3 pl-0 pr-0 same-height">
							<div class="focuspoint" data-focus-x="0" data-focus-y="0">
								<a href="{{ $shopGiveawaysData }}" title="Giveaways" class="d-block">
									<img src="{{ $shopGiveawaysImage }}" alt="Giveaway" class="img-fluid">
									<h2 class="font-interface-bold position-absolute text-uppercase text-white bg-safetynet-orange">Giveaways</h2>
								</a>
							</div>
						</div>
					@endif
					@if (!empty($shopBrochuresAndStationeryData))
						<div class="col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3 pl-0 pr-0 same-height">
							<div class="focuspoint" data-focus-x="0" data-focus-y="0">
								<a href="{{ $shopBrochuresAndStationeryData }}" title="Brochures & Stationery" class="d-block">
									<img src="{{ $shopBrochuresAndStationeryImage }}" alt="Brochures & Stationery" class="img-fluid">
									<h2 class="font-interface-bold position-absolute text-uppercase text-white bg-safetynet-orange">Brochures & Stationery</h2>
								</a>
							</div>
						</div>
					@endif
				</div>
			</div>
		</div>
	</main>
@endsection	
