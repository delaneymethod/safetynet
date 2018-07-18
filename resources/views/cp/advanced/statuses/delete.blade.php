@extends('_layouts.cp')

@section('title', 'Delete Status - Statuses - '.config('app.name'))
@section('description', 'Delete Status - Statuses - '.config('app.name'))
@section('keywords', 'Delete, Status, Statuses, '.config('app.name'))

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
				<p class="text-center text-sm-center text-md-left text-lg-left text-xl-left">Please confirm that you wish to delete the <strong>{{ $status->title }}</strong> status.</p>
				<p class="font-weight-bold text-warning text-center text-sm-center text-md-left text-lg-left text-xl-left"><i class="icon fa fa-exclamation-triangle" aria-hidden="true"></i>Caution: This action cannot be undone.</p>
				<form name="removeStatus" id="removeStatus" class="removeStatus" role="form" method="POST" action="/cp/advanced/statuses/{{ $status->id }}">
					{{ csrf_field() }}
					{{ method_field('DELETE') }}
					<div class="spacer" style="width: auto;margin-left: -15px;margin-right: -15px;margin-bottom: -30px;"><hr></div>
					<div class="form-buttons">
						@if ($currentUser->hasPermission('view_statuses'))
							<a href="/cp/advanced/statuses" title="Cancel" class="btn btn-link text-secondary" tabindex="2" title="Cancel">Cancel</a>
						@endif
						<button type="submit" name="submit_remove_status" id="submit_remove_status" class="pull-right btn btn-danger" tabindex="1" title="Delete">Delete</button>
					</div>
				</form>
			</div>
			@include('cp._partials.footer')
		</div>
@endsection
