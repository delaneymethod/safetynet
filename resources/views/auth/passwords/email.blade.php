@extends('_layouts.default')

@section('title', 'Reset Password - '.$siteName)
@section('description', 'Reset Password - '.$siteName)
@section('keywords', 'Reset, Password, '.$siteName)

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
						<h1 class="mb-3 display-6 font-vtg-stencil text-uppercase font-weight-bold">Reset Password</h1>
					</div>
				</div>
				<div class="row">
					<div class="col-12 text-center text-sm-center text-md-left text-lg-left text-xl-left bg-gray">
						<form name="sendResetPasswordLink" id="sendResetPasswordLink" class="sendResetPasswordLink pt-4 pl-4 pr-4 pb-2" role="form" method="POST" action="{{ route('password.email') }}">
							{{ csrf_field() }}
							<div class="form-group">
								<label for="email" class="control-label sr-only">Email Address <span class="text-danger">&#42;</span></label>
								<input type="email" name="email" id="email" class="form-control" placeholder="Email Address" value="{{ old('email') }}" title="Email Address" tabindex="1" autocomplete="off" aria-describedby="helpBlockEmail" required autofocus>
								@if ($errors->has('email'))
									<span id="helpBlockFirstName" class="form-control-feedback form-text text-danger">- {{ $errors->first('email') }}</span>
								@endif
								<span id="did-you-mean" class="form-control-feedback form-text text-danger">- Did you mean <a href="javascript:void(0);" title="Click to fix your mistake." rel="nofollow"></a>?</span>
							</div>
							<div class="form-group">
								<button type="submit" name="submit_sent_reset_password_link" id="submit_sent_reset_password_link" class="btn btn-block btn-success text-uppercase" title="Send Reset Password Link" tabindex="2">Send Reset Password Link</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="col-12 col-sm-12 col-md-2 col-lg-3 col-xl-3"></div>				
		</div>
	</main>
@endsection
