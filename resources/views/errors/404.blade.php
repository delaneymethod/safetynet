@extends('_layouts.default')

@section('title', '404 Page not found - '.$siteName)
@section('description', '404 Page not found - '.$siteName)
@section('keywords', '404, Page, not, found, '.$siteName)

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
				<h6 class="mb-2 p-0 display-6 font-vtg-stencil text-uppercase font-weight-bold">404 Page not found</h6>
				<h2 class="mb-2 p-0">What does this mean?</h2>
				<p class="ml-1 mr-1">We couldn&#39;t find the page you requested on our servers.</p>
				<p class="ml-1 mr-1">We&#39;re really sorry about that. It&#39;s our fault, not yours.</p>
				<p class="ml-1 mr-1">We&#39;ll work hard to get this page back online as soon as possible.</p>
				<p class="ml-1 mr-1">Perhaps you would like to go <a href="javascript:window.history.back();" title="Back" class="text-info">back</a> or go to our <a href="/" title="Home" class="text-info">homepage</a>?</p>
			</div>
		</div>
	</main>
@endsection
