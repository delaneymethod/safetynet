@extends('_layouts.cp')

@section('title', 'Create Global - Globals - '.config('app.name'))
@section('description', 'Create Global - Globals - '.config('app.name'))
@section('keywords', 'Create, Global, Globals, '.config('app.name'))

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
		@if ($currentUser->hasPermission('view_globals'))
			<a href="/cp/globals" title="Cancel" class="btn btn-link text-secondary" title="Cancel">Cancel</a>
		@endif
		<button type="submit" name="submit_create_global" id="submit_create_global" class="pull-right btn btn-primary" title="Save Changes"><i class="icon fa fa-check-circle" aria-hidden="true"></i>Save Changes</button>
	</div>
@endsection

@section('content')
		@include('cp._partials.sidebar')
		<div class="{{ $mainSmCols }} {{ $mainMdCols }} {{ $mainLgCols }} {{ $mainXlCols }} main">
			@include('cp._partials.message')
			@include('cp._partials.pageTitle')
			<div class="content padding bg-white">
				<form name="createGlobal" id="createGlobal" class="createGlobal" role="form" method="POST" action="/cp/globals">
					{{ csrf_field() }}
					<input type="hidden" name="asset" value="false">
					@yield('formButtons')
					<div class="spacer" style="width: auto;margin-left: -15px;margin-right: -15px;"><hr></div>
					<div class="spacer"></div>
					<p><i class="fa fa-info-circle" aria-hidden="true"></i> Fields marked with <span class="text-danger">&#42;</span> are required.</p>
					<div class="spacer"></div>
					<div class="form-group">
						<label for="title" class="control-label font-weight-bold">Title <span class="text-danger">&#42;</span></label>
						<input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" placeholder="e.g Twitter" tabindex="1" autocomplete="off" aria-describedby="helpBlockTitle" required autofocus>
						@if ($errors->has('title'))
							<span id="helpBlockTitle" class="form-control-feedback form-text text-danger">- {{ $errors->first('title') }}</span>
						@endif
						<span id="helpBlockTitle" class="form-control-feedback form-text text-muted"></span>
					</div>
					<div class="spacer"></div>
					<div class="form-group">
						<label for="handle" class="control-label font-weight-bold">Handle <span class="text-danger">&#42;</span></label>
						<input type="text" name="handle" id="handle" class="form-control" value="{{ old('handle') }}" placeholder="e.g twitter" tabindex="2" autocomplete="off" aria-describedby="helpBlockHandle" required>
						@if ($errors->has('handle'))
							<span id="helpBlockHandle" class="form-control-feedback form-text text-danger">- {{ $errors->first('handle') }}</span>
						@endif
						<span id="helpBlockHandle" class="form-control-feedback form-text text-muted">- The handle is auto-generated based on the title but feel free to edit it.</span>
					</div>
					<div class="spacer"></div>
					<div class="form-group">
						<label for="data" class="control-label font-weight-bold">Data</label>
						<textarea name="data" id="data" class="form-control" autocomplete="off" placeholder="e.g https://twitter.com/delaneymethod" rows="5" cols="50" tabindex="3" aria-describedby="helpBlockData">{{ old('data') }}</textarea>
						@if ($errors->has('data'))
							<span id="helpBlockData" class="form-control-feedback form-text text-danger">- {{ $errors->first('data') }}</span>
						@endif
						<span id="helpBlockData" class="form-control-feedback form-text text-muted"></span>
					</div>	
					<div class="spacer"></div>
					<div class="form-group">
						<label for="image" class="control-label font-weight-bold">Image</label>
						<div class="input-group">
							<input type="text" name="image" id="image" class="form-control" autocomplete="off" placeholder="e.g Test Image" value="{{ old('image') }}" tabindex="4" autocomplete="off" aria-describedby="helpBlockImage">
							<div class="input-group-append">
								<a href="javascript:void(0);" title="Select Asset" rel="nofollow" data-toggle="modal" data-target="#image-browse-modal" data-field_id="image" data-value="{{ old('image') }}" class="btn btn-secondary">Select Asset</a>
								<a href="javascript:void(0);" title="Clear Asset" rel="nofollow" id="image-reset-field" class="btn btn-outline-secondary">Clear Asset</a>
							</div>
						</div>
						@if ($errors->has('image'))
							<span id="helpBlockImage" class="form-control-feedback form-text text-danger">- {{ $errors->first('image') }}</span>
						@endif
						<span id="helpBlockImage" class="form-control-feedback form-text text-muted">- Images only apply to the Shop.</span>
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
					<div class="spacer" style="width: auto;margin-left: -15px;margin-right: -15px;margin-bottom: -30px;"><hr></div>
					@yield('formButtons')
				</form>
			</div>
			@include('cp._partials.footer')
		</div>
@endsection
