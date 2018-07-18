@extends('_layouts.cp')

@section('title', 'Create Expert - Experts - '.config('app.name'))
@section('description', 'Create Expert - Experts - '.config('app.name'))
@section('keywords', 'Create, Expert, Experts, '.config('app.name'))

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
		@if ($currentUser->hasPermission('view_experts'))
			<a href="/cp/experts" title="Cancel" class="btn btn-link text-secondary" title="Cancel">Cancel</a>
		@endif
		<button type="submit" name="submit_create_expert" id="submit_create_expert" class="pull-right btn btn-primary" title="Save Changes"><i class="icon fa fa-check-circle" aria-hidden="true"></i>Save Changes</button>
	</div>
@endsection

@section('content')
		@include('cp._partials.sidebar')
		<div class="{{ $mainSmCols }} {{ $mainMdCols }} {{ $mainLgCols }} {{ $mainXlCols }} main">
			@include('cp._partials.message')
			@include('cp._partials.pageTitle')
			<div class="content padding bg-white">
				<form name="createExpert" id="createExpert" class="createExpert" role="form" method="POST" action="/cp/experts">
					{{ csrf_field() }}
					@yield('formButtons')
					<div class="spacer" style="width: auto;margin-left: -15px;margin-right: -15px;"><hr></div>
					<div class="spacer"></div>
					<p><i class="fa fa-info-circle" aria-hidden="true"></i> Fields marked with <span class="text-danger">&#42;</span> are required.</p>
					<div class="spacer"></div>
					<div class="form-group">
						<label for="full_name" class="control-label font-weight-bold">Full Name <span class="text-danger">&#42;</span></label>
						<input type="text" name="full_name" id="full_name" class="form-control" value="{{ old('full_name') }}" placeholder="e.g Test Full Name" tabindex="1" autocomplete="off" aria-describedby="helpBlockFullName" required autofocus>
						@if ($errors->has('full_name'))
							<span id="helpBlockFullName" class="form-control-feedback form-text text-danger">- {{ $errors->first('full_name') }}</span>
						@endif
						<span id="helpBlockFullName" class="form-control-feedback form-text text-muted"></span>
					</div>
					<div class="spacer"></div>
					<div class="form-group">
						<label for="email" class="control-label font-weight-bold">Email Address <span class="text-danger">&#42;</span></label>
						<input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" placeholder="e.g Test Email" tabindex="2" autocomplete="off" aria-describedby="helpBlockEmailAddress" required>
						@if ($errors->has('email'))
							<span id="helpBlockEmailAddress" class="form-control-feedback form-text text-danger">- {{ $errors->first('email') }}</span>
						@endif
						<span id="helpBlockEmailAddress" class="form-control-feedback form-text text-muted">- Please enter the email address in lowercase.</span>
						<span id="did-you-mean" class="form-control-feedback form-text text-danger">- Did you mean <a href="javascript:void(0);" title="Click to fix your mistake." rel="nofollow"></a>?</span>
					</div>
					<div class="spacer"></div>
					<div class="form-group">
						<label for="position" class="control-label font-weight-bold">Position <span class="text-danger">&#42;</span></label>
						<input type="text" name="position" id="position" class="form-control" value="{{ old('position') }}" placeholder="e.g Test Position" tabindex="3" autocomplete="off" aria-describedby="helpBlockPosition" required>
						@if ($errors->has('position'))
							<span id="helpBlockPosition" class="form-control-feedback form-text text-danger">- {{ $errors->first('position') }}</span>
						@endif
						<span id="helpBlockPosition" class="form-control-feedback form-text text-muted"></span>
					</div>
					<div class="spacer"></div>
					<div class="form-group">
						<label for="image" class="control-label font-weight-bold">Mugshot</label>
						<div class="input-group">
							<input type="text" name="image" id="image" class="form-control" autocomplete="off" placeholder="e.g Test Mugshot" value="{{ old('image') }}" tabindex="4" autocomplete="off" aria-describedby="helpBlockMugshot">
							<div class="input-group-append">
								<a href="javascript:void(0);" title="Select Asset" rel="nofollow" data-toggle="modal" data-target="#image-browse-modal" data-field_id="image" data-value="{{ old('image') }}" class="btn btn-secondary">Select Asset</a>
								<a href="javascript:void(0);" title="Clear Asset" rel="nofollow" id="image-reset-field" class="btn btn-outline-secondary">Clear Asset</a>
							</div>
						</div>
						@if ($errors->has('image'))
							<span id="helpBlockMugshot" class="form-control-feedback form-text text-danger">- {{ $errors->first('image') }}</span>
						@endif
						<span id="helpBlockMugshot" class="form-control-feedback form-text text-muted"></span>
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
									<input type="radio" name="status_id" id="status_id-{{ str_slug($status->title) }}" class="custom-control-input" value="{{ $status->id }}" tabindex="5" aria-describedby="helpBlockStatusId" {{ (old('status_id') == $status->id) ? 'checked' : ($loop->first) ? 'checked' : '' }}>
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
						<label class="control-label font-weight-bold">Categories <span class="text-danger">&#42;</span></label>
						@php ($categoryIds = old('category_ids') ?? [])
						@foreach ($categories as $category)
							<div class="spacer"></div>
							<div class="row d-flex h-100 justify-content-center">
								<div class="col-6 align-self-center">{{ $category->title }}</div>
								<div class="col-6 align-self-center text-right">
									<label for="category_id-{{ $category->id }}" class="switch form-check-label"> 
										<input type="checkbox" name="category_ids[]" id="category_id-{{ $category->id }}" value="{{ $category->id }}" data-slug="{{ $category->slug }}" tabindex="6" aria-describedby="helpBlockCategoryIds" {{ in_array($category->id, $categoryIds) ? 'checked' : '' }}>
										<span class="slider round"></span>
									</label>
								</div>
							</div>
						@endforeach
						@if ($errors->has('category_ids'))
							<span id="helpBlockCategoryIds" class="form-control-feedback form-text text-danger">- {{ $errors->first('category_ids') }}</span>
						@endif
						<span id="helpBlockCategoryIds" class="form-control-feedback form-text text-muted"></span>
					</div>
					<div class="spacer"></div>
					<div class="spacer" style="width: auto;margin-left: -15px;margin-right: -15px;margin-bottom: -30px;"><hr></div>
					@yield('formButtons')
				</form>
			</div>
			@include('cp._partials.footer')
		</div>
@endsection
