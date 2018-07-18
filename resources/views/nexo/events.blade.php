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
	@include('_partials.calendar', [
		'calendar' => $calendar
	])
@endpush
						
@section('content')
	@include('_partials.message', [
		'currentUser' => $currentUser
	])
	<main role="main" class="container-fluid">
		<div class="row">
			<div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3 order-1 order-sm-1 order-md-1 order-lg-1 order-xl-1 bg-white">
				<div class="row no-gutter">
					@include('_partials.nexo.navigation', [
						'breadcrumbs' => [],
						'classes' => 'col-12'
					])
					@include('_partials.nexo.sidebar', [
						'breadcrumbs' => [],
						'classes' => 'col-12',
						'showSidebarFooterTitle' => false
					])
				</div>
			</div>
			<div class="col-12 col-sm-12 col-md-12 col-lg-9 col-xl-9 order-3 order-sm-3 order-md-3 order-lg-4 order-xl-4 bg-white grid">
				<div class="row no-gutter d-flex">
					@include('_partials.breadcrumbs')
					@include('_partials.calendarFilters')
					<div class="col-12 p-0 m-0 calendar"></div>
					<div class="col-12 p-0 m-0">
						{!! $calendar->calendar() !!}
						@foreach ($events as $event)
							@foreach ($event->datesTimes as $eventDateTime)
								@include('_partials.eventModal', [
									'event' => $event,
									'eventDateTime' => $eventDateTime
								])
							@endforeach
						@endforeach
					</div>
				</div>
			</div>
			@include('_partials.sidebarFooterTitle', [
				'classes' => 'events-footer-title col-sm-12 col-md-12 col-lg-3 col-xl-3 bg-white p-2 m-0'
			])
		</div>
	</main>
@endsection	
