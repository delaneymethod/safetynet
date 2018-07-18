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

@section('content')
	@include('_partials.message', [
		'currentUser' => $currentUser
	])
	<main role="main" class="container h-100 text-uppercase text-center">
		<div class="row h-100">
			<div class="col-12 d-flex flex-column">
				<div class="w-100 h-25" style="min-height: 130px;">
					<div class="d-flex h-100 justify-content-center align-items-center">
						<div class="col-12 align-self-center">
							<h1 class="font-vtg-stencil text-safetynet-orange p-0 m-0">{{ $department->title }}</h1>
						</div>
					</div>
				</div>
				@if ($currentUser->hasPermission('view_sectors'))
					@if ($department->sectors->count() > 0)
						@foreach ($department->sectors as $sector)
							<div class="w-100 h-25 m-0 p-0" style="min-height: 110px;">
								<div class="d-flex h-100 justify-content-end align-items-end">
									<div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 align-self-center">
										<h2 class="p-0 m-0"><a href="/{{ $department->slug }}/{{ $sector->slug }}" title="{{ $sector->title }}" class="font-interface bg-safetynet-33 text-white d-block">{{ $sector->title }}</a></h2>
									</div>
								</div>
							</div>
						@endforeach
					@else
						<div class="d-flex w-100 h-50 justify-content-center align-items-center m-0 p-0">
							<p class="p-4 text-warning bg-white align-self-center"><i class="fa fa-info-circle d-block d-sm-block d-md-inline-block d-lg-inline-block d-xl-inline-block pb-2 pb-sm-2 pb-md-0 pb-lg-0 pb-xl-0" aria-hidden="true"></i> No sectors found.</p>
						</div>
					@endif
				@else
					<div class="d-flex w-100 h-50 justify-content-center align-items-center m-0 p-0">
						<p class="p-4 text-danger bg-white align-self-center"><i class="fa fa-exclamation-triangle d-block d-sm-block d-md-inline-block d-lg-inline-block d-xl-inline-block pb-2 pb-sm-2 pb-md-0 pb-lg-0 pb-xl-0" aria-hidden="true"></i> You do not have permission to view sectors.</p>
					</div>
				@endif
			</div>
		</div>
	</main>
@endsection	
