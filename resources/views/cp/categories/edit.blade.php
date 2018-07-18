@extends('_layouts.cp')

@section('title', 'Edit Category - Categories - '.config('app.name'))
@section('description', 'Edit Category - Categories - '.config('app.name'))
@section('keywords', 'Edit, Category, Categories, '.config('app.name'))

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
		@if ($currentUser->hasPermission('view_categories'))
			<a href="/cp/categories" title="Cancel" class="btn btn-link text-secondary" title="Cancel">Cancel</a>
		@endif
		<button type="submit" name="submit_edit_category" id="submit_edit_category" class="pull-right btn btn-primary" title="Save Changes"><i class="icon fa fa-check-circle" aria-hidden="true"></i>Save Changes</button>
	</div>
@endsection

@section('content')
		@include('cp._partials.sidebar')
		<div class="{{ $mainSmCols }} {{ $mainMdCols }} {{ $mainLgCols }} {{ $mainXlCols }} main">
			@include('cp._partials.message')
			@include('cp._partials.pageTitle')
			<div class="content padding bg-white">
				<form name="editCategory" id="editCategory" class="editCategory" role="form" method="POST" action="/cp/categories/{{ $category->id }}">
					{{ csrf_field() }}
					{{ method_field('PUT') }}
					<input type="hidden" name="id" value="{{ $category->id }}">
					@yield('formButtons')
					<div class="spacer" style="width: auto;margin-left: -15px;margin-right: -15px;"><hr></div>
					<div class="spacer"></div>
					<p><i class="fa fa-info-circle" aria-hidden="true"></i> Fields marked with <span class="text-danger">&#42;</span> are required.</p>
					<div class="spacer"></div>
					<div class="form-group">
						<label for="title" class="control-label font-weight-bold">Title <span class="text-danger">&#42;</span></label>
						<input type="text" name="title" id="title" class="form-control" value="{{ old('title', optional($category)->title) }}" placeholder="e.g Test Title" tabindex="1" autocomplete="off" aria-describedby="helpBlockTitle" required autofocus>
						@if ($errors->has('title'))
							<span id="helpBlockTitle" class="form-control-feedback form-text text-danger">- {{ $errors->first('title') }}</span>
						@endif
						<span id="helpBlockTitle" class="form-control-feedback form-text text-muted"></span>
					</div>
					<div class="spacer"></div>
					<div class="form-group">
						<label for="slug" class="control-label font-weight-bold">Slug <span class="text-danger">&#42;</span></label>
						<input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug', optional($category)->slug) }}" placeholder="e.g Test Slug" tabindex="2" autocomplete="off" aria-describedby="helpBlockSlug" required>
						@if ($errors->has('slug'))
							<span id="helpBlockSlug" class="form-control-feedback form-text text-danger">- {{ $errors->first('slug') }}</span>
						@endif
						<span id="helpBlockSlug" class="form-control-feedback form-text text-muted">- The slug is auto-generated based on the title but feel free to edit it.</span>
					</div>
					<div class="spacer"></div>
					<div class="form-group">
						<label for="description" class="control-label font-weight-bold">Description <span class="text-danger">&#42;</span></label>
						<textarea name="description" id="description" class="form-control" autocomplete="off" placeholder="e.g Test Description" rows="5" cols="50" tabindex="3" aria-describedby="helpBlockDescription" required>{{ old('description', optional($category)->description) }}</textarea>
						@if ($errors->has('description'))
							<span id="helpBlockDescription" class="form-control-feedback form-text text-danger">- {{ $errors->first('description') }}</span>
						@endif
						<span id="helpBlockDescription" class="form-control-feedback form-text text-muted"></span>
					</div>
					<div class="spacer"></div>
					<div class="form-group">
						<label for="banner" class="control-label font-weight-bold">Banner</label>
						<div class="input-group">
							<input type="text" name="banner" id="banner" class="form-control" autocomplete="off" placeholder="e.g Test Banner Image" value="{{ old('banner', optional($category)->banner->path) }}" tabindex="4" autocomplete="off" aria-describedby="helpBlockBanner">
							<div class="input-group-append">
								<a href="javascript:void(0);" title="Select Asset" rel="nofollow" data-toggle="modal" data-target="#banner-browse-modal" data-field_id="banner" data-value="{{ old('banner', optional($category)->banner->url) }}" class="btn btn-secondary">Select Asset</a>
								<a href="javascript:void(0);" title="Clear Asset" rel="nofollow" id="banner-reset-field" class="btn btn-outline-secondary">Clear Asset</a>
							</div>
						</div>
						@if ($errors->has('banner'))
							<span id="helpBlockBanner" class="form-control-feedback form-text text-danger">- {{ $errors->first('banner') }}</span>
						@endif
						<span id="helpBlockBanner" class="form-control-feedback form-text text-muted"></span>
						<div class="modal fade" id="banner-browse-modal" tabindex="-1" role="dialog" aria-labelledby="banner-browse-modal-label" aria-hidden="true">
							<div class="modal-dialog modal-lg modal-xl" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="1_banner-browse-modal-label">Assets</h5>
									</div>
									<div class="modal-body">
										<div class="container-fluid">
											<div class="row no-gutters">
												<div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8 text-left">
													<div id="banner-container"></div>
												</div>
												<div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 text-center">
													<div id="banner-selected-asset-preview"></div>
												</div>
											</div>
										</div>
									</div>
									<div class="modal-footer">
										<div class="container-fluid">
											<div class="row d-flex h-100 justify-content-start">
												<div class="col-12 col-sm-12 col-md-12 col-lg-9 col-xl-9 align-self-center align-self-sm-center align-self-md-left align-self-lg-left align-self-xl-left">
													<div class="text-center text-sm-center text-md-left text-lg-left text-xl-left selected-asset" id="banner-selected-asset"></div>
												</div>
												<div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3 text-center text-sm-center text-md-center text-lg-right text-xl-right align-self-center">
													<button type="button" class="btn btn-link text-secondary" data-dismiss="modal">Close</button>
													<button type="button" class="btn btn-primary" id="banner-select-asset">Insert</button>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="spacer"></div>
					<div class="form-group">
						<label for="image" class="control-label font-weight-bold">Image <span class="text-danger">&#42;</span></label>
						<div class="input-group">
							<input type="text" name="image" id="image" class="form-control" autocomplete="off" placeholder="e.g Test Image" value="{{ old('image', optional($category)->image->path) }}" tabindex="5" autocomplete="off" aria-describedby="helpBlockImage" required>
							<div class="input-group-append">
								<a href="javascript:void(0);" title="Select Asset" rel="nofollow" data-toggle="modal" data-target="#image-browse-modal" data-field_id="image" data-value="{{ old('image', optional($category)->image->url) }}" class="btn btn-secondary">Select Asset</a>
								<a href="javascript:void(0);" title="Clear Asset" rel="nofollow" id="image-reset-field" class="btn btn-outline-secondary">Clear Asset</a>
							</div>
						</div>
						@if ($errors->has('image'))
							<span id="helpBlockImage" class="form-control-feedback form-text text-danger">- {{ $errors->first('image') }}</span>
						@endif
						<span id="helpBlockImage" class="form-control-feedback form-text text-muted"></span>
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
					<div class="form-group">
						<label class="control-label font-weight-bold">Status</label>
						<div class="custom-controls-stacked">
							@foreach ($statuses as $status)
								<div class="custom-control custom-radio d-flex h-100 justify-content-start status_id-{{ $status->id }}">
									<input type="radio" name="status_id" id="status_id-{{ str_slug($status->title) }}" class="custom-control-input" value="{{ $status->id }}" tabindex="6" aria-describedby="helpBlockStatusId" {{ ($category->status_id == $status->id || old('status_id') == $status->id) ? 'checked' : '' }}>
									<label for="status_id-{{ str_slug($status->title) }}" class="custom-control-label align-self-center">{{ $status->title }}@if (!empty($status->description))&nbsp;<i class="fa fa-info-circle text-muted" data-toggle="tooltip" data-placement="top" title="{{ $status->description }}" aria-hidden="true"></i>@endif</label>
								</div>
							@endforeach
						</div>
						@if ($errors->has('status_id'))
							<span id="helpBlockStatusId" class="form-control-feedback form-text text-danger">- {{ $errors->first('status_id') }}</span>
						@endif
						<span id="helpBlockStatusId" class="form-control-feedback form-text text-muted"></span>
					</div>
					<div class="spacer"></div>
					<div class="form-group">
						<label class="control-label font-weight-bold">Sectors <span class="text-danger">&#42;</span></label>
						@if (old('sector_ids'))
							@php ($sectorIds = old('sector_ids'))
						@else
							@php ($sectorIds = $category->sectors->pluck('id')->toArray())
						@endif
						@foreach ($sectors as $sector)
							<div class="spacer"></div>
							<div class="row d-flex h-100 justify-content-center switch-row">
								<div class="col-6 align-self-center">{{ $sector->title }}&nbsp;<i class="fa fa-info-circle text-muted" data-toggle="tooltip" data-placement="top" data-html="true" title="{{ $sector->description }}<br/><br/>This sector belongs to {{ implode(', ', $sector->departments->pluck('title')->toArray()) }}" aria-hidden="true"></i></div>
								<div class="col-6 align-self-center text-right">
									<label for="sector_id-{{ $sector->id }}" class="switch form-check-label"> 
										<input type="checkbox" name="sector_ids[]" id="sector_id-{{ $sector->id }}" value="{{ $sector->id }}" data-slug="{{ $sector->slug }}" tabindex="7" aria-describedby="helpBlockSectorIds" {{ in_array($sector->id, $sectorIds) ? 'checked' : '' }}>
										<span class="slider round"></span>
									</label>
								</div>
							</div>
						@endforeach
						@if ($errors->has('sector_ids'))
							<span id="helpBlockSectorIds" class="form-control-feedback form-text text-danger">- {{ $errors->first('sector_ids') }}</span>
						@endif
						<span id="helpBlockSectorIds" class="form-control-feedback form-text text-muted"></span>
					</div>
					<div class="spacer"></div>
					<div class="spacer" style="width: auto;margin-left: -15px;margin-right: -15px;margin-bottom: -30px;"><hr></div>
					@yield('formButtons')
				</form>
			</div>
			@include('cp._partials.footer')
		</div>
@endsection
