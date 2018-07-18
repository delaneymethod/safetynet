@extends('_layouts.cp')

@section('title', 'Edit Password - Users - '.config('app.name'))
@section('description', 'Edit Password - Users - '.config('app.name'))
@section('keywords', 'Edit, Password, Users, '.config('app.name'))

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
		@if ($currentUser->hasPermission('view_users'))
			<a href="/cp/users" title="Cancel" class="btn btn-link text-secondary" tabindex="4" title="Cancel">Cancel</a>
		@endif
		<button type="submit" name="submit_edit_user_password" id="submit_edit_user_password" class="pull-right btn btn-primary" tabindex="3" title="Save Changes"><i class="icon fa fa-check-circle" aria-hidden="true"></i>Save Changes</button>
	</div>
@endsection

@section('content')
		@include('cp._partials.sidebar')
		<div class="{{ $mainSmCols }} {{ $mainMdCols }} {{ $mainLgCols }} {{ $mainXlCols }} main">
			@include('cp._partials.message')
			@include('cp._partials.pageTitle')
			<div class="content padding bg-white">
				<form name="editUser" id="editUser" class="editUser" role="form" method="POST" action="/cp/users/{{ $user->id }}">
					{{ csrf_field() }}
					{{ method_field('PUT') }}
					@yield('formButtons')
					<div class="spacer" style="width: auto;margin-left: -15px;margin-right: -15px;"><hr></div>
					<div class="spacer"></div>
					<p><i class="fa fa-info-circle" aria-hidden="true"></i> Fields marked with <span class="text-danger">&#42;</span> are required.</p>
					<div class="spacer"></div>
					@if ($user->id != $currentUser->id)
						<div class="form-group">
							<label class="control-label font-weight-bold">User Account</label>
							<input type="text" class="form-control text-disabled" value="{{ $user->first_name }} {{ $user->last_name }}" disabled>
						</div>
						<div class="spacer"></div>
					@endif
					<div class="form-group">
						<label for="password" class="control-label font-weight-bold">Password <span class="text-danger">&#42;</span></label>
						<div class="input-group">
							<input type="password" name="password" id="password" class="form-control" value="{{ old('password') }}" placeholder="e.g y1Fwc]_C" tabindex="1" autocomplete="off" aria-describedby="helpBlockPassword" required autofocus>
							<div class="input-group-append">
								<button type="button" name="hide_show_password" id="hide_show_password" class="btn btn-secondary" title="Hide / Show Password">Show Password</button>
							</div>
						</div>
						@if ($errors->has('password'))
							<span id="helpBlockPassword" class="form-control-feedback form-text text-danger">- {{ $errors->first('first_name') }}</span>
						@endif
						<span id="helpBlockPassword" class="form-control-feedback form-text text-muted"></span>
					</div>
					<div class="spacer"></div>
					<div class="form-group">
						<label for="password_confirmation" class="control-label font-weight-bold">Password Confirmation <span class="text-danger">&#42;</span></label>
						<div class="input-group">
							<input type="password" name="password_confirmation" id="password_confirmation" class="form-control" value="{{ old('password_confirmation') }}" placeholder="e.g y1Fwc]_C" tabindex="2" autocomplete="off" aria-describedby="helpBlockPasswordConfirmation" required>
							<div class="input-group-append">
								<button type="button" name="hide_show_password_confirmation" id="hide_show_password_confirmation" class="btn btn-secondary" title="Hide / Show Password">Show Password</button>
							</div>
						</div>
						@if ($errors->has('password_confirmation'))
							<span id="helpBlockPasswordConfirmation" class="form-control-feedback form-text text-danger">- {{ $errors->first('password_confirmation') }}</span>
						@endif
						<span id="helpBlockPasswordConfirmation" class="form-control-feedback form-text text-muted"></span>
					</div>
					<div class="spacer"></div>
					<div class="spacer" style="width: auto;margin-left: -15px;margin-right: -15px;margin-bottom: -30px;"><hr></div>
					@yield('formButtons')
				</form>
			</div>
			@include('cp._partials.footer')
		</div>
@endsection
