@extends('_layouts.default')

@section('title', '410 Gone - '.$siteName)
@section('description', '410 Gone - '.$siteName)
@section('keywords', '410, Gone, '.$siteName)

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
				<h6 class="mb-2 p-0 display-6 font-vtg-stencil text-uppercase font-weight-bold">410 Gone</h6>
				<h2 class="mb-2 p-0">What does this mean?</h2>What does this mean?</h2>
				@if ($exception->getMessage())
					<p class="ml-1 mr-1">{{ $exception->getMessage() }}</p>
				@else
					<p class="ml-1 mr-1">The requested resource is no longer available at the server and no forwarding address is known. This condition is expected to be considered permanent.</p>
				@endif
				<p class="ml-1 mr-1">Perhaps you would like to go <a href="javascript:window.history.back();" title="Back" class="text-info">back</a> or go to our <a href="/" title="Home" class="text-info">homepage</a>?</p>
			</div>
		</div>
	</main>
@endsection
