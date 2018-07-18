@extends('_layouts.cp')

@section('title', 'Delete Event - Events - '.config('app.name'))
@section('description', 'Delete Event - Events - '.config('app.name'))
@section('keywords', 'Delete, Event, Events, '.config('app.name'))

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
				<p class="text-center text-sm-center text-md-left text-lg-left text-xl-left">Please confirm that you wish to delete the <strong>{{ $event->title }}</strong> event.</p>
				<p class="font-weight-bold text-warning text-center text-sm-center text-md-left text-lg-left text-xl-left"><i class="icon fa fa-exclamation-triangle" aria-hidden="true"></i>Caution: This action cannot be undone.</p>
				<form name="removeEvent" id="removeEvent" class="removeEvent" role="form" method="POST" action="/cp/events/{{ $event->id }}">
					{{ csrf_field() }}
					{{ method_field('DELETE') }}
					<div class="spacer" style="width: auto;margin-left: -15px;margin-right: -15px;margin-bottom: -30px;"><hr></div>
					<div class="form-buttons">
						@if ($currentUser->hasPermission('view_events'))
							<a href="/cp/events" title="Cancel" class="btn btn-link text-secondary" tabindex="2" title="Cancel">Cancel</a>
						@endif
						<button type="submit" name="submit_remove_event" id="submit_remove_event" class="pull-right btn btn-danger" tabindex="1" title="Delete">Delete</button>
					</div>
				</form>
			</div>
			@include('cp._partials.footer')
		</div>
@endsection
