@extends('_layouts.cp')

@section('title', 'Create Folder - Assets - '.config('app.name'))
@section('description', 'Create Folder - Assets - '.config('app.name'))
@section('keywords', 'Create, Folder, Assets, '.config('app.name'))

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
		@if ($currentUser->hasPermission('view_assets'))
			<a href="/cp/assets" title="Cancel" class="btn btn-link text-secondary cancel-button" title="Cancel">Cancel</a>
		@endif
		<button type="submit" name="submit_create_asset" id="submit_create_asset" class="pull-right btn btn-primary" title="Save Changes"><i class="icon fa fa-check-circle" aria-hidden="true"></i>Save Changes</button>
	</div>
@endsection

@section('content')
		@include('cp._partials.sidebar')
		<div class="{{ $mainSmCols }} {{ $mainMdCols }} {{ $mainLgCols }} {{ $mainXlCols }} main">
			@include('cp._partials.message')
			@include('cp._partials.pageTitle')
			<div class="content padding bg-white">
				<form name="createFolderAsset" id="createFolderAsset" class="createFolderAsset" role="form" method="POST" action="/cp/assets/folder">
					{{ csrf_field() }}
					<input type="hidden" name="directory" value="{{ $directory }}">
					@yield('formButtons')
					<div class="spacer" style="width: auto;margin-left: -15px;margin-right: -15px;"><hr></div>
					<div class="spacer"></div>
					<p><i class="fa fa-info-circle" aria-hidden="true"></i> Fields marked with <span class="text-danger">&#42;</span> are required.</p>
					<div class="spacer"></div>
					<div class="form-group">
						<label class="control-label font-weight-bold">Directory</label>
						<input type="text" class="form-control" value="{{ $directory }}" tabindex="1" aria-describedby="helpBlockDirectory" readonly>
					</div>
					<div class="spacer"></div>
					<div class="form-group">
						<label class="control-label font-weight-bold">Folder Name <span class="text-danger">&#42;</span></label>
						<input type="text" name="folder" id="folder" class="form-control" value="{{ old('folder') }}" tabindex="2" aria-describedby="helpBlockFolder" focus required>
						@if ($errors->has('folder'))
							<span id="helpBlockFolder" class="form-control-feedback form-text text-danger">- {{ $errors->first('folder') }}</span>
						@endif
						<span id="helpBlockFolder" class="form-control-feedback form-text text-muted"></span>
					</div>
					<div class="spacer"></div>
					<div class="spacer" style="width: auto;margin-left: -15px;margin-right: -15px;margin-bottom: -30px;"><hr></div>
					@yield('formButtons')
				</form>
			</div>
			@include('cp._partials.footer')
		</div>
@endsection
