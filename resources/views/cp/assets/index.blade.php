@extends('_layouts.cp')

@section('title', 'Assets - '.config('app.name'))
@section('description', 'Assets - '.config('app.name'))
@section('keywords', 'Assets, '.config('app.name'))

@push('styles')
	@include('cp._partials.styles')
@endpush

@push('headScripts')
	@include('cp._partials.headScripts')
@endpush

@push('bodyScripts')
	@include('cp._partials.bodyScripts')
@endpush

@section('content')
		@include('cp._partials.sidebar')
		<div class="{{ $mainSmCols }} {{ $mainMdCols }} {{ $mainLgCols }} {{ $mainXlCols }} main">
			@include('cp._partials.message')
			@include('cp._partials.pageTitle')
			<div class="content padding bg-white">
				@if ($currentUser->hasPermission('upload_assets'))
					<div class="form-buttons text-center text-sm-center text-md-left text-lg-left text-xl-left">
						<a href="/cp/assets/upload{{ $directoryUrl }}" title="Upload Assets" class="btn btn-success cancel-button"><i class="icon fa fa-upload" aria-hidden="true"></i>Upload Assets</a>
						<a href="/cp/assets/folder/create{{ $directoryUrl }}" title="Create Folder" class="btn btn-link text-secondary pull-right float-sm-right float-md-none float-lg-none float-xl-none d-none d-sm-none d-md-none d-lg-inline-block d-xl-inline-block"><i class="icon fa fa-folder" aria-hidden="true"></i>Create Folder</a>
						@if ($deleteFolder)
							<a href="/cp/assets/folder/delete{{ $directoryUrl }}" title="Delete Folder" class="btn btn-outline-danger pull-right d-none d-sm-none d-md-none d-lg-inline-block d-xl-inline-block"><i class="icon fa fa-folder" aria-hidden="true"></i>Delete Folder</a>
						@endif
					</div>
				@endif
				<div class="spacer" style="width: auto;margin-left: -15px;margin-right: -15px;"><hr></div>
				<div class="row">
					<div class="col-12 text-center text-sm-center text-md-left text-lg-left text-xl-left">
						<ul class="breadcrumbs list-unstyled list-inline">
							<li class="list-inline-item">You are here:</li>
							@foreach ($breadcrumbs as $breadcrumb)
								@if ($breadcrumb != end($breadcrumbs))
									<li class="list-inline-item"><a href="{{ $breadcrumb->url }}" title="{{ $breadcrumb->title }}">{{ $breadcrumb->title }}</a></li>
									<li class="list-inline-item">/</li>
								@else
									<li class="list-inline-item">{{ $breadcrumb->title }}</li>
								@endif
							@endforeach
						</ul>
					</div>
				</div>
				<div class="spacer" style="width: auto;margin-left: -15px;margin-right: -15px;"><hr></div>
				<div class="row assets">
					@if (count($assets) > 0)
						@foreach ($assets as $filename => $meta)
							<div class="col-12 col-sm-4 col-md-4 col-lg-2 col-xl-2 asset text-center">
								@if (!empty($meta->mime_type))
									@if (starts_with($meta->mime_type, 'image'))	
										<a href="javascript:void(0);" title="{{ $filename }}" rel="nofollow" class="image asset-opener" style="background-image: url('{{ $meta->url }}');" data-toggle="modal" data-target=".asset-{{ $meta->id }}-modal-lg">
											<span>{{ $filename }}</span>
										</a>
									@else
										<a href="javascript:void(0);" title="{{ $filename }}" rel="nofollow" class="asset-opener" data-toggle="modal" data-target=".asset-{{ $meta->id }}-modal-lg">
											<i class="fa {{ $meta->icon_class }} fa-5x" aria-hidden="true"></i>
											<span>{{ $filename }}</span>
										</a>
									@endif
									<div class="modal fade asset-{{ $meta->id }}-modal-lg" tabindex="-1" role="dialog" aria-labelledby="assetModalLabel" aria-hidden="true">
										<div class="modal-dialog modal-md modal-lg modal-xl">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title" id="assetModalLabel">{{ $filename }}</h5>
													<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
												</div>
												<div class="modal-body">
													<div class="row d-flex h-100 justify-content-center">
														<div class="col-12 col-sm-12 col-md-12 col-lg-7 col-xl-7 d-flex justify-content-center align-self-center" style="text-align: center !important;">
															@if (!starts_with($meta->mime_type, 'image'))
																<p>&nbsp;</p>
																<p><a href="{{ $meta->url }}" title="{{ $filename }}" target="_blank"><i class="fa {{ $meta->icon_class }} fa-5x align-middle" aria-hidden="true"></i><br><br>No Preview Available</a></p>
															@else
																<div class="focuspoint-selector align-self-center" style="max-width: {{ $meta->width }}px !important;">
																	<img src="{{ $meta->url }}" style="max-width: 100%;" class="selector align-top text-center" data-id="{{ $meta->id }}" alt="{{ $filename }}">
																	<img src="/assets/img/focuspoint-cross.png" class="reticle" style="top: {{ $meta->focus_point->percentageY }}; left: {{ $meta->focus_point->percentageX }};">
																	<img src="{{ $meta->url }}" style="max-width: 100%;" class="target" data-id="{{ $meta->id }}" alt="{{ $filename }}">
																</div>
															@endif
														</div>
														<div class="col-12 col-sm-12 col-md-12 col-lg-5 col-xl-5 text-left">
															<div class="spacer d-block d-sm-block d-md-block d-lg-none d-xl-none"></div>
															<h5>Meta Data</h5>
															<div class="spacer"></div>
															<form>
																<div class="form-group">
																	<label class="d-block">File Uploaded: <strong>{{ date('F j, Y, H:i a', $meta->mod_time) }}</strong></label>
																</div>
																<div class="form-group">
																	<label for="file_url">File URL:</label>
																	<div class="input-group">
																		<input type="text" class="form-control bg-transparent" value="{{ $meta->url }}" id="file_url" readonly>
																		<div class="input-group-append">
																			<span class="input-group-text"><a href="javascript:void(0);" title="Copy File URL to clipboard" rel="nofollow" class="clipboard" data-clipboard data-clipboard-target="#file_url"><i class="fa fa-clipboard" title="Copy" aria-hidden="true"></i></a></span>
																		</div>
																	</div>
																</div>
																<div class="form-group">
																	<label>File type: <strong>{{ $meta->mime_type }}</strong></label>
																</div>
																<div class="form-group">
																	<label>File size: <strong>{{ $meta->human_size }}</strong></label>
																</div>
																@if ($meta->width && $meta->height)
																	<div class="form-group">
																		<label>Dimensions: <strong>{{ $meta->width }} x {{ $meta->height }}</strong></label>
																	</div>
																@endif
															</form>
															<div class="spacer tall"><hr></div>
															<h5>Setting a Focal Point</h5>
															<p>Click around the image to adjust the focal point automatically.</p>
															<p>The focal point is used when cropping so the most important part of the image is not removed.</p>
															<div class="spacer tall"><hr></div>
															<ul class="list-unstyled list-inline">
																<li class="list-inline-item"><a href="/cp/assets/{{ $meta->id }}/move{{ $directoryUrl }}" title="Move Asset" class="btn btn-outline-secondary">Move Asset</a></li>
																<li class="list-inline-item pull-right"><a href="/cp/assets/{{ $meta->id }}/delete{{ $directoryUrl }}" title="Delete Asset" class="btn btn-outline-danger">Delete Asset</a></li>
															</ul>
														</div>
													</div>
												</div>
												<div class="modal-footer">
													<div class="container-fluid">
														<div class="row d-flex h-100 justify-content-start">
															<div class="col-12 text-center text-sm-center text-md-center text-lg-right text-xl-right align-self-center">
																<button type="button" class="btn btn-link text-secondary" style="margin-right: -10px;" data-dismiss="modal">Close</button>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								@else
									<a href="{{ $meta->url_path }}" title="{{ $filename }}" class="asset-opener">
										<i class="fa {{ $meta->icon_class }} fa-5x" aria-hidden="true"></i>
										<span>{{ $filename }}</span>
									</a>
								@endif
							</div>
						@endforeach
					@else
						<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center">
							<p>&nbsp;</p>
							<h4><i class="fa fa-folder-o fa-5x" aria-hidden="true"></i><br>Empty folder</h4>
							<p>&nbsp;</p>
							<p>You may upload new assets into this folder by clicking Upload Assets button above.</p>
							<p>&nbsp;</p>
							<p>&nbsp;</p>
							<p>&nbsp;</p>
						</div>
					@endif
				</div>
			</div>
			@include('cp._partials.footer')
		</div>
@endsection
