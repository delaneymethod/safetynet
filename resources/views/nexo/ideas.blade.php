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
						
@section('content')
	@include('_partials.message', [
		'currentUser' => $currentUser
	])
	<main role="main" class="container-fluid">
		<div class="row">
			@include('_partials.nexo.navigation', [
				'breadcrumbs' => $breadcrumbs,
				'classes' => 'col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3 order-2 order-sm-2 order-md-2 order-lg-1 order-xl-1'
			])
			<div class="col-12 col-sm-12 col-md-12 col-lg-9 col-xl-9 order-1 order-sm-2 order-md-2 order-lg-2 order-xl-2 pl-0 pr-0 text-center text-sm-center text-md-center text-lg-left text-xl-left bg-white banner">
				<h2 class="font-vtg-stencil display-5 text-safetynet-orange text-uppercase m-4">Submitted Ideas</h2>
				@if (!empty($ideasDescription))
					<h4 class="font-interface p-0 mt-0 mb-4 ml-4 mr-4">{{ $ideasDescription }}</h4>
				@endif
			</div>
			@include('_partials.nexo.sidebar', [
				'breadcrumbs' => $breadcrumbs,
				'showSidebarFooterTitle' => true,
				'classes' => 'col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3 order-4 order-sm-4 order-md-4 order-lg-3 order-xl-3'
			])
			<div class="col-12 col-sm-12 col-md-12 col-lg-9 col-xl-9 order-3 order-sm-3 order-md-3 order-lg-4 order-xl-4 bg-white grid">
				<div class="row d-flex">
					@include('_partials.breadcrumbs')
					@if ($currentUser->hasPermission('view_ideas'))
						@if ($ideas->count() > 0)
							@foreach ($ideas as $idea)
								@php ($modalId = camel_case($idea->slug))
								<div class="col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3 pl-0 pr-0 same-height">
									@if (!empty($idea->image->focus_point))
										<div class="focuspoint" data-focus-x="{{ $idea->image->focus_point->focusX }}" data-focus-y="{{ $idea->image->focus_point->focusY }}">
											<a href="javascript:void(0);" title="{{ $idea->title }}" class="d-block" data-toggle="modal" data-target="#{{ $modalId }}Modal">
												<img src="{{ $idea->image->url }}" alt="{{ $idea->title }}" class="img-fluid">
												<h2 class="font-interface-bold position-absolute text-uppercase text-white bg-safetynet-orange">{{ $idea->title }}</h2>
											</a>	
										</div>
									@else
										<a href="javascript:void(0);" title="{{ $idea->title }}" class="d-block" data-toggle="modal" data-target="#{{ $modalId }}Modal">
											<img src="{{ $idea->image->url }}" alt="{{ $idea->title }}" class="img-fluid">
											<h2 class="font-interface-bold position-absolute text-uppercase text-white bg-safetynet-orange">{{ $idea->title }}</h2>
										</a>
									@endif
									@include('_partials.nexo.ideaModal', [
										'idea' => $idea,
										'modalId' => $modalId,
										'breadcrumbs' => $breadcrumbs,
										'modalClass' => snake_case($idea->slug)
									])
								</div>
							@endforeach
						@else
							<div class="col-12 p-0 m-0 text-center text-sm-center text-md-center text-lg-left text-xl-left">
								<p class="text-uppercase p-4 m-0 bg-white text-warning"><i class="fa fa-info-circle d-block d-sm-block d-md-inline-block d-lg-inline-block d-xl-inline-block pb-2 pb-sm-2 pb-md-0 pb-lg-0 pb-xl-0" aria-hidden="true"></i> No ideas found.</p>
							</div>
						@endif
					@else
						<div class="col-12 p-0 m-0 text-center text-sm-center text-md-center text-lg-left text-xl-left">
							<p class="text-uppercase p-4 m-0 bg-white text-danger"><i class="fa fa-exclamation-triangle d-block d-sm-block d-md-inline-block d-lg-inline-block d-xl-inline-block pb-2 pb-sm-2 pb-md-0 pb-lg-0 pb-xl-0" aria-hidden="true"></i> You do not have permission to view ideas.</p>
						</div>
					@endif
				</div>
			</div>
		</div>
	</main>
@endsection	
