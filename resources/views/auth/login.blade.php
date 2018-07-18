@extends('_layouts.default')

@section('title', 'Login - '.$siteName)
@section('description', 'Login - '.$siteName)
@section('keywords', 'Login, '.$siteName)

@push('styles')
	@include('_partials.styles')
@endpush

@push('headScripts')
	@include('_partials.headScripts')
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
						<h1 class="mb-3 display-6 font-vtg-stencil text-uppercase font-weight-bold">Login</h1>
					</div>
				</div>
				<div class="row">
					<div class="col-12 text-center text-sm-center text-md-left text-lg-left text-xl-left bg-gray">
						<form name="loginUser" id="loginUser" class="loginUser pt-4 pl-4 pr-4 pb-2" role="form" method="POST" action="{{ route('login') }}">
							{{ csrf_field() }}
							<div class="form-group">
								<label for="email" class="control-label sr-only">Email Address <span class="text-danger">&#42;</span></label>
								<input type="email" name="email" id="email" class="form-control" placeholder="Email Address" value="{{ old('email') }}" title="Email Address" tabindex="1" autocomplete="on" aria-describedby="helpBlockEmail" required autofocus>
								@if ($errors->has('email'))
									<span id="helpBlockEmail" class="form-control-feedback form-text text-danger">- {{ $errors->first('email') }}</span>
								@endif
								<span id="did-you-mean" class="form-control-feedback form-text text-danger">- Did you mean <a href="javascript:void(0);" title="Click to fix your mistake." rel="nofollow"></a>?</span>
							</div>
							<div class="form-group">
								<label for="password" class="control-label sr-only">Password <span class="text-danger">&#42;</span></label>
								<input type="password" name="password" id="password" class="form-control" placeholder="Password" value="" title="Password" tabindex="2" autocomplete="on" aria-describedby="helpBlockPassword" required>
								@if ($errors->has('password'))
									<span id="helpBlockPassword" class="form-control-feedback form-text text-danger">- {{ $errors->first('password') }}</span>
								@endif
							</div>
							<div class="form-group">
								<div class="form-check">
									<label class="form-check-label"><input type="checkbox" name="remember" id="remember" class="form-check-input" title="Remember me" {{ old('remember') ? 'checked' : '' }} style="margin-top: 3px;" tabindex="3" autocomplete="off">Remember Me&nbsp;<a href="javascript:void(0);" class="text-dark pl-1" style="margin-top: 0;" data-html="true" data-toggle="tooltip" data-placement="top" title="<p><strong>&quot;Remember me&quot; Tick Box</strong><p><p>Choosing &quot;Remember me&quot; reduces the number of times you&#39;re asked to log in on this device.</p><p>To keep your account secure, use this option only on your personal devices.</p>"><i class="fa fa-info-circle" aria-hidden="true"></i></a></label>
								</div>
							</div>
							<div class="form-group">
								<button type="submit" name="submit_login" id="submit_login" class="btn btn-block btn-success text-uppercase" title="Login" tabindex="4">Login</button>
							</div>
						</form>
						<p class="text-center"><i class="fa fa-lock" aria-hidden="true"></i>&nbsp;<a href="{{ route('password.request') }}" title="Click here to Reset Password" class="text-secondary">Forgotten your Password?</a></p>
					</div>
				</div>
			</div>
			<div class="col-12 col-sm-12 col-md-2 col-lg-3 col-xl-3"></div>
		</div>
	</main>
@endsection
