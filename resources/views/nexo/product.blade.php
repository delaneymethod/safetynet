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
	@include('_partials.loadPlayer')
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
			@include('_partials.nexo.navigation', [
				'breadcrumbs' => $breadcrumbs,
				'classes' => 'col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3 order-2 order-sm-2 order-md-2 order-lg-1 order-xl-1'
			])
			<div class="col-12 col-sm-12 col-md-12 col-lg-9 col-xl-9 order-1 order-sm-2 order-md-2 order-lg-2 order-xl-2 pl-0 pr-0 text-center text-sm-center text-md-center text-lg-left text-xl-left bg-white banner">
				<h2 class="font-vtg-stencil display-5 text-safetynet-orange text-uppercase m-4">{{ $contentType->title }}</h2>
				@if (!empty($contentType->description))
					<h4 class="font-interface p-0 mt-0 mb-4 ml-4 mr-4">{{ $contentType->description }}</h4>
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
					<div class="col-12 item p-3 bg-white text-center text-sm-center text-md-center text-lg-left text-xl-left">
						@if ($currentUser->hasPermission('view_products'))
							<div class="row">
								<div class="col-12">
									<h2 class="font-interface-bold text-uppercase">{{ $product->title }}</h2>
									<p>{{ $product->description }}</p>
								</div>
							</div>
							<div class="row">
								<div class="col-12">
									@if (!empty($product->video))
										@if (str_contains($product->video, 'youtube'))
											<div class="mt-3" data-type="youtube" data-video-id="{{ $product->video }}"></div>
										@elseif (str_contains($product->video, 'vimeo'))
											<div class="mt-3" data-type="vimeo" data-video-id="{{ $product->video }}"></div>
										@endif
									@else
										@if ($product->supportingFiles->count() > 0)
											<div class="row">
												@foreach ($product->supportingFiles as $supportingFile)
													@php ($tempUrl = $supportingFile->custom_properties['url'])
													<div class="col-12 col-sm-6 col-md-3 col-lg-3 col-xl-3">
														<div class="card text-center mb-4">
															@if (!empty($supportingFile->custom_properties['thumbnail']))
																@php ($tempThumbnailUrl = $supportingFile->custom_properties['thumbnail']['url'])
																<a href="{{ $tempUrl }}" title="{{ $supportingFile->custom_properties['thumbnail']['name'] }}" class="text-dark"><img src="{{ $tempThumbnailUrl }}" style="max-width: 100%;" class="card-img-top" alt="{{ $supportingFile->custom_properties['thumbnail']['name'] }}"></a>
															@else
																@if (starts_with($supportingFile->mime_type, 'image'))
																	<a href="{{ $tempUrl }}" title="{{ $supportingFile->name }}" class="text-dark"><img src="{{ $tempUrl }}" style="max-width: 100%;" class="card-img-top" alt="{{ $supportingFile->name }}"></a>
																@else
																	<a href="{{ $tempUrl }}" title="{{ $supportingFile->name }}" class="text-dark pt-3"><i class="fa fa-file fa-5x align-middle" aria-hidden="true"></i><br><br>No Preview Available</a>
																@endif
															@endif
															<div class="card-body">
																<p class="card-text">{{ $supportingFile->name }}<br><a href="{{ $tempUrl }}" title="{{ $supportingFile->name }}" class="text-info" target="_blank">Download Now ({{ $supportingFile->human_readable_size }})</a></p>
															</div>
														</div>
													</div>
												@endforeach
											</div>
										@endif
									@endif
								</div>
							</div>
						@else
							<p class="text-uppercase p-1 m-0 bg-white text-danger"><i class="fa fa-exclamation-triangle d-block d-sm-block d-md-inline-block d-lg-inline-block d-xl-inline-block pb-2 pb-sm-2 pb-md-0 pb-lg-0 pb-xl-0" aria-hidden="true"></i> You do not have permission to view products.</p>
						@endif
					</div>
				</div>
			</div>
		</div>
	</main>
@endsection	
