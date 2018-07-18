@extends('_layouts.default')

@section('title', 'Scheduled Maintenance - '.$siteName)
@section('description', 'Scheduled Maintenance - '.$siteName)
@section('keywords', 'Scheduled, Maintenance, '.$siteName)

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
	<main role="main" class="container">
		<div class="row">
			<div class="col-12 mt-5 mb-5 pt-5 pb-5 text-center">
				<h3 class="mb-4"><img src="/assets/img/survitec-logo.png" alt="Survitec Logo" class="col-6 col-sm-6 col-md-5 col-lg-4 col-xl-3"></h3>
				<h6 class="mb-2 p-0 display-6 font-vtg-stencil text-uppercase font-weight-bold">We&#39;re taking a short break<br>for some scheduled maintenance&hellip;</h6>
				<p class="ml-1 mr-1">{{ json_decode(file_get_contents(storage_path('framework/down')), true)['message'] }}</p>
				<p class="ml-1 mr-1">We&#39;ll be back in a few minutes, so don&#39;t forget to refresh this page.</p>
				<p class="ml-1 mr-1">Sorry for any inconvenience and for thanks for your patience.</p>
			</div>
		</div>
	</main>
@endsection
