@extends('_layouts.cp')

@section('title', 'Add Thumbnail - Supporting File - Idea - Ideas - '.config('app.name'))
@section('description', 'Add Thumbnail - Supporting File - Idea - Ideas - '.config('app.name'))
@section('keywords', 'Add, Supporting, File, Thumbnail, Idea, Ideas, '.config('app.name'))

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
		@if ($currentUser->hasPermission('view_ideas'))
			<a href="/cp/ideas" title="Cancel" class="btn btn-link text-secondary cancel-button" title="Cancel">Cancel</a>
		@endif
		<button type="submit" name="submit_create_thumbnail" id="submit_create_thumbnail" class="pull-right btn btn-primary" title="Upload"><i class="icon fa fa-upload" aria-hidden="true"></i>Upload</button>
	</div>
@endsection

@section('content')
		@include('cp._partials.sidebar')
		<div class="{{ $mainSmCols }} {{ $mainMdCols }} {{ $mainLgCols }} {{ $mainXlCols }} main">
			@include('cp._partials.message')
			@include('cp._partials.pageTitle')
			<div class="content padding bg-white">
				<form name="uploadSupportingFileThumbnail" id="uploadSupportingFileThumbnail" class="uploadSupportingFileThumbnail" role="form" method="POST" action="/cp/ideas/{{ $id }}/supporting-files/{{ $supportingFileId }}/thumbnail" enctype="multipart/form-data">
					{{ csrf_field() }}
					@yield('formButtons')
					<div class="spacer" style="width: auto;margin-left: -15px;margin-right: -15px;"><hr></div>
					<div class="spacer"></div>
					<p><i class="fa fa-info-circle" aria-hidden="true"></i> Fields marked with <span class="text-danger">&#42;</span> are required.</p>
					<div class="spacer"></div>
					<div class="form-group supporting-files">
						<label for="file" class="control-label font-weight-bold">File <span class="text-danger">&#42;</span></label>
						<input type="file" name="file" id="file" class="form-control" value="{{ old('file') }}" tabindex="2" autocomplete="off" aria-describedby="helpBlockFile">
						@if ($errors->has('file'))
							<span id="helpBlockFile" class="form-control-feedback form-text text-danger">- {{ $errors->first('file') }}</span>
						@endif
						<span id="helpBlockFile" class="form-control-feedback form-text text-muted">- Please click on the browse button to select files.</span>
						<span id="helpBlockFile" class="form-control-feedback form-text text-muted">- Max 30MB per file.</span>
					</div>
					<div class="spacer"></div>
					<div class="spacer" style="width: auto;margin-left: -15px;margin-right: -15px;margin-bottom: -30px;"><hr></div>
					@yield('formButtons')
				</form>
			</div>
			@include('cp._partials.footer')
		</div>
@endsection
