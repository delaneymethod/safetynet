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

@php ($breadcrumbs = [])

@if (!empty($department))
	@php (array_push($breadcrumbs, $department->title))
@endif

@if (!empty($sector))
	@php (array_push($breadcrumbs, $sector->title))
@endif

@if (!empty($category))
	@php (array_push($breadcrumbs, $category->title))
@endif

@if (!empty($update))
	@php (array_push($breadcrumbs, $update->title))
@endif
				
@section('content')
	@include('_partials.message', [
		'currentUser' => $currentUser
	])
	<main role="main" class="container-fluid">
		<div class="row">
			@include('_partials.source.navigation', [
				'breadcrumbs' => $breadcrumbs,
				'classes' => 'col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3 order-2 order-sm-2 order-md-2 order-lg-1 order-xl-1'
			])
			<div class="col-12 col-sm-12 col-md-12 col-lg-9 col-xl-9 order-1 order-sm-1 order-md-1 order-lg-2 order-xl-2 pl-0 pr-0 bg-white banner">
				@if (!empty($department->banner->focus_point))
					<div class="focuspoint" data-focus-x="{{ $department->banner->focus_point->focusX }}" data-focus-y="{{ $department->banner->focus_point->focusY }}">
						<img src="{{ $department->banner->url }}" alt="{{ $department->title }}" class="img-fluid">
						<div class="position-absolute d-flex h-100 align-items-center justify-content-center banner-title">
							<h3 class="font-vtg-stencil display-5 text-uppercase text-white text-center">Request A Post</h3>
						</div>
					</div>
				@elseif (!empty($department->image->focus_point))
					<div class="focuspoint" data-focus-x="{{ $department->image->focus_point->focusX }}" data-focus-y="{{ $department->image->focus_point->focusY }}">
						<img src="{{ $department->image->url }}" alt="{{ $department->title }}" class="img-fluid">
						<div class="position-absolute d-flex h-100 align-items-center justify-content-center banner-title">
							<h3 class="font-vtg-stencil display-5 text-uppercase text-white text-center">Request A Post</h3>
						</div>
					</div>
				@else
					<div class="focuspoint" data-focus-x="0" data-focus-y="0">
						<img src="{{ $department->image->url }}" alt="{{ $department->title }}" class="img-fluid">
					</div>
					<div class="position-absolute d-flex h-100 align-items-center justify-content-center banner-title">
						<h3 class="font-vtg-stencil display-5 text-uppercase text-white text-center">Request A Post</h3>
					</div>
				@endif
			</div>
			@include('_partials.source.sidebar', [
				'breadcrumbs' => $breadcrumbs,
				'showSidebarFooterTitle' => true,
				'classes' => 'col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3 order-4 order-sm-4 order-md-4 order-lg-3 order-xl-3'
			])
			<div class="col-12 col-sm-12 col-md-12 col-lg-9 col-xl-9 order-3 order-sm-3 order-md-3 order-lg-4 order-xl-4 bg-white grid">
				<div class="row d-flex">
					@include('_partials.breadcrumbs')
					<div class="col-12 item p-3 bg-white text-center text-sm-center text-md-center text-lg-left text-xl-left">
						<form name="requestAPost" id="requestAPost" class="requestAPost" role="form" method="POST" action="/{{ $department->slug }}/form-submission" enctype="multipart/form-data">
							{{ csrf_field() }}
							<input type="hidden" name="form" value="Request A Post">
							<input type="hidden" name="page" value="{{ implode(' / ', $breadcrumbs) }}" >
							<div class="form-group mt-1 ml-3 mr-3 mb-3">
								<label for="title" class="col-form-label text-uppercase">Title <span class="text-danger">&#42;</span></label>
								<input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" placeholder="" tabindex="1" autocomplete="off" aria-describedby="helpBlockTitle" required autofocus>
								@if ($errors->has('title'))
									<span id="helpBlockTitle" class="form-control-feedback form-text text-danger">- {{ $errors->first('title') }}</span>
								@endif
							</div>
							<div class="form-group mt-1 ml-3 mr-3 mb-3">
								<label for="overview" class="col-form-label text-uppercase">Overview <span class="text-danger">&#42;</span></label>
								<textarea name="overview" id="overview" class="form-control" placeholder="" rows="10" cols="50" tabindex="2" autocomplete="off" aria-describedby="helpBlockOverview" required>{{ old('overview') }}</textarea>
								@if ($errors->has('overview'))
									<span id="helpBlockOverview" class="form-control-feedback form-text text-danger">- {{ $errors->first('overview') }}</span>
								@endif
							</div>
							<div class="form-group mt-1 ml-3 mr-3 mb-3">
								<label class="col-form-label text-uppercase">Channels <span class="text-danger">&#42;</span></label>
								<div class="form-check">
									<input name="channels[]" id="twitter" class="form-check-input" type="checkbox" value="twitter">
									<label for="twitter" class="form-check-label"><i class="fa fa-fw fa-twitter" aria-hidden="true"></i>Twitter</label>
								</div>
								<div class="form-check">
									<input name="channels[]" id="linkedin" class="form-check-input" type="checkbox" value="linkedin">
									<label for="linkedin" class="form-check-label"><i class="fa fa-fw fa-linkedin-square" aria-hidden="true"></i>LinkedIn</label>
								</div>
								<div class="form-check">
									<input name="channels[]" id="instagram" class="form-check-input" type="checkbox" value="instagram">
									<label for="instagram" class="form-check-label"><i class="fa fa-fw fa-instagram" aria-hidden="true"></i>Instagram</label>
								</div>
								<div class="form-check">
									<input name="channels[]" id="facebook" class="form-check-input" type="checkbox" value="facebook">
									<label for="facebook" class="form-check-label"><i class="fa fa-fw fa-facebook" aria-hidden="true"></i>Facebook</label>
								</div>
								<div class="form-check">
									<input name="channels[]" id="on_target" class="form-check-input" type="checkbox" value="on_target">
									<label for="on_target" class="form-check-label"><i class="fa fa-fw fa-share-alt" aria-hidden="true"></i>ON Target</label>
								</div>
								<div class="form-check">
									<input name="channels[]" id="on_board" class="form-check-input" type="checkbox" value="on_board">
									<label for="on_board" class="form-check-label"><i class="fa fa-fw fa-share-alt" aria-hidden="true"></i>ON Board</label>
								</div>
								<div class="form-check">
									<input name="channels[]" id="new_horizons" class="form-check-input" type="checkbox" value="new_horizons">
									<label for="new_horizons" class="form-check-label"><i class="fa fa-fw fa-share-alt" aria-hidden="true"></i>New Horizons</label>
								</div>
								<div class="form-check">
									<input name="channels[]" id="wind_energy" class="form-check-input" type="checkbox" value="wind_energy">
									<label for="wind_energy" class="form-check-label"><i class="fa fa-fw fa-share-alt" aria-hidden="true"></i>Wind &amp; Energy</label>
								</div>
							</div>
							<div class="form-group row mt-4 ml-0 mr-0">
								<div class="col-12 text-left">
									<button type="submit" name="submit_request_a_post" id="submit_request_a_post" class="btn btn-safetynet-orange text-uppercase" tabindex="3" title="Submit">Submit</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
			@include('_partials.sidebarFooterTitle', [
				'classes' => 'events-footer-title col-sm-12 col-md-12 col-lg-3 col-xl-3 bg-white p-2 m-0'
			])
		</div>
	</main>
@endsection	
