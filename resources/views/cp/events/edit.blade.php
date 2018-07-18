@extends('_layouts.cp')

@section('title', 'Edit Event - Events - '.config('app.name'))
@section('description', 'Edit Event - Events - '.config('app.name'))
@section('keywords', 'Edit, Event, Events, '.config('app.name'))

@push('styles')
	@include('cp._partials.styles')
@endpush

@push('headScripts')
	@include('cp._partials.headScripts')
@endpush

@push('bodyScripts')
	@include('cp._partials.bodyScripts')
	@include('cp._partials.assetBrowser')
	<script async>
	/*
	'use strict';
	
	function loadAirDatepicker() {
		const now = new Date();
		
		const eventStartDate = new Date('{{ $event->start }}');
		
		const eventStartDatePicker = $('.datepicker-start').datepicker({
			'firstDay': 1,
			'language': 'en',
			'timepicker': true,
			'timeFormat': 'hh:ii',
			'dateFormat': 'yyyy-mm-dd',
			'classes': 'datetimepicker',
			'onSelect': (fd, d, picker) => {
	            if (!d) {
	            	return;
				}
				
				$('#start').val(fd);
			}
		}).data('datepicker');
		
		eventStartDatePicker.selectDate(eventStartDate);
		
		const eventEndDate = new Date('{{ $event->end }}');
		
		const eventEndDatePicker = $('.datepicker-end').datepicker({
			'firstDay': 1,
			'language': 'en',
			'timepicker': true,
			'timeFormat': 'hh:ii',
			'dateFormat': 'yyyy-mm-dd',
			'classes': 'datetimepicker',
			'onSelect': (fd, d, picker) => {
	            if (!d) {
	            	return;
				}
				
				$('#end').val(fd);
			}
		}).data('datepicker');
		
		eventEndDatePicker.selectDate(eventEndDate);
	}
	
	if (window.attachEvent) {
		window.attachEvent('onload', () => {
			loadAirDatepicker();
		});
	} else if (window.addEventListener) {
		window.addEventListener('load', () => {
			loadAirDatepicker();
		}, false);
	} else {
		document.addEventListener('load', () => {
			loadAirDatepicker();
		}, false);
	}
	*/
	</script>
@endpush

@section('formButtons')
	<div class="form-buttons">
		@if ($currentUser->hasPermission('view_events'))
			<a href="/cp/events" title="Cancel" class="btn btn-link text-secondary" title="Cancel">Cancel</a>
		@endif
		<button type="submit" name="submit_edit_event" id="submit_edit_event" class="pull-right btn btn-primary" title="Save Changes"><i class="icon fa fa-check-circle" aria-hidden="true"></i>Save Changes</button>
	</div>
@endsection

@section('content')
		@include('cp._partials.sidebar')
		<div class="{{ $mainSmCols }} {{ $mainMdCols }} {{ $mainLgCols }} {{ $mainXlCols }} main">
			@include('cp._partials.message')
			@include('cp._partials.pageTitle')
			<div class="content padding bg-white">
				<form name="editEvent" id="editEvent" class="editEvent" role="form" method="POST" action="/cp/events/{{ $event->id }}">
					{{ csrf_field() }}
					{{ method_field('PUT') }}
					<input type="hidden" name="id" value="{{ $event->id }}">
					@yield('formButtons')
					<div class="spacer" style="width: auto;margin-left: -15px;margin-right: -15px;"><hr></div>
					<div class="spacer"></div>
					<p><i class="fa fa-info-circle" aria-hidden="true"></i> Fields marked with <span class="text-danger">&#42;</span> are required.</p>
					<div class="spacer"></div>
					<div class="form-group">
						<label for="title" class="control-label font-weight-bold">Title <span class="text-danger">&#42;</span></label>
						<input type="text" name="title" id="title" class="form-control" value="{{ old('title', optional($event)->title) }}" placeholder="e.g Test Title" tabindex="1" autocomplete="off" aria-describedby="helpBlockTitle" required autofocus>
						@if ($errors->has('title'))
							<span id="helpBlockTitle" class="form-control-feedback form-text text-danger">- {{ $errors->first('title') }}</span>
						@endif
						<span id="helpBlockTitle" class="form-control-feedback form-text text-muted"></span>
					</div>
					<div class="spacer"></div>
					<div class="form-group">
						<label for="slug" class="control-label font-weight-bold">Slug <span class="text-danger">&#42;</span></label>
						<input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug', optional($event)->slug) }}" placeholder="e.g Test Slug" tabindex="2" autocomplete="off" aria-describedby="helpBlockSlug" required>
						@if ($errors->has('slug'))
							<span id="helpBlockSlug" class="form-control-feedback form-text text-danger">- {{ $errors->first('slug') }}</span>
						@endif
						<span id="helpBlockSlug" class="form-control-feedback form-text text-muted">- The slug is auto-generated based on the title but feel free to edit it.</span>
					</div>
					<div class="spacer"></div>
					<div class="form-group">
						<label for="description" class="control-label font-weight-bold">Description <span class="text-danger">&#42;</span></label>
						<textarea name="description" id="description" class="form-control" autocomplete="off" placeholder="e.g Test Description" rows="5" cols="50" tabindex="3" aria-describedby="helpBlockDescription" required>{{ old('description', optional($event)->description) }}</textarea>
						@if ($errors->has('description'))
							<span id="helpBlockDescription" class="form-control-feedback form-text text-danger">- {{ $errors->first('description') }}</span>
						@endif
						<span id="helpBlockDescription" class="form-control-feedback form-text text-muted"></span>
					</div>
					<div class="spacer"></div>
					<div class="form-group">
						<label for="image" class="control-label font-weight-bold">Image <span class="text-danger">&#42;</span></label>
						<div class="input-group">
							<input type="text" name="image" id="image" class="form-control" value="{{ old('image', optional($event)->image->path) }}" placeholder="e.g Test Image" tabindex="4" autocomplete="off" aria-describedby="helpBlockImage" required>
							<div class="input-group-append">
								<a href="javascript:void(0);" title="Select Asset" rel="nofollow" data-toggle="modal" data-target="#image-browse-modal" data-field_id="image" data-value="{{ old('image', optional($event)->image->url) }}" class="btn btn-secondary">Select Asset</a>
								<a href="javascript:void(0);" title="Clear Asset" rel="nofollow" id="image-reset-field" class="btn btn-outline-secondary">Clear Asset</a>
							</div>
						</div>
						@if ($errors->has('image'))
							<span id="helpBlockImage" class="form-control-feedback form-text text-danger">- {{ $errors->first('image') }}</span>
						@endif
						<span id="helpBlockImage" class="form-control-feedback form-text text-muted"></span>
						<div class="modal fade" id="image-browse-modal" tabindex="-1" role="dialog" aria-labelledby="image-browse-modal-label" aria-hidden="true">
							<div class="modal-dialog modal-lg modal-xl" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="1_image-browse-modal-label">Assets</h5>
									</div>
									<div class="modal-body">
										<div class="container-fluid">
											<div class="row no-gutters">
												<div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8 text-left">
													<div id="image-container"></div>
												</div>
												<div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 text-center">
													<div id="image-selected-asset-preview"></div>
												</div>
											</div>
										</div>
									</div>
									<div class="modal-footer">
										<div class="container-fluid">
											<div class="row d-flex h-100 justify-content-start">
												<div class="col-12 col-sm-12 col-md-12 col-lg-9 col-xl-9 align-self-center align-self-sm-center align-self-md-left align-self-lg-left align-self-xl-left">
													<div class="text-center text-sm-center text-md-left text-lg-left text-xl-left selected-asset" id="image-selected-asset"></div>
												</div>
												<div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3 text-center text-sm-center text-md-center text-lg-right text-xl-right align-self-center">
													<button type="button" class="btn btn-link text-secondary" data-dismiss="modal">Close</button>
													<button type="button" class="btn btn-primary" id="image-select-asset">Insert</button>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="spacer"></div>
					<div class="form-group">
						<label class="control-label font-weight-bold">All Day</label>
						<div class="custom-controls-stacked">
							@foreach ($allDayOptions as $allDayOption)
								<div class="custom-control custom-radio d-flex h-100 justify-content-start all_day-{{ $allDayOption->value }}">
									<input type="radio" name="all_day" id="all_day-{{ str_slug($allDayOption->title) }}" class="custom-control-input" value="{{ $allDayOption->value }}" tabindex="5" aria-describedby="helpBlockAllDay" {{ ($event->all_day == $allDayOption->value || old('all_day') == $allDayOption->value) ? 'checked' : '' }}>
									<label for="all_day-{{ str_slug($allDayOption->title) }}" class="custom-control-label align-self-center">{{ $allDayOption->title }}</label>
								</div>
							@endforeach
						</div>
						@if ($errors->has('all_day'))
							<span id="helpBlockAllDay" class="form-control-feedback form-text text-danger">- {{ $errors->first('all_day') }}</span>
						@endif
						<span id="helpBlockAllDay" class="form-control-feedback form-text text-muted">- Is it an all day event?</span>
					</div>
					<!--
					<div class="spacer"></div>
					<div class="form-group">
						<div class="row">
							<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
								<label for="start" class="control-label font-weight-bold">Start Date / Time <span class="text-danger">&#42;</span></label>
								<input type="hidden" name="start" id="start" value="{{ old('start', optional($event)->start) }}" tabindex="6" aria-describedby="helpBlockStart">
								<div class="datepicker-start"></div>
								<div class="spacer"></div>
							</div>
							<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
								<label for="end" class="control-label font-weight-bold">End Date / Time <span class="text-danger">&#42;</span></label>
								<input type="hidden" name="end" id="end" value="{{ old('start', optional($event)->end) }}" tabindex="7" aria-describedby="helpBlockEnd">
								<div class="datepicker-end"></div>
							</div>
						</div>
						@if ($errors->has('start'))
							<span id="helpBlockStart" class="form-control-feedback form-text text-danger">- {{ $errors->first('start') }}</span>
						@endif
						<span id="helpBlockStart" class="form-control-feedback form-text text-muted"></span>
						@if ($errors->has('end'))
							<span id="helpBlockEnd" class="form-control-feedback form-text text-danger">- {{ $errors->first('end') }}</span>
						@endif
						<span id="helpBlockEnd" class="form-control-feedback form-text text-muted"></span>
					</div>
					//-->
					<div class="spacer"></div>
					<div class="form-group multi-field-wrapper">
						<div class="multi-fields">
							@if ($event->datesTimes->count() > 0)
								@foreach ($event->datesTimes as $eventDateTime)
									<div class="multi-field">
										<div class="row">
											<div class="col-4">
												<label for="starts" class="control-label font-weight-bold">Start Date / Time <span class="text-danger">&#42;</span></label>
												<input type="text" name="starts[]" value="{{ old('start', $eventDateTime->start) }}" class="form-control" placeholder="30-06-2018 09:00" autocomplete="off" required>
											</div>
											<div class="col-4">
												<label for="ends" class="control-label font-weight-bold">End Date / Time <span class="text-danger">&#42;</span></label>
												<input type="text" name="ends[]" value="{{ old('end', $eventDateTime->end) }}" class="form-control" placeholder="30-06-2018 17:00" autocomplete="off" required>
											</div>
											<div class="col-1">
												<label>&nbsp;</label>
												<button type="button" class="btn btn-sm btn-danger remove-field border-0"><i class="fa fa-minus-circle" aria-hidden="true"></i></button>
											</div>
										</div>
										<div class="spacer"></div>
									</div>
								@endforeach
							@else
								<div class="multi-field">
									<div class="row">
										<div class="col-4">
											<label for="starts" class="control-label font-weight-bold">Start Date / Time <span class="text-danger">&#42;</span></label>
											<input type="text" name="starts[]" value="{{ old('start') }}" class="form-control" placeholder="30-06-2018 09:00" autocomplete="off" required>
										</div>
										<div class="col-4">
											<label for="ends" class="control-label font-weight-bold">End Date / Time <span class="text-danger">&#42;</span></label>
											<input type="text" name="ends[]" value="{{ old('end') }}" class="form-control" placeholder="30-06-2018 17:00" autocomplete="off" required>
										</div>
										<div class="col-1">
											<label>&nbsp;</label>
											<button type="button" class="btn btn-sm btn-danger remove-field border-0"><i class="fa fa-minus-circle" aria-hidden="true"></i></button>
										</div>
									</div>
									<div class="spacer"></div>
								</div>
							@endif
						</div>
						<button type="button" name="add-more-dates-times" id="add-more-dates-times" class="btn btn-sm btn-secondary" title="Add more dates and times">Add more dates and times</button>
					</div>
					<div class="spacer"></div>
					<div class="form-group">
						<label for="url" class="control-label font-weight-bold">URL</label>
						@php ($url = '')
						@if (!empty($event->options))
							@php ($options = json_decode($event->options))
							@if (!empty($options->url))
								@php ($url = $options->url)
							@endif
						@endif
						<input type="url" name="url" id="url" class="form-control" value="{{ old('url', $url) }}" placeholder="e.g http://www." tabindex="8" autocomplete="off" aria-describedby="helpBlockUrl">
						@if ($errors->has('url'))
							<span id="helpBlockUrl" class="form-control-feedback form-text text-danger">- {{ $errors->first('url') }}</span>
						@endif
						<span id="helpBlockUrl" class="form-control-feedback form-text text-muted">- This could be a link to the event. Like a conference or meeting invite for a call.</span>
						<span id="helpBlockUrl" class="form-control-feedback form-text text-muted">- Please include http://</span>
					</div>
					<div class="spacer"></div>
					<div class="form-group">
						<label for="organiser_name" class="control-label font-weight-bold">Organiser Name</label>
						<input type="text" name="organiser_name" id="organiser_name" class="form-control" value="{{ old('organiser_name', $event->organiser_name) }}" placeholder="e.g " tabindex="9" autocomplete="off" aria-describedby="helpBlockOrganiserName">
						@if ($errors->has('organiser_name'))
							<span id="helpBlockOrganiserName" class="form-control-feedback form-text text-danger">- {{ $errors->first('organiser_name') }}</span>
						@endif
						<span id="helpBlockOrganiserName" class="form-control-feedback form-text text-muted"></span>
					</div>
					<div class="spacer"></div>
					<div class="form-group">
						<label for="organiser_email" class="control-label font-weight-bold">Organiser Email</label>
						<input type="text" name="organiser_email" id="organiser_email" class="form-control" value="{{ old('organiser_email', $event->organiser_email) }}" placeholder="e.g " tabindex="10" autocomplete="off" aria-describedby="helpBlockOrganiserEmail">
						@if ($errors->has('organiser_email'))
							<span id="helpBlockOrganiserEmail" class="form-control-feedback form-text text-danger">- {{ $errors->first('organiser_email') }}</span>
						@endif
						<span id="helpBlockOrganiserEmail" class="form-control-feedback form-text text-muted"></span>
					</div>
					<div class="spacer"></div>
					<div class="form-group">
						<label for="organiser_contact_number" class="control-label font-weight-bold">Organiser Contact Number</label>
						<input type="text" name="organiser_contact_number" id="organiser_contact_number" class="form-control" value="{{ old('organiser_contact_number', $event->organiser_contact_number) }}" placeholder="e.g " tabindex="11" autocomplete="off" aria-describedby="helpBlockOrganiserContactNumber">
						@if ($errors->has('organiser_contact_number'))
							<span id="helpBlockOrganiserContactNumber" class="form-control-feedback form-text text-danger">- {{ $errors->first('organiser_contact_number') }}</span>
						@endif
						<span id="helpBlockOrganiserContactNumber" class="form-control-feedback form-text text-muted"></span>
					</div>
					<div class="spacer"></div>
					<div class="form-group">
						<label class="control-label font-weight-bold">Status</label>
						<div class="custom-controls-stacked">
							@foreach ($statuses as $status)
								<div class="custom-control custom-radio d-flex h-100 justify-content-start status_id-{{ $status->id }}">
									<input type="radio" name="status_id" id="status_id-{{ str_slug($status->title) }}" class="custom-control-input" value="{{ $status->id }}" tabindex="12" aria-describedby="helpBlockStatusId" {{ ($event->status_id == $status->id || old('status_id') == $status->id) ? 'checked' : '' }}>
									<label for="status_id-{{ str_slug($status->title) }}" class="custom-control-label align-self-center">{{ $status->title }}@if (!empty($status->description))&nbsp;<i class="fa fa-info-circle text-muted" data-toggle="tooltip" data-placement="top" title="{{ $status->description }}" aria-hidden="true"></i>@endif</label>
								</div>
							@endforeach
						</div>
						@if ($errors->has('status_id'))
							<span id="helpBlockStatusId" class="form-control-feedback form-text text-danger">- {{ $errors->first('status_id') }}</span>
						@endif
						<span id="helpBlockStatusId" class="form-control-feedback form-text text-muted"></span>
					</div>
					<div class="spacer"></div>
					<div class="form-group">
						<label class="control-label font-weight-bold">Sectors <span class="text-danger">&#42;</span></label>
						@if (old('sector_ids'))
							@php ($sectorIds = old('sector_ids'))
						@else
							@php ($sectorIds = $event->sectors->pluck('id')->toArray())
						@endif
						@foreach ($sectors as $sector)
							<div class="spacer"></div>
							<div class="row d-flex h-100 justify-content-center">
								<div class="col-6 align-self-center">{{ $sector->title }}</div>
								<div class="col-6 align-self-center text-right">
									<label for="sector_id-{{ $sector->id }}" class="switch form-check-label">
										<input type="checkbox" name="sector_ids[]" id="sector_id-{{ $sector->id }}" value="{{ $sector->id }}" tabindex="13" aria-describedby="helpBlockSectorIds" {{ in_array($sector->id, $sectorIds) ? 'checked' : '' }}>
										<span class="slider round"></span>
									</label>
								</div>
							</div>
						@endforeach
						@if ($errors->has('sector_ids'))
							<span id="helpBlockSectorIds" class="form-control-feedback form-text text-danger">- {{ $errors->first('sector_ids') }}</span>
						@endif
						<span id="helpBlockSectorIds" class="form-control-feedback form-text text-muted"></span>
					</div>
					<div class="spacer"></div>
					<div class="spacer" style="width: auto;margin-left: -15px;margin-right: -15px;margin-bottom: -30px;"><hr></div>
					@yield('formButtons')
				</form>
			</div>
			@include('cp._partials.footer')
		</div>
@endsection
