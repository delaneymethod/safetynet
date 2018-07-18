@extends('_layouts.cp')

@section('title', 'Support - '.config('app.name'))
@section('description', 'Support - '.config('app.name'))
@section('keywords', 'Support, '.config('app.name'))

@push('styles')
	@include('cp._partials.styles')
@endpush

@push('headScripts')
	@include('cp._partials.headScripts')
@endpush

@push('bodyScripts')
	@include('cp._partials.bodyScripts')
@endpush

@section('content')
		@include('cp._partials.sidebar')
		<div class="{{ $mainSmCols }} {{ $mainMdCols }} {{ $mainLgCols }} {{ $mainXlCols }} main">
			@include('cp._partials.message')
			@include('cp._partials.pageTitle')
			<div class="content padding bg-white">
				<div class="row">
					<div class="col-12 text-center text-sm-center text-md-left  text-lg-left text-xl-left">
						<p>If you are having difficulty with any aspect of this web app, please don&#39;t hesitate to contact us.</p>
						<ul class="list-unstyled list-inline">
							<li class="list-inline-item pb-3"><a href="mailto:hello@delaneymethod.com" title="Email Us" class="btn btn-outline-secondary">Email us at hello@delaneymethod.com</a></li>
							<li class="list-inline-item"><a href="tel:+447889571206" title="Ring Us" class="btn btn-outline-secondary">Ring us on +44 7889 571 206</a></li>	
						</ul>
						<p>Built by <a href="https://www.delaneymethod.com/" title="DelaneyMethod" class="delaneymethod">DelaneyMethod</a></p>
						<p><a href="https://www.delaneymethod.com/" title="DelaneyMethod" class="delaneymethod"><img src="{{ mix('/assets/img/delaneymethod.png') }}" alt="DelaneyMethod Web Development Ltd" class="img-fluid" style="width: 100px; height: auto;"></a></p>
					</div>					
				</div>
			</div>
			@include('cp._partials.footer')
		</div>
@endsection
