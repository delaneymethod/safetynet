<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="author" content="Sean Delaney (delaneymethod.com)">
	<meta name="description" content="@yield('description')">
	<meta name="keywords" content="@yield('keywords')">
	<meta name="robots" content="noindex, nofollow, noarchive">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<!--[if IE]>
	<meta http-equiv="cleartype" content="on">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<![endif]-->
	<title>@yield('title')</title>
	<link rel="home" href="{{ config('app.url') }}">
	@stack('styles')
	<link rel="shortcut icon" href="{{ mix('/favicon.ico') }}">
	<link rel="apple-touch-icon" sizes="57x57" href="{{ mix('/assets/img/apple-icon-57x57.png') }}">
	<link rel="apple-touch-icon" sizes="72x72" href="{{ mix('/assets/img/apple-icon-72x72.png') }}">
	<link rel="apple-touch-icon" sizes="144x144" href="{{ mix('/assets/img/apple-icon-144x144.png') }}">
	<link rel="dns-prefetch" href="{{ config('app.url') }}">
	<base href="/">
	<script async>
	'use strict';
	
	window.CMS = {};
	
	@auth
		window.User = {
			'id': {!! Auth::id() !!},
			'first_name': '{!! Auth::user()->first_name !!}',
			'last_name': '{!! Auth::user()->last_name !!}'
		};
	@else
		window.User = {};
	@endauth	
	
	window.Laravel = {'csrfToken': '{{ csrf_token() }}'};
	</script>
	@stack('headScripts')
</head>
@if (request()->path() == '/')
	@php ($style = ' style="background-image: url(\'/assets/img/safetynet-background.jpg\');"')
@elseif (request()->path() == 'nexo')
	@php ($style = ' style="background-image: url(\'/assets/img/nexo-background.jpg\');"')
@else
	@php ($style = '')
@endif
<body class="{{ setClass('/', 'homepage') }}{{ setClass('source', 'sourcepage') }}{{ setClass('nexo', 'nexopage') }}"{!! $style !!}>
	@include('_partials.serverEnv')
	@yield('content')
	@include('_partials.help')
	@stack('bodyScripts')
</body>
</html>
