@extends('_layouts.cp')

@section('title', 'Move Asset - Assets - '.config('app.name'))
@section('description', 'Move Asset - Assets - '.config('app.name'))
@section('keywords', 'Move, Asset, Assets, '.config('app.name'))

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
		<button type="submit" name="submit_move_asset" id="submit_move_asset" class="pull-right btn btn-primary" title="Save Changes"><i class="icon fa fa-check-circle" aria-hidden="true"></i>Save Changes</button>
	</div>
@endsection

@section('content')
		@include('cp._partials.sidebar')
		<div class="{{ $mainSmCols }} {{ $mainMdCols }} {{ $mainLgCols }} {{ $mainXlCols }} main">
			@include('cp._partials.message')
			@include('cp._partials.pageTitle')
			<div class="content padding bg-white">
				<form name="moveAsset" id="moveAsset" class="moveAsset" role="form" method="POST" action="/cp/assets/{{ $asset->id }}/move">
					{{ csrf_field() }}
					{{ method_field('PUT') }}
					@yield('formButtons')
					<div class="spacer" style="width: auto;margin-left: -15px;margin-right: -15px;"><hr></div>
					<div class="spacer"></div>
					<p><i class="fa fa-info-circle" aria-hidden="true"></i> Fields marked with <span class="text-danger">&#42;</span> are required.</p>
					<div class="spacer"></div>
					<div class="form-group">
						<label class="control-label font-weight-bold">Asset</label>
						<input type="text" name="filename" id="filename" class="form-control" value="{{ $asset->file_name }}" tabindex="1" disabled>
					</div>
					<div class="spacer"></div>
					<div class="form-group">
						<label class="control-label font-weight-bold">Current Directory</label>
						<input type="text" name="_directory" id="_directory" class="form-control" value="{{ $directory }}" tabindex="2" disabled>
					</div>
					<div class="spacer"></div>
					<div class="form-group">
						<label class="control-label font-weight-bold">New Directory <span class="text-danger">&#42;</span></label>
						<select name="new_path" id="new_path" class="form-control" tabindex="3" required>
							@foreach ($directories as $newDirectory)
								@php ($indent = '')
								@php ($depth = $newDirectory->depth)
								@while ($depth > 0)
									@php ($indent .= '-')
									@php ($depth--)
								@endwhile
								@if ($indent > '')
									@php ($indent .= ' ')
								@endif
								<option value="{{ $newDirectory->path }}" {{ (old('new_path') == $newDirectory->path || $directory == $newDirectory->path) ? 'disabled' : '' }}>{{ $indent }}{{ $newDirectory->path }}</option>
							@endforeach
						</select>
						@if ($errors->has('new_path'))
							<span id="helpBlockNewPath" class="form-control-feedback form-text text-danger">- {{ $errors->first('new_path') }}</span>
						@endif
						<span id="helpBlockNewPath" class="form-control-feedback form-text text-muted">- Please select the new directory you want to move the asset <strong>{{ $asset->file_name }}</strong> into.</span>
					</div>
					<div class="spacer"></div>
					<div class="spacer" style="width: auto;margin-left: -15px;margin-right: -15px;margin-bottom: -30px;"><hr></div>
					@yield('formButtons')
				</form>
			</div>
			@include('cp._partials.footer')
		</div>
@endsection
