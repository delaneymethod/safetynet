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

@php (array_push($breadcrumbs, 'Request An Event'))
						
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
							<h3 class="font-vtg-stencil display-5 text-uppercase text-white text-center">{{ $department->title }}</h3>
						</div>
					</div>
				@else
					<div class="focuspoint" data-focus-x="0" data-focus-y="0">
						<img src="{{ $department->banner->url }}" alt="{{ $department->title }}" class="img-fluid">
					</div>
					<div class="position-absolute d-flex h-100 align-items-center justify-content-center banner-title">
						<h3 class="font-vtg-stencil display-5 text-uppercase text-white text-center">{{ $department->title }}</h3>
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
					<div class="col-12 item p-4 bg-white text-center text-sm-center text-md-center text-lg-left text-xl-left">
						<h2>Survitec Group Event Request Form</h2>
						<form name="submitEvent" id="submitAnEvent" class="submitAnEvent" role="form" method="POST" action="/{{ $department->slug }}/form-submission" enctype="multipart/form-data">
							{{ csrf_field() }}
							<input type="hidden" name="form" value="Request An Event">
							<h4 class="mt-5 mb-3">Details of Event</h4>
							<div class="form-group row">
								<label for="name" class="col-12 col-form-label text-uppercase">Name <span class="text-danger">&#42;</span></label>
								<div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
									<input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" placeholder="" tabindex="1" autocomplete="off" aria-describedby="helpBlockName" required autofocus>
									@if ($errors->has('name'))
										<span id="helpBlockName" class="form-control-feedback form-text text-danger">- {{ $errors->first('name') }}</span>
									@endif
								</div>
							</div>
							<div class="form-group row">
								<label for="date" class="col-12 col-form-label text-uppercase">Date <span class="text-danger">&#42;</span></label>
								<div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
									<input type="text" name="date" id="date" class="form-control" value="{{ old('date') }}" placeholder="" tabindex="2" autocomplete="off" aria-describedby="helpBlockDate" required>
									@if ($errors->has('date'))
										<span id="helpBlockDate" class="form-control-feedback form-text text-danger">- {{ $errors->first('date') }}</span>
									@endif
								</div>
							</div>
							<div class="form-group row">
								<label for="sector" class="col-12 col-form-label text-uppercase">Sector <span class="text-danger">&#42;</span></label>
								<div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
									<select name="sector" id="sector" class="form-control" tabindex="3" autocomplete="off" aria-describedby="helpBlockSector" required>
										@foreach ($sectors as $sector)
											<option value="{{ $sector->title }}">{{ $sector->title }}</option>
										@endforeach
									</select>
									@if ($errors->has('sector'))
										<span id="helpBlockSector" class="form-control-feedback form-text text-danger">- {{ $errors->first('sector') }}</span>
									@endif
								</div>
							</div>
							<div class="form-group row">
								<label for="region" class="col-12 col-form-label text-uppercase">Region <span class="text-danger">&#42;</span></label>
								<div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
									<select name="region" id="region" class="form-control" tabindex="4" autocomplete="off" aria-describedby="helpBlockRegion" required>
										<option value="Americas">Americas</option>
										<option value="Europe">Europe</option>
										<option value="MEA">MEA</option>
										<option value="APA">APA</option>
									</select>
									@if ($errors->has('region'))
										<span id="helpBlockRegion" class="form-control-feedback form-text text-danger">- {{ $errors->first('region') }}</span>
									@endif
								</div>
							</div>
							<div class="form-group row">
								<label for="scopeOfAttendance" class="col-12 col-form-label text-uppercase">Scope of Attendance <span class="text-danger">&#42;</span></label>
								<div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
									<select name="scopeOfAttendance" id="scopeOfAttendance" class="form-control" tabindex="4" autocomplete="off" aria-describedby="helpBlockScopeOfAttendance" required>
										<option value="Survitec Space">Survitec Space</option>
										<option value="Partnership">Partnership</option>
									</select>
									@if ($errors->has('scopeOfAttendance'))
										<span id="helpBlockScopeOfAttendance" class="form-control-feedback form-text text-danger">- {{ $errors->first('scopeOfAttendance') }}</span>
									@endif
									<span id="helpBlockScopeOfAttendance" class="form-control-feedback form-text text-muted">(i.e. trade show with space or supporting distributor, agent, industry partner)</span>
								</div>
							</div>
							<div class="form-group row d-none" id="partnershipNameToggle">
								<label for="partnershipName" class="col-12 col-form-label text-uppercase">Please state partnership name</label>
								<div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
									<input type="text" name="partnershipName" value="{{ old('partnershipName') }}" id="partnershipName" tabindex="-1" autocomplete="off" aria-describedby="helpBlockPartnershipName" class="form-control">
									@if ($errors->has('partnershipName'))
										<span id="helpBlockPartnershipName" class="form-control-feedback form-text text-danger">- {{ $errors->first('partnershipName') }}</span>
									@endif
								</div>
							</div>
							<h4 class="mt-5 mb-3">Justification for Attendance</h4>
							<div class="form-group row">
								<label for="justificationForAttendance" class="col-12 col-form-label text-uppercase">Please outline a clear business justification for attending the event, stating sales campaigns and pipeline opportunities it will help support <span class="text-danger">&#42;</span></label>
								<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
									<textarea name="justificationForAttendance" rows="10" id="justificationForAttendance" tabindex="5" autocomplete="off" aria-describedby="helpBlockJustificationForAttendance" required class="form-control">{{ old('justificationForAttendance') }}</textarea>
									@if ($errors->has('justificationForAttendance'))
										<span id="helpBlockJustificationForAttendance" class="form-control-feedback form-text text-danger">- {{ $errors->first('justificationForAttendance') }}</span>
									@endif
								</div>
							</div>
							<h4 class="mt-5 mb-3">Details of Stand</h4>
							<div class="form-group row">
								<label for="detailsOfStand" class="col-12 col-form-label text-uppercase">Stand space required <span class="text-danger">&#42;</span></label>
								<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
									<textarea name="detailsOfStand" rows="10" id="detailsOfStand" tabindex="6" autocomplete="off" aria-describedby="helpBlockDetailsOfStand" required class="form-control">{{ old('detailsOfStand') }}</textarea>
									@if ($errors->has('detailsOfStand'))
										<span id="helpBlockDetailsOfStand" class="form-control-feedback form-text text-danger">- {{ $errors->first('detailsOfStand') }}</span>
									@endif
									<span id="helpBlockDetailsOfStand" class="form-control-feedback form-text text-muted">(Please include sq.m)</span>
								</div>
							</div>
							<div class="form-group row">
								<label for="estimatedCostOfStand" class="col-12 col-form-label text-uppercase">Estimated cost of stand <span class="text-danger">&#42;</span></label>
								<div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
									<input type="text" name="estimatedCostOfStand" value="{{ old('estimatedCostOfStand') }}" id="estimatedCostOfStand" tabindex="7" autocomplete="off" aria-describedby="helpBlockEstimatedCostOfStand" required class="form-control">
									@if ($errors->has('estimatedCostOfStand'))
										<span id="helpBlockEstimatedCostOfStand" class="form-control-feedback form-text text-danger">- {{ $errors->first('estimatedCostOfStand') }}</span>
									@endif
								</div>
							</div>
							<div class="form-group row">
								<label for="objectiveOfEvent" class="col-12 col-form-label text-uppercase">Objective of event <span class="text-danger">&#42;</span></label>
								<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
									<textarea name="objectiveOfEvent" rows="10" id="objectiveOfEvent" tabindex="8" autocomplete="off" aria-describedby="helpBlockObjectiveOfEvent" required class="form-control">{{ old('objectiveOfEvent') }}</textarea>
									@if ($errors->has('objectiveOfEvent'))
										<span id="helpBlockObjectiveOfEvent" class="form-control-feedback form-text text-danger">- {{ $errors->first('objectiveOfEvent') }}</span>
									@endif
								</div>
							</div>
							<div class="form-group row">
								<label for="messagingAtEvent" class="col-12 col-form-label text-uppercase">Messaging at event <span class="text-danger">&#42;</span></label>
								<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
									<textarea name="messagingAtEvent" rows="10" id="messagingAtEvent" tabindex="9" autocomplete="off" aria-describedby="helpBlockMessagingAtEvent" required class="form-control">{{ old('messagingAtEvent') }}</textarea>
									@if ($errors->has('messagingAtEvent'))
										<span id="helpBlockMessagingAtEvent" class="form-control-feedback form-text text-danger">- {{ $errors->first('messagingAtEvent') }}</span>
									@endif
								</div>
							</div>
							<div class="form-group row">
								<label for="productsToBeDisplayed" class="col-12 col-form-label text-uppercase">Products to be displayed <span class="text-danger">&#42;</span></label>
								<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
									<textarea name="productsToBeDisplayed" rows="10" id="productsToBeDisplayed" tabindex="10" autocomplete="off" aria-describedby="helpBlockProductsToBeDisplayed" required class="form-control">{{ old('productsToBeDisplayed') }}</textarea>
									@if ($errors->has('productsToBeDisplayed'))
										<span id="helpBlockProductsToBeDisplayed" class="form-control-feedback form-text text-danger">- {{ $errors->first('productsToBeDisplayed') }}</span>
									@endif
								</div>
							</div>
							<h4 class="mt-5 mb-3">Sales Objectives</h4>
							<div class="form-group row">
								<label for="customerInviteGroups" class="col-12 col-form-label text-uppercase">Customer invite groups <span class="text-danger">&#42;</span></label>
								<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
									<textarea name="customerInviteGroups" rows="10" id="customerInviteGroups" tabindex="11" autocomplete="off" aria-describedby="helpBlockCustomerInviteGroups" required class="form-control">{{ old('customerInviteGroups') }}</textarea>
									@if ($errors->has('customerInviteGroups'))
										<span id="helpBlockCustomerInviteGroups" class="form-control-feedback form-text text-danger">- {{ $errors->first('customerInviteGroups') }}</span>
									@endif
									<span id="helpBlockDetailsOfStand" class="form-control-feedback form-text text-muted">(End Users, OEMs, country delegations, distributors, agents etc). Please note this will act as essential criteria when making a decision on the event's value.</span>
								</div>
							</div>
							<div class="form-group row">
								<label for="customerPreArrangedMeetings" class="col-12 col-form-label text-uppercase">Customer Pre-arranged Meetings</label>
								<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
									<textarea name="customerPreArrangedMeetings" rows="10" id="customerPreArrangedMeetings" tabindex="12" autocomplete="off" aria-describedby="helpBlockCustomerPreArrangedMeetings" class="form-control">{{ old('customerPreArrangedMeetings') }}</textarea>
									@if ($errors->has('customerPreArrangedMeetings'))
										<span id="helpBlockCustomerPreArrangedMeetings" class="form-control-feedback form-text text-danger">- {{ $errors->first('customerPreArrangedMeetings') }}</span>
									@endif
								</div>
							</div>
							<div class="form-group row">
								<label for="papersToBeSubmitted" class="col-12 col-form-label text-uppercase">Papers to be submitted</label>
								<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
									<textarea name="papersToBeSubmitted" rows="10" id="papersToBeSubmitted" tabindex="13" autocomplete="off" aria-describedby="helpBlockPapersToBeSubmitted" class="form-control">{{ old('papersToBeSubmitted') }}</textarea>
									@if ($errors->has('papersToBeSubmitted'))
										<span id="helpBlockPapersToBeSubmitted" class="form-control-feedback form-text text-danger">- {{ $errors->first('papersToBeSubmitted') }}</span>
									@endif
								</div>
							</div>
							<div class="form-group row">
								<label for="pipelineOpportunities" class="col-12 col-form-label text-uppercase">Pipeline opportunities in this region/product area that this show will support</label>
								<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
									<textarea name="pipelineOpportunities" rows="10" id="pipelineOpportunities" tabindex="14" autocomplete="off" aria-describedby="helpBlockPipelineOpportunities" class="form-control">{{ old('pipelineOpportunities') }}</textarea>
									@if ($errors->has('pipelineOpportunities'))
										<span id="helpBlockPipelineOpportunities" class="form-control-feedback form-text text-danger">- {{ $errors->first('pipelineOpportunities') }}</span>
									@endif
								</div>
							</div>
							<h4 class="mt-5 mb-3">Survitec attendance at show</h4>
							<div class="form-group row">
								<label for="attendees" class="col-12 col-form-label text-uppercase">Please list attendees for this show that will assist in set up/break down and manning of stand <span class="text-danger">&#42;</span></label>
								<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
									<textarea name="attendees" rows="10" id="attendees" tabindex="15" autocomplete="off" aria-describedby="helpBlockAttendees" required class="form-control">{{ old('attendees') }}</textarea>
									@if ($errors->has('attendees'))
										<span id="helpBlockAttendees" class="form-control-feedback form-text text-danger">- {{ $errors->first('attendees') }}</span>
									@endif
								</div>
							</div>
							<div class="form-group">
								<button type="submit" name="submit_event" id="submit_event" class="btn btn-safetynet-orange text-uppercase" tabindex="5" title="Submit">Submit</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</main>
@endsection	