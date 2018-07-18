@extends('_layouts.cp')

@section('title', 'Dashboard - '.config('app.name'))
@section('description', 'Dashboard - '.config('app.name'))
@section('keywords', 'Dashboard, '.config('app.name'))

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
				@if (count($statCards) > 0)
					<div class="row stats">
						@foreach ($statCards as $statCard)
							<div class="col-12 col-sm-4 col-md-3 col-lg-3 col-xl-2 cols">
								<a href="{{ $statCard->url }}" title="{{ $statCard->label }}">
									<div class="stat-card text-center alert" id="{{ $statCard->id }}-card">
										<h5>{{ $statCard->label }}</h5>
										<p>{{ $statCard->count }}</p>
									</div>
								</a>
							</div>
						@endforeach
					</div>
				@endif
			</div>
			@include('cp._partials.footer')
		</div>
@endsection
