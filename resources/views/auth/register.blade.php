@extends('_layouts.default')

@section('title', 'Register - '.$siteName)
@section('description', 'Register - '.$siteName)
@section('keywords', 'Register, '.$siteName)

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
						<h1 class="mb-3 display-6 font-vtg-stencil text-uppercase font-weight-bold">Register</h1>
					</div>
				</div>
				<div class="row">
					<div class="col-12 text-center text-sm-center text-md-left text-lg-left text-xl-left bg-gray">
						<form name="registerUser" id="registerUser" class="registerUser pt-4 pl-4 pr-4 pb-2" role="form" method="POST" action="{{ route('register') }}">
							{{ csrf_field() }}
							<div class="form-group">
								<label for="first_name" class="control-label sr-only">First Name <span class="text-danger">&#42;</span></label>
								<input type="text" name="first_name" id="first_name" class="form-control" placeholder="First Name" value="{{ old('first_name') }}" title="First Name" tabindex="1" autocomplete="off" aria-describedby="helpBlockFirstName" required autofocus>
								@if ($errors->has('first_name'))
									<span id="helpBlockFirstName" class="form-control-feedback form-text text-danger">- {{ $errors->first('first_name') }}</span>
								@endif
							</div>
							<div class="form-group">
								<label for="last_name" class="control-label sr-only">Last Name <span class="text-danger">&#42;</span></label>
								<input type="text" name="last_name" id="last_name" class="form-control" placeholder="Last Name" value="{{ old('last_name') }}" title="Last Name" tabindex="2" autocomplete="off" aria-describedby="helpBlockLastName" required>
								@if ($errors->has('last_name'))
									<span id="helpBlockLastName" class="form-control-feedback form-text text-danger">- {{ $errors->first('last_name') }}</span>
								@endif
							</div>
							<div class="form-group">
								<label for="email" class="control-label sr-only">Email Address <span class="text-danger">&#42;</span></label>
								<input type="email" name="email" id="email" class="form-control" placeholder="Email Address" value="{{ old('email') }}" title="Email Address" tabindex="3" autocomplete="off" aria-describedby="helpBlockEmail" required>
								@if ($errors->has('email'))
									<span id="helpBlockEmail" class="form-control-feedback form-text text-danger">- {{ $errors->first('email') }}</span>
								@endif
								<span id="did-you-mean" class="form-control-feedback form-text text-danger">- Did you mean <a href="javascript:void(0);" title="Click to fix your mistake." rel="nofollow"></a>?</span>
							</div>
							<div class="form-group">
								<label for="password" class="control-label sr-only">Password <span class="text-danger">&#42;</span></label>
								<div class="input-group">
									<input type="password" name="password" id="password" class="form-control" placeholder="Password" value="" title="Password" tabindex="4" autocomplete="off" aria-describedby="helpBlockPassword" required>
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
								<input type="password" name="password_confirmation" id="password-confirm" class="form-control" placeholder="Confirm Password" value="" title="Confirm Password" tabindex="5" autocomplete="off" aria-describedby="helpBlockConfirmPassword" required>
								@if ($errors->has('password_confirmation'))
									<span id="helpBlockConfirmPassword" class="form-control-feedback form-text text-danger">- {{ $errors->first('password_confirmation') }}</span>
								@endif
							</div>
							<div class="spacer"></div>
							<div class="form-group">	
								<button type="submit" name="submit_register" id="submit_register" class="btn btn-block btn-success text-uppercase" title="Register" tabindex="6">Register</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="col-12 col-sm-12 col-md-2 col-lg-3 col-xl-3"></div>				
		</div>
	</main>
@endsection
