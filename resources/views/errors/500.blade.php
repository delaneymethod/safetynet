@extends('_layouts.default')

@section('title', '500 Internal Server Error - '.$siteName)
@section('description', '500 Internal Server Error - '.$siteName)
@section('keywords', '500, Internal, Server, Error, '.$siteName)

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
				<h6 class="mb-2 p-0 display-6 font-vtg-stencil text-uppercase font-weight-bold">500 Internal Server Error</h6>
				<h2 class="mb-2 p-0">What does this mean?</h2>What does this mean?</h2>
				<p class="ml-1 mr-1">Something went wrong on our servers while we were processing your request.</p>
				<p class="ml-1 mr-1">We&#39;re really sorry about this, and will work hard to get this resolved as soon as possible.</p>
				@if ($exception->getMessage())
					<pre class="ml-1 mr-1">{{ $exception->getMessage() }}</pre>
				@else
					<p class="ml-1 mr-1">Perhaps you would like to go <a href="javascript:window.history.back();" title="Back" class="text-info">back</a> or go to our <a href="/" title="Home" class="text-info">homepage</a>?</p>
				@endif					
			</div>
		</div>
	</main>
@endsection
