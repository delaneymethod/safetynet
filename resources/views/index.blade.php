@extends('_layouts.default')

@section('title', $siteName)
@section('description', $siteName)
@section('keywords', $siteName)

@push('styles')
	@include('_partials.styles')
@endpush

@push('headScripts')
	@include('_partials.headScripts')
@endpush

@push('bodyScripts')
	@include('_partials.bodyScripts')
	<script>
	function loadHowToVideo() {
		$('#howToVideoModal').on('show.bs.modal', function (event) {
			var language = $(event.relatedTarget).data('lang');
			
			var title = $(event.relatedTarget).data('title');
			
			var url = $(event.relatedTarget).data('url');
			
			if (url) {
				var extension = url.split('.');
			
				extension = extension[(extension.length) - 1];

				$(this).find('#video-language').html('<img src="/assets/img/' + language + '.svg" title="Flag" class="flag"> ' + title);
			
				$('source').attr('src', url);
			
				if (extension == 'mp4') {
					$('source').attr('type', 'video/mp4');
				} else if(extension == 'webm') {
					$('source').attr('type', 'video/webm');
				} else if(extension == 'ogg') {
					$('source').attr('type', 'video/ogg');
				}
				
				window.plyr.setup();
			}
		});
	}

	if (window.attachEvent) {
		window.attachEvent('onload', loadHowToVideo);
	} else if (window.addEventListener) {
		window.addEventListener('load', loadHowToVideo, false);
	} else {
		document.addEventListener('load', loadHowToVideo, false);
	}
	</script>
@endpush

@section('content')
	<main role="main" class="container h-100 text-center">
		<div class="row h-100">
			<!--
			<div class="col-12 text-md-left text-lg-left text-xl-left">
				<div class="dropdown ml-1 mt-3">
					<a href="javascript:void(0);" title="Watch How to Videos..." class="btn btn-dark btn-sm dropdown-toggle" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Watch How to Videos</a>
					<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
						<a href="javascript:void(0);" title="View in English" class="dropdown-item" data-toggle="modal" data-target="#howToVideoModal" data-lang="english" data-url="{{ $howToVideoEnglish }}" data-title="View in English"><img src="/assets/img/english.svg" title="United Kingdom Flag" class="flag"> View in English</a>
						<a href="javascript:void(0);" title="Ansicht auf Deutsch" class="dropdown-item" data-toggle="modal" data-target="#howToVideoModal" data-lang="german" data-url="{{ $howToVideoGerman }}" data-title="Ansicht auf Deutsch"><img src="/assets/img/german.svg" title="German Flag" class="flag"> Ansicht auf Deutsch</a>
						<a href="javascript:void(0);" title="Voir en Français" class="dropdown-item" data-toggle="modal" data-target="#howToVideoModal" data-lang="french" data-url="{{ $howToVideoFrench }}" data-title="Voir en Français"><img src="/assets/img/french.svg" title="French Flag" class="flag"> Voir en Français</a>
						<a href="javascript:void(0);" title="Visualizza in Italiano" class="dropdown-item" data-toggle="modal" data-target="#howToVideoModal" data-lang="italian" data-url="{{ $howToVideoItalian }}" data-title="Visualizza in Italiano"><img src="/assets/img/italian.svg" title="Italian Flag" class="flag"> Visualizza in Italiano</a>
					</div>
				</div>
				<div class="modal fade" id="howToVideoModal" tabindex="-1" role="dialog" aria-labelledby="howToVideoModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered modal-md modal-lg modal-xl" role="document">
						<div class="modal-content" style="background-color: rgba(0, 0, 0, 0.7);">
							<div class="modal-header border-0 d-block text-white text-uppercase text-center text-sm-center text-md-center text-lg-left text-xl-left pb-0 mb-0">
								<h5 class="font-interface-bold pb-0 mb-0" id="howToVideoModalLabel">How to Video <span class="text-safetynet-orange" id="video-language"></span></h5>
							</div>
							<div class="modal-body mb-0 pb-0">
								<video class="mt-3 mb-0 pb-0" poster="" controls>
									<source src="" type=""></source>
								</video>
							</div>
							<div class="modal-footer border-0 text-right">
								<button type="button" name="close" id="close" class="btn btn-secondary text-uppercase" tabindex="" title="Close" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			//-->
			<div class="col-12 d-flex flex-column">
				<div class="w-100 h-25" style="min-height: 320px;">
					<div class="row no-gutters d-flex h-100 justify-content-center align-items-center">
						<div class="col-12 align-self-center text-uppercase">
							<h3 class="mb-3"><img src="/assets/img/survitec-logo.png" alt="Survitec Logo" class="col-6 col-sm-6 col-md-5 col-lg-4 col-xl-3"></h3>
							<h1 class="mb-0 pl-1 pr-1 font-vtg-stencil text-uppercase">{{ $siteName }}</h1>
							<h2 class="font-interface p-0 m-0">{{ $siteTagline }}</h2>
						</div>
					</div>
				</div>
				@if ($currentUser->hasPermission('view_departments'))
					@if ($departments->count() > 0)
						<div class="w-100 h-75">
							<div class="row no-gutters d-flex h-100 justify-content-center align-items-center">
								@foreach ($departments as $department)
									<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 align-self-start {{ $department->slug }}">
										@if ($loop->first)
											<div class="d-block d-none d-sm-none d-md-block d-lg-none d-xl-none pb-3">&nbsp;</div>
										@endif
										<!-- Removes the Designer Hub sector as per https://glazeteam.atlassian.net/browse/SGS-158 //-->
										@if ($department->slug === 'nexo')
											@php ($department->slug = $department->slug.'/new-product-development')
										@endif
										<div class="row no-gutters">
											<div class="col-12">
												<a href="/{{ $department->slug }}" title="{{ $department->title }}" class="d-block p-3"><img src="{{ $department->image->url }}" alt="{{ $department->title }}" class="img-fluid"></a>
												<h3 class="font-interface-bold text-uppercase p-3"><a href="/{{ $department->slug }}" title="{{ $department->title }}">{{ $department->title }}</a></h3>
											</div>
										</div>	
										<div class="row no-gutters justify-content-center">
											<div class="col-12 col-sm-12 col-md-10 col-lg-8 col-xl-8">
												<p class="font-interface pt-3 pl-3 pr-3 pb-1">{{ $department->description }}</p>
											</div>
										</div>
									</div>
								@endforeach
							</div>
						</div>
					@else
						<div class="d-flex w-100 h-50 justify-content-center align-items-center m-0 p-0">
							<p class="p-4 text-uppercase text-warning bg-white align-self-center"><i class="fa fa-info-circle d-block d-sm-block d-md-inline-block d-lg-inline-block d-xl-inline-block pb-2 pb-sm-2 pb-md-0 pb-lg-0 pb-xl-0" aria-hidden="true"></i> No departments found.</p>
						</div>
					@endif
				@else
					<div class="d-flex w-100 h-50 justify-content-center align-items-center m-0 p-0">
						<p class="p-4 text-uppercase text-danger bg-white align-self-center"><i class="fa fa-exclamation-triangle d-block d-sm-block d-md-inline-block d-lg-inline-block d-xl-inline-block pb-2 pb-sm-2 pb-md-0 pb-lg-0 pb-xl-0" aria-hidden="true"></i> You do not have permission to view departments.</p>
					</div>
				@endif
			</div>
		</div>
	</main>
@endsection
