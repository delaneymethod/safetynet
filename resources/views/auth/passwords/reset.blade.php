@extends('_layouts.default')

@section('title', 'Set Password - '.$siteName)
@section('description', 'Set Password - '.$siteName)
@section('keywords', 'Set, Password, '.$siteName)

@push('styles')
	@include('_partials.styles')
@endpush

@push('headScripts')
	@include('_partials.headScripts')
	<script async defer src="/assets/js/zxcvbn.js"></script>
@endpush

@push('bodyScripts')
	@include('_partials.bodyScripts')
@endpush

@section('content')
	@include('_partials.message', [
		'currentUser' => null
	])
	<main role="main" class="container">
		<div class="row">
			<div class="col-12 col-sm-12 col-md-2 col-lg-3 col-xl-3"></div>	
			<div class="col-12 col-sm-12 col-md-8 col-lg-6 col-xl-6 mt-5">
				<div class="row">
					<div class="col-12 text-center">
						<h2 class="mb-4"><img src="/assets/img/survitec-logo.png" alt="Survitec Logo" class="col-6 col-sm-6 col-md-5 col-lg-4 col-xl-3"></h2>
						<h1 class="mb-3 display-6 font-vtg-stencil text-uppercase font-weight-bold">Set Password</h1>
					</div>
				</div>
				<div class="row">
					<div class="col-12 text-center text-sm-center text-md-left text-lg-left text-xl-left bg-gray">
						<form name="setPassword" id="setPassword" class="setPassword pt-4 pl-4 pr-4 pb-2" role="form" method="POST" action="{{ route('password.request') }}">
							{{ csrf_field() }}
							<input type="hidden" name="token" value="{{ $token }}">
							<div class="form-group">
								<label for="email" class="control-label sr-only">Email Address <span class="text-danger">&#42;</span></label>
								<input type="email" name="email" id="email" class="form-control" placeholder="Email Address" value="{{ old('email') }}" title="Email Address" tabindex="1" autocomplete="off" aria-describedby="helpBlockEmail" required autofocus>
								@if ($errors->has('email'))
									<span id="helpBlockEmail" class="form-control-feedback form-text text-danger">- {{ $errors->first('email') }}</span>
								@endif
								<span id="did-you-mean" class="form-control-feedback form-text text-danger">- Did you mean <a href="javascript:void(0);" title="Click to fix your mistake." rel="nofollow"></a>?</span>
							</div>
							<div class="form-group">
								<label for="password" class="control-label sr-only">Password <span class="text-danger">&#42;</span></label>
								<div class="input-group">
									<input type="password" name="password" id="password" class="form-control" placeholder="Password" value="" title="Password" tabindex="2" autocomplete="off" aria-describedby="helpBlockPassword" required>
									<div class="input-group-append">
										<button type="button" name="hide_show_password" id="hide_show_password" class="btn btn-secondary" title="Hide / Show Password">Show Password</button>
									</div>
								</div>
								@if ($errors->has('password'))
									<span id="helpBlockPassword" class="form-control-feedback form-text text-danger">- {{ $errors->first('password') }}</span>
								@endif
							</div>
							<div class="form-group">
								<label for="password-confirm" class="control-label sr-only">Confirm Password <span class="text-danger">&#42;</span></label>
								<input type="password" name="password_confirmation" id="password-confirm" class="form-control" placeholder="Confirm Password" value="" title="Confirm Password" tabindex="3" autocomplete="off" aria-describedby="helpBlockConfirmPassword" required>
								@if ($errors->has('password_confirmation'))
									<span id="helpBlockConfirmPassword" class="form-control-feedback form-text text-danger">- {{ $errors->first('password_confirmation') }}</span>
								@endif
							</div>
							<div class="form-group">
								<div class="form-check">
									<label class="form-check-label"><input type="checkbox" name="agree" id="agree" class="form-check-input" title="Agree to Terms &amp; Conditions" {{ old('agree') ? 'checked' : '' }} style="margin-top: 3px;" tabindex="4" autocomplete="off" required>Agree to <a href="{{ $termsAndConditions }}" class="text-safetynet-orange" style="margin-top: 0;">Terms &amp; Conditions</a></label>
								</div>
							</div>
							<div class="form-group">
								<button type="submit" name="submit_set_password" id="submit_set_password" class="btn btn-block btn-success text-uppercase" title="Set Password" tabindex="4">Set Password</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="col-12 col-sm-12 col-md-2 col-lg-3 col-xl-3"></div>				
		</div>
	</main>
@endsection
