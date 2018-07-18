@extends('_layouts.cp')

@section('title', 'Create Location - Locations - '.config('app.name'))
@section('description', 'Create Location - Locations - '.config('app.name'))
@section('keywords', 'Create, Location, Locations, '.config('app.name'))

@push('styles')
	@include('cp._partials.styles')
@endpush

@push('headScripts')
	@include('cp._partials.headScripts')
@endpush

@push('bodyScripts')
	@include('cp._partials.bodyScripts')
@endpush

@section('formButtons')
	<div class="form-buttons">
		@if ($currentUser->hasPermission('view_locations'))
			<a href="/cp/locations" title="Cancel" class="btn btn-link text-secondary" title="Cancel">Cancel</a>
		@endif
		<button type="submit" name="submit_create_location" id="submit_create_location" class="pull-right btn btn-primary" title="Save Changes"><i class="icon fa fa-check-circle" aria-hidden="true"></i>Save Changes</button>
	</div>
@endsection

@section('content')
		@include('cp._partials.sidebar')
		<div class="{{ $mainSmCols }} {{ $mainMdCols }} {{ $mainLgCols }} {{ $mainXlCols }} main">
			@include('cp._partials.message')
			@include('cp._partials.pageTitle')
			<div class="content padding bg-white">
				<form name="createLocation" id="createLocation" class="createLocation" role="form" method="POST" action="/cp/locations">
					{{ csrf_field() }}
					@yield('formButtons')
					<div class="spacer" style="width: auto;margin-left: -15px;margin-right: -15px;"><hr></div>
					<div class="spacer"></div>
					<p><i class="fa fa-info-circle" aria-hidden="true"></i> Fields marked with <span class="text-danger">&#42;</span> are required.</p>
					<div class="spacer"></div>
					<div class="form-group">
						<label for="title" class="control-label font-weight-bold">Title <span class="text-danger">&#42;</span></label>
						<input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" placeholder="e.g Test Title" tabindex="1" autocomplete="off" aria-describedby="helpBlockTitle" required autofocus>
						@if ($errors->has('title'))
							<span id="helpBlockTitle" class="form-control-feedback form-text text-danger">- {{ $errors->first('title') }}</span>
						@endif
						<span id="helpBlockTitle" class="form-control-feedback form-text text-muted"></span>
					</div>
					<div class="spacer"></div>
					<div class="form-group">
						<label for="unit" class="control-label font-weight-bold">Unit</label>
						<input type="text" name="unit" id="unit" class="form-control" value="{{ old('unit') }}" placeholder="e.g Test Unit" tabindex="2" autocomplete="off" aria-describedby="helpBlockUnit">
						@if ($errors->has('unit'))
							<span id="helpBlockUnit" class="form-control-feedback form-text text-danger">- {{ $errors->first('unit') }}</span>
						@endif
						<span id="helpBlockUnit" class="form-control-feedback form-text text-muted"></span>
					</div>
					<div class="spacer"></div>
					<div class="form-group">
						<label for="building" class="control-label font-weight-bold">Building</label>
						<input type="text" name="building" id="building" class="form-control" value="{{ old('building') }}" placeholder="e.g Test Building" tabindex="3" autocomplete="off" aria-describedby="helpBlockBuilding">
						@if ($errors->has('building'))
							<span id="helpBlockBuilding" class="form-control-feedback form-text text-danger">- {{ $errors->first('building') }}</span>
						@endif
						<span id="helpBlockBuilding" class="form-control-feedback form-text text-muted"></span>
					</div>
					<div class="spacer"></div>
					<div class="form-group">
						<label for="street_address_1" class="control-label font-weight-bold">Street Address 1 <span class="text-danger">&#42;</span></label>
						<input type="text" name="street_address_1" id="street_address_1" class="form-control" value="{{ old('street_address_1') }}" placeholder="e.g Street Address 1" tabindex="4" autocomplete="off" aria-describedby="helpBlockStreetAddress1" required>
						@if ($errors->has('street_address_1'))
							<span id="helpBlockStreetAddress1" class="form-control-feedback form-text text-danger">- {{ $errors->first('street_address_1') }}</span>
						@endif
						<span id="helpBlockStreetAddress1" class="form-control-feedback form-text text-muted"></span>
					</div>
					<div class="spacer"></div>
					<div class="form-group">
						<label for="street_address_2" class="control-label font-weight-bold">Street Address 2</label>
						<input type="text" name="street_address_2" id="street_address_2" class="form-control" value="{{ old('street_address_2') }}" placeholder="e.g Street Address 2" tabindex="5" autocomplete="off" aria-describedby="helpBlockStreetAddress2">
						@if ($errors->has('street_address_2'))
							<span id="helpBlockStreetAddress2" class="form-control-feedback form-text text-danger">- {{ $errors->first('street_address_2') }}</span>
						@endif
						<span id="helpBlockStreetAddress2" class="form-control-feedback form-text text-muted"></span>
					</div>
					<div class="spacer"></div>
					<div class="form-group">
						<label for="street_address_3" class="control-label font-weight-bold">Street Address 3</label>
						<input type="text" name="street_address_3" id="street_address_3" class="form-control" value="{{ old('street_address_3') }}" placeholder="e.g Street Address 3" tabindex="6" autocomplete="off" aria-describedby="helpBlockStreetAddress3">
						@if ($errors->has('street_address_3'))
							<span id="helpBlockStreetAddress3" class="form-control-feedback form-text text-danger">- {{ $errors->first('street_address_3') }}</span>
						@endif
						<span id="helpBlockStreetAddress3" class="form-control-feedback form-text text-muted"></span>
					</div>
					<div class="spacer"></div>
					<div class="form-group">
						<label for="street_address_4" class="control-label font-weight-bold">Street Address 4</label>
						<input type="text" name="street_address_4" id="street_address_4" class="form-control" value="{{ old('street_address_4') }}" placeholder="e.g Street Address 4" tabindex="7" autocomplete="off" aria-describedby="helpBlockStreetAddress4">
						@if ($errors->has('street_address_4'))
							<span id="helpBlockStreetAddress4" class="form-control-feedback form-text text-danger">- {{ $errors->first('street_address_4') }}</span>
						@endif
						<span id="helpBlockStreetAddress4" class="form-control-feedback form-text text-muted"></span>
					</div>
					<div class="spacer"></div>
					<div class="form-group">
						<label for="town_city" class="control-label font-weight-bold">Town / City <span class="text-danger">&#42;</span></label>
						<input type="text" name="town_city" id="town_city" class="form-control" value="{{ old('town_city') }}" placeholder="e.g Town / City" tabindex="8" autocomplete="off" aria-describedby="helpBlockTownCity" required>
						@if ($errors->has('town_city'))
							<span id="helpBlockTownCity" class="form-control-feedback form-text text-danger">- {{ $errors->first('town_city') }}</span>
						@endif
						<span id="helpBlockTownCity" class="form-control-feedback form-text text-muted"></span>
					</div>
					<div class="spacer"></div>
					<div class="form-group">
						<label for="postal_code" class="control-label font-weight-bold">Postal Code</label>
						<input type="text" name="postal_code" id="postal_code" class="form-control" value="{{ old('postal_code') }}" placeholder="e.g Postal Code" tabindex="9" autocomplete="off" aria-describedby="helpBlockPostalCode">
						@if ($errors->has('postal_code'))
							<span id="helpBlockPostalCode" class="form-control-feedback form-text text-danger">- {{ $errors->first('postal_code') }}</span>
						@endif
						<span id="helpBlockPostalCode" class="form-control-feedback form-text text-muted"></span>
					</div>
					<div class="spacer"></div>
					<div class="form-group">
						<label for="county_id" class="control-label font-weight-bold">County <span class="text-danger">&#42;</span></label>
						<select name="county_id" id="county_id" class="form-control" tabindex="10" aria-describedby="helpBlockCountyId" required>
							@foreach ($counties as $county)
								<option value="{{ $county->id }}" {{ (old('county_id') == $county->id) ? 'selected' : '' }}>{{ $county->title }}</option>
							@endforeach
						</select>
						@if ($errors->has('county_id'))
							<span id="helpBlockCountyId" class="form-control-feedback form-text text-danger">- {{ $errors->first('county_id') }}</span>
						@endif
						<span id="helpBlockCountyId" class="form-control-feedback form-text text-muted"></span>
					</div>
					<div class="spacer"></div>
					<div class="form-group">
						<label for="country_id" class="control-label font-weight-bold">Country <span class="text-danger">&#42;</span></label>
						<select name="country_id" id="country_id" class="form-control" tabindex="11" aria-describedby="helpBlockCountryId" required>
							@foreach ($countries as $country)
								<option value="{{ $country->id }}" {{ (old('country_id') == $country->id) ? 'selected' : '' }}>{{ $country->title }}</option>
							@endforeach
						</select>
						@if ($errors->has('country_id'))
							<span id="helpBlockCountryId" class="form-control-feedback form-text text-danger">- {{ $errors->first('country_id') }}</span>
						@endif
						<span id="helpBlockCountryId" class="form-control-feedback form-text text-muted"></span>
					</div>
					<div class="spacer"></div>
					<div class="form-group">
						<label for="telephone" class="control-label font-weight-bold">Telephone</label>
						<input type="tel" name="telephone" id="telephone" class="form-control" value="{{ old('telephone') }}" placeholder="e.g +44 1224 123 4567" tabindex="12" autocomplete="off" aria-describedby="helpBlockTelephone">
						@if ($errors->has('telephone'))
							<span id="helpBlockTelephone" class="form-control-feedback form-text text-danger">- {{ $errors->first('telephone') }}</span>
						@endif
						<span id="helpBlockTelephone" class="form-control-feedback form-text text-muted">- Please enter the telephone number in the international format starting with a plus sign (&#43;).</span>
					</div>
					<div class="spacer"></div>
					<div class="form-group">
						<label for="fax" class="control-label font-weight-bold">Fax</label>
						<input type="tel" name="fax" id="fax" class="form-control" value="{{ old('fax') }}" placeholder="e.g +44 1224 123 4567" tabindex="13" autocomplete="off" aria-describedby="helpBlockFax">
						@if ($errors->has('fax'))
							<span id="helpBlockFax" class="form-control-feedback form-text text-danger">- {{ $errors->first('fax') }}</span>
						@endif
						<span id="helpBlockFax" class="form-control-feedback form-text text-muted">- Please enter the fax number in the international format starting with a plus sign (&#43;).</span>
					</div>
					<div class="spacer"></div>
					<div class="form-group">
						<label for="email" class="control-label font-weight-bold">Email Address <span class="text-danger">&#42;</span></label>
						<input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" placeholder="e.g joe@bloggs.com" tabindex="14" autocomplete="off" aria-describedby="helpBlockEmailAddress">
						@if ($errors->has('email'))
							<span id="helpBlockEmailAddress" class="form-control-feedback form-text text-danger">- {{ $errors->first('email') }}</span>
						@endif
						<span id="helpBlockEmailAddress" class="form-control-feedback form-text text-muted">- Please enter the email address in lowercase.</span>
						<span id="did-you-mean" class="form-control-feedback form-text text-danger">- Did you mean <a href="javascript:void(0);" title="Click to fix your mistake." rel="nofollow"></a>?</span>
					</div>
					<div class="spacer"></div>
					<div class="form-group">
						<label class="control-label font-weight-bold">Status</label>
						<div class="custom-controls-stacked">
							@foreach ($statuses as $status)
								<div class="custom-control custom-radio d-flex h-100 justify-content-start status_id-{{ $status->id }}">
									<input type="radio" name="status_id" id="status_id-{{ str_slug($status->title) }}" class="custom-control-input" value="{{ $status->id }}" tabindex="15" aria-describedby="helpBlockStatusId" {{ (old('status_id') == $status->id) ? 'checked' : ($loop->first) ? 'checked' : '' }}>
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
