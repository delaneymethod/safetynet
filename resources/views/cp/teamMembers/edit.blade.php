@extends('_layouts.cp')

@section('title', 'Edit Team Member - Team Members - '.config('app.name'))
@section('description', 'Edit Team Member - Team Members - '.config('app.name'))
@section('keywords', 'Edit, Team Member, Team Members, '.config('app.name'))

@push('styles')
	@include('cp._partials.styles')
@endpush

@push('headScripts')
	@include('cp._partials.headScripts')
@endpush

@push('bodyScripts')
	@include('cp._partials.bodyScripts')
	@include('cp._partials.assetBrowser')
@endpush

@section('formButtons')
	<div class="form-buttons">
		@if ($currentUser->hasPermission('view_team_members'))
			<a href="/cp/team-members" title="Cancel" class="btn btn-link text-secondary" tabindex="12" title="Cancel">Cancel</a>
		@endif
		<button type="submit" name="submit_edit_team_member" id="submit_edit_team_member" class="pull-right btn btn-primary" tabindex="11" title="Save Changes"><i class="icon fa fa-check-circle" aria-hidden="true"></i>Save Changes</button>
	</div>
@endsection

@section('content')
		@include('cp._partials.sidebar')
		<div class="{{ $mainSmCols }} {{ $mainMdCols }} {{ $mainLgCols }} {{ $mainXlCols }} main">
			@include('cp._partials.message')
			@include('cp._partials.pageTitle')
			<div class="content padding bg-white">
				<form name="editTeamMember" id="editTeamMember" class="editTeamMember" role="form" method="POST" action="/cp/team-members/{{ $teamMember->id }}">
					{{ csrf_field() }}
					{{ method_field('PUT') }}
					@yield('formButtons')
					<div class="spacer" style="width: auto;margin-left: -15px;margin-right: -15px;"><hr></div>
					<div class="spacer"></div>
					<p><i class="fa fa-info-circle" aria-hidden="true"></i> Fields marked with <span class="text-danger">&#42;</span> are required.</p>
					<div class="spacer"></div>
					<div class="form-group">
						<label for="full_name" class="control-label font-weight-bold">Full Name <span class="text-danger">&#42;</span></label>
						<input type="text" name="full_name" id="full_name" class="form-control" value="{{ old('full_name', optional($teamMember)->full_name) }}" placeholder="e.g Joe" tabindex="1" autocomplete="off" aria-describedby="helpBlockFullName" required autofocus>
						@if ($errors->has('full_name'))
							<span id="helpBlockFullName" class="form-control-feedback form-text text-danger">- {{ $errors->first('full_name') }}</span>
						@endif
						<span id="helpBlockFullName" class="form-control-feedback form-text text-muted"></span>
					</div>
					<div class="form-group">
						<label for="email" class="control-label font-weight-bold">Email Address <span class="text-danger">&#42;</span></label>
						<input type="email" name="email" id="email" class="form-control" value="{{ old('email', optional($teamMember)->email) }}" placeholder="e.g joe@bloggs.com" tabindex="2" autocomplete="off" aria-describedby="helpBlockEmailAddress" required>
						@if ($errors->has('email'))
							<span id="helpBlockEmailAddress" class="form-control-feedback form-text text-danger">- {{ $errors->first('email') }}</span>
						@endif
						<span id="helpBlockEmailAddress" class="form-control-feedback form-text text-muted">- Please enter the email address in lowercase.</span>
						<span id="did-you-mean" class="form-control-feedback form-text text-danger">- Did you mean <a href="javascript:void(0);" title="Click to fix your mistake." rel="nofollow"></a>?</span>
					</div>
					<div class="form-group">
						<label for="job_title" class="control-label font-weight-bold">Job Title</label>
						<input type="text" name="job_title" id="job_title" class="form-control" value="{{ old('job_title', optional($teamMember)->job_title) }}" placeholder="e.g Senior Design Engineer" tabindex="3" autocomplete="off" aria-describedby="helpBlockJobTitle">
						@if ($errors->has('job_title'))
							<span id="helpBlockJobTitle" class="form-control-feedback form-text text-danger">- {{ $errors->first('job_title') }}</span>
						@endif
						<span id="helpBlockJobTitle" class="form-control-feedback form-text text-muted">- Please enter your job title.</span>
					</div>
					<div class="form-group">
						<label for="image" class="control-label font-weight-bold">Mugshot</label>
						<div class="input-group">
							<input type="text" name="image" id="image" class="form-control" autocomplete="off" placeholder="e.g Test Mugshot" value="{{ old('image', optional($teamMember)->image->path) }}" tabindex="4" autocomplete="off" aria-describedby="helpBlockMugshot">
							<div class="input-group-append">
								<a href="javascript:void(0);" title="Select Asset" rel="nofollow" data-toggle="modal" data-target="#image-browse-modal" data-field_id="image" data-value="{{ old('image', optional($teamMember)->image->url) }}" class="btn btn-secondary">Select Asset</a>
								<a href="javascript:void(0);" title="Clear Asset" rel="nofollow" id="image-reset-field" class="btn btn-outline-secondary">Clear Asset</a>
							</div>
						</div>
						@if ($errors->has('image'))
							<span id="helpBlockMugshot" class="form-control-feedback form-text text-danger">- {{ $errors->first('image') }}</span>
						@endif
						<span id="helpBlockMugshot" class="form-control-feedback form-text text-muted"></span>
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
						<label for="bio" class="control-label font-weight-bold">Bio</label>
						<textarea name="bio" id="bio" class="form-control" autocomplete="off" placeholder="e.g Test Bio" rows="5" cols="50" tabindex="5" aria-describedby="helpBlockBio">{{ old('bio', optional($teamMember)->bio) }}</textarea>
						@if ($errors->has('bio'))
							<span id="helpBlockBio" class="form-control-feedback form-text text-danger">- {{ $errors->first('bio') }}</span>
						@endif
					</div>
					<div class="spacer"></div>
					<div class="form-group">
						<label class="control-label font-weight-bold">Location</label>
						<div class="custom-controls-stacked">
							@foreach ($locations as $location)
								<div class="custom-control custom-radio d-flex h-100 justify-content-start">
									<input type="radio" name="location_id" id="location_id-{{ $location->id }}" class="custom-control-input" value="{{ $location->id }}" tabindex="6" aria-describedby="helpBlockLocationId" {{ (old('location_id') == $location->id || $teamMember->location_id == $location->id) ? 'checked' : '' }}>
									<label for="location_id-{{ $location->id }}" class="custom-control-label align-self-center">{{ $location->title }}, {{ $location->postal_address }}</label>
								</div>
							@endforeach
						</div>
						@if ($errors->has('location_id'))
							<span id="helpBlockLocationId" class="form-control-feedback form-text text-danger">- {{ $errors->first('location_id') }}</span>
						@endif
						<span id="helpBlockLocationId" class="form-control-feedback form-text text-muted"></span>
					</div>
					<div class="spacer"></div>
					<div class="form-group">
						<label class="control-label font-weight-bold">Status</label>
						<div class="custom-controls-stacked">
							@foreach ($statuses as $status)
								<div class="custom-control custom-radio d-flex h-100 justify-content-start status_id-{{ $status->id }}">
									<input type="radio" name="status_id" id="status_id-{{ str_slug($status->title) }}" class="custom-control-input" value="{{ $status->id }}" tabindex="7" aria-describedby="helpBlockStatusId" {{ (old('status_id') == $status->id || $teamMember->status_id == $status->id) ? 'checked' : '' }}>
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
					<div class="spacer" style="width: auto;margin-left: -15px;margin-right: -15px;margin-bottom: -30px;"><hr></div>
					@yield('formButtons')
				</form>
			</div>
			@include('cp._partials.footer')
		</div>
@endsection
