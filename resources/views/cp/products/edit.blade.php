@extends('_layouts.cp')

@section('title', 'Edit Product - Products - '.config('app.name'))
@section('description', 'Edit Product - Products - '.config('app.name'))
@section('keywords', 'Edit, Product, Products, '.config('app.name'))

@push('styles')
	@include('cp._partials.styles')
@endpush

@push('headScripts')
	@include('cp._partials.headScripts')
@endpush

@push('bodyScripts')
	@include('cp._partials.bodyScripts')
	@include('cp._partials.assetBrowser')
	@include('cp._partials.toggleSwitches')
	@include('_partials.loadPlayer')
@endpush

@section('formButtons')
	<div class="form-buttons">
		@if ($currentUser->hasPermission('view_products'))
			<a href="/cp/products" title="Cancel" class="btn btn-link text-secondary" title="Cancel">Cancel</a>
		@endif
		<button type="submit" name="submit_edit_product" id="submit_edit_product" class="pull-right btn btn-primary" title="Save Changes"><i class="icon fa fa-check-circle" aria-hidden="true"></i>Save Changes</button>
	</div>
@endsection

@section('content')
		@include('cp._partials.sidebar')
		<div class="{{ $mainSmCols }} {{ $mainMdCols }} {{ $mainLgCols }} {{ $mainXlCols }} main">
			@include('cp._partials.message')
			@include('cp._partials.pageTitle')
			<div class="content padding bg-white">
				<form name="editProduct" id="editProduct" class="editProduct" role="form" method="POST" action="/cp/products/{{ $product->id }}" enctype="multipart/form-data">
					{{ csrf_field() }}
					{{ method_field('PUT') }}
					<input type="hidden" name="id" value="{{ $product->id }}">
					@yield('formButtons')
					<div class="spacer" style="width: auto;margin-left: -15px;margin-right: -15px;"><hr></div>
					<div class="spacer"></div>
					<p><i class="fa fa-info-circle" aria-hidden="true"></i> Fields marked with <span class="text-danger">&#42;</span> are required.</p>
					<div class="spacer"></div>
					<div class="form-group">
						<label for="title" class="control-label font-weight-bold">Title <span class="text-danger">&#42;</span></label>
						<input type="text" name="title" id="title" class="form-control" value="{{ old('title', optional($product)->title) }}" placeholder="e.g Test Title" tabindex="1" autocomplete="off" aria-describedby="helpBlockTitle" required autofocus>
						@if ($errors->has('title'))
							<span id="helpBlockTitle" class="form-control-feedback form-text text-danger">- {{ $errors->first('title') }}</span>
						@endif
						<span id="helpBlockTitle" class="form-control-feedback form-text text-muted"></span>
					</div>
					<div class="spacer"></div>
					<div class="form-group">
						<label for="slug" class="control-label font-weight-bold">Slug <span class="text-danger">&#42;</span></label>
						<input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug', optional($product)->slug) }}" placeholder="e.g Test Slug" tabindex="2" autocomplete="off" aria-describedby="helpBlockSlug" required>
						@if ($errors->has('slug'))
							<span id="helpBlockSlug" class="form-control-feedback form-text text-danger">- {{ $errors->first('slug') }}</span>
						@endif
						<span id="helpBlockSlug" class="form-control-feedback form-text text-muted">- The slug is auto-generated based on the title but feel free to edit it.</span>
					</div>
					<div class="spacer"></div>
					<div class="form-group">
						<label for="description" class="control-label font-weight-bold">Description <span class="text-danger">&#42;</span></label>
						<textarea name="description" id="description" class="form-control" autocomplete="off" placeholder="e.g Test Description" rows="5" cols="50" tabindex="3" aria-describedby="helpBlockDescription" required>{{ old('description', optional($product)->description) }}</textarea>
						@if ($errors->has('description'))
							<span id="helpBlockDescription" class="form-control-feedback form-text text-danger">- {{ $errors->first('description') }}</span>
						@endif
						<span id="helpBlockDescription" class="form-control-feedback form-text text-muted"></span>
					</div>
					<div class="spacer"></div>
					<div class="form-group">
						<label for="banner" class="control-label font-weight-bold">Banner</label>
						<div class="input-group">
							<input type="text" name="banner" id="banner" class="form-control" autocomplete="off" placeholder="e.g Test Banner Image" value="{{ old('banner', optional($product)->banner->path) }}" tabindex="4" autocomplete="off" aria-describedby="helpBlockBanner">
							<div class="input-group-append">
								<a href="javascript:void(0);" title="Select Asset" rel="nofollow" data-toggle="modal" data-target="#banner-browse-modal" data-field_id="banner" data-value="{{ old('banner', optional($product)->banner->url) }}" class="btn btn-secondary">Select Asset</a>
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
							<input type="text" name="image" id="image" class="form-control" value="{{ old('image', optional($product)->image->path) }}" placeholder="e.g Test Image" tabindex="5" autocomplete="off" aria-describedby="helpBlockImage" required>
							<div class="input-group-append">
								<a href="javascript:void(0);" title="Select Asset" rel="nofollow" data-toggle="modal" data-target="#image-browse-modal" data-field_id="image" data-value="{{ old('image', optional($product)->image->url) }}" class="btn btn-secondary">Select Asset</a>
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
									<input type="radio" name="status_id" id="status_id-{{ str_slug($status->title) }}" class="custom-control-input" value="{{ $status->id }}" tabindex="6" aria-describedby="helpBlockStatusId" {{ ($product->status_id == $status->id || old('status_id') == $status->id) ? 'checked' : '' }}>
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
						<label class="control-label font-weight-bold">Departments <span class="text-danger">&#42;</span></label>
						@if (old('department_ids'))
							@php ($departmentIds = old('department_ids'))
						@else
							@php ($departmentIds = $product->departments->pluck('id')->toArray())
						@endif
						@foreach ($departments as $department)
							<div class="spacer"></div>
							<div class="row d-flex h-100 justify-content-center switch-row">
								<div class="col-6 align-self-center">{{ $department->title }}&nbsp;<i class="fa fa-info-circle text-muted" data-toggle="tooltip" data-placement="top" data-html="true" title="{{ $department->description }}" aria-hidden="true"></i></div>
								<div class="col-6 align-self-center text-right">
									<label for="department_id-{{ $department->id }}" class="switch form-check-label"> 
										<input type="checkbox" name="department_ids[]" id="department_id-{{ $department->id }}" value="{{ $department->id }}" data-slug="{{ $department->slug }}" tabindex="7" aria-describedby="helpBlockDepartmentIds" {{ in_array($department->id, $departmentIds) ? 'checked' : '' }}>
										<span class="slider round"></span>
									</label>
								</div>
							</div>
						@endforeach
						@if ($errors->has('department_ids'))
							<span id="helpBlockDepartmentIds" class="form-control-feedback form-text text-danger">- {{ $errors->first('department_ids') }}</span>
						@endif
						<span id="helpBlockDepartmentIds" class="form-control-feedback form-text text-muted"></span>
					</div>
					<div class="spacer"></div>
					<div class="form-group">
						<label class="control-label font-weight-bold">Sectors <span class="text-danger">&#42;</span></label>
						@if (old('sector_ids'))
							@php ($sectorIds = old('sector_ids'))
						@else
							@php ($sectorIds = $product->sectors->pluck('id')->toArray())
						@endif
						@foreach ($sectors as $sector)
							@php ($departmentClasses = implode(' ', $sector->departments->pluck('slug')->toArray()))
							<div class="sector-switch {{ $departmentClasses }}" style="opacity: {{ in_array($sector->id, $sectorIds) ? 1 : 0.5 }};">
								<div class="spacer"></div>
								<div class="row d-flex h-100 justify-content-center switch-row">
									<div class="col-6 align-self-center">{{ $sector->title }}&nbsp;<i class="fa fa-info-circle text-muted" data-toggle="tooltip" data-placement="top" data-html="true" title="{{ $sector->description }}<br/><br/>This sector belongs to {{ implode(', ', $sector->departments->pluck('title')->toArray()) }}" aria-hidden="true"></i></div>
									<div class="col-6 align-self-center text-right">
										<label for="sector_id-{{ $sector->id }}" class="switch form-check-label"> 
											<input type="checkbox" name="sector_ids[]" id="sector_id-{{ $sector->id }}" value="{{ $sector->id }}" data-slug="{{ $sector->slug }}" tabindex="8" aria-describedby="helpBlockSectorIds" {{ in_array($sector->id, $sectorIds) ? 'checked' : '' }} disabled>
											<span class="slider round"></span>
										</label>
									</div>
								</div>
							</div>
						@endforeach
						@if ($errors->has('sector_ids'))
							<span id="helpBlockArticleSectorIds" class="form-control-feedback form-text text-danger">- {{ $errors->first('sector_ids') }}</span>
						@endif
						<span id="helpBlockArticleSectorIds" class="form-control-feedback form-text text-muted"></span>
					</div>
					<div class="spacer"></div>
					<div class="form-group">
						<label class="control-label font-weight-bold">Categories</label>
						@if (old('category_ids'))
							@php ($categoryIds = old('category_ids'))
						@else
							@php ($categoryIds = $product->categories->pluck('id')->toArray())
						@endif
						@foreach ($categories as $category)
							@php ($sectorClasses = implode(' ', $category->sectors->pluck('slug')->toArray()))
							<div class="category-switch {{ $sectorClasses }}" style="opacity: {{ in_array($category->id, $categoryIds) ? 1 : 0.5 }};">
								<div class="spacer"></div>
								<div class="row d-flex h-100 justify-content-center switch-row">
									<div class="col-6 align-self-center">{{ $category->title }}&nbsp;<i class="fa fa-info-circle text-muted" data-toggle="tooltip" data-placement="top" data-html="true" title="{{ $category->description }}<br/><br/>This category belongs to {{ implode(', ', $category->sectors->pluck('title')->toArray()) }}" aria-hidden="true"></i></div>
									<div class="col-6 align-self-center text-right">
										<label for="category_id-{{ $category->id }}" class="switch form-check-label"> 
											<input type="checkbox" name="category_ids[]" id="category_id-{{ $category->id }}" value="{{ $category->id }}" data-slug="{{ $category->slug }}" tabindex="9" aria-describedby="helpBlockCategoryIds" {{ in_array($category->id, $categoryIds) ? 'checked' : '' }} disabled>
											<span class="slider round"></span>
										</label>
									</div>
								</div>
							</div>
						@endforeach
						@if ($errors->has('category_ids'))
							<span id="helpBlockCategoryIds" class="form-control-feedback form-text text-danger">- {{ $errors->first('category_ids') }}</span>
						@endif
						<span id="helpBlockCategoryIds" class="form-control-feedback form-text text-muted"></span>
					</div>
					<div class="spacer"></div>
					<div class="form-group">
						<label class="control-label font-weight-bold">Content Types</label>
						@if (old('content_type_ids'))
							@php ($contentTypeIds = old('content_type_ids'))
						@else
							@php ($contentTypeIds = $product->contentTypes->pluck('id')->toArray())
						@endif
						@foreach ($contentTypes as $contentType)
							@php ($categoryClasses = implode(' ', $contentType->categories->pluck('slug')->toArray()))
							<div class="content-type-switch {{ $categoryClasses }}" style="opacity: {{ in_array($contentType->id, $contentTypeIds) ? 1 : 0.5 }};">
								<div class="spacer"></div>
								<div class="row d-flex h-100 justify-content-center switch-row">
									<div class="col-6 align-self-center">{{ $contentType->title }}&nbsp;<i class="fa fa-info-circle text-muted" data-toggle="tooltip" data-placement="top" data-html="true" title="{{ $contentType->description }}<br/><br/>This content type belongs to {{ implode(', ', $contentType->categories->pluck('title')->toArray()) }}" aria-hidden="true"></i></div>
									<div class="col-6 align-self-center text-right">
										<label for="content_type_id-{{ $contentType->id }}" class="switch form-check-label"> 
											<input type="checkbox" name="content_type_ids[]" id="content_type_id-{{ $contentType->id }}" value="{{ $contentType->id }}" data-slug="{{ $contentType->slug }}" tabindex="10" aria-describedby="helpBlockContentTypeIds" {{ in_array($contentType->id, $contentTypeIds) ? 'checked' : '' }} disabled>
											<span class="slider round"></span>
										</label>
									</div>
								</div>
							</div>
						@endforeach
						@if ($errors->has('content_type_ids'))
							<span id="helpBlockContentTypeIds" class="form-control-feedback form-text text-danger">- {{ $errors->first('content_type_ids') }}</span>
						@endif
						<span id="helpBlockContentTypeIds" class="form-control-feedback form-text text-muted"></span>
					</div>
					<div class="spacer"></div>
					<div class="form-group supporting-files">
						<label for="supporting_files" class="control-label font-weight-bold">Supporting Files</label>
						@if ($supportingFiles->count())
							<ul class="list-unstyled mt-1">
								@foreach ($supportingFiles as $supportingFile)
									<li><i class="icon fa fa-file" aria-hidden="true"></i><a href="javascript:void(0);" title="{{ $supportingFile->name }}" class="text-info" rel="nofollow" data-toggle="modal" data-target=".supporting-file-{{ $supportingFile->id }}-modal-lg">{{ $supportingFile->name }}</a> (Click to manage)</li>
								@endforeach
							</ul>
						@endif
						<input type="file" name="supporting_files[]" id="supporting_files" class="form-control" value="{{ old('supporting_files') }}" tabindex="11" autocomplete="off" aria-describedby="helpBlockSupportingFiles" multiple>
						@if ($errors->has('supporting_files'))
							<span id="helpBlockSupportingFiles" class="form-control-feedback form-text text-danger">- {{ $errors->first('supporting_files') }}</span>
						@endif
						<span id="helpBlockSupportingFiles" class="form-control-feedback form-text text-muted">- Please click on the browse button to select supporting files.</span>
					</div>
					<div id="new-product-development-sector" class="d-none">
						<div class="spacer"></div>
						<div class="form-group">
							<label for="overview" class="control-label font-weight-bold">Overview</label>
							<textarea name="overview" id="overview" class="form-control" autocomplete="off" placeholder="e.g Test New Product Development Overview" rows="5" cols="50" tabindex="13" aria-describedby="helpBlockOverview">{{ old('overview', optional($product)->overview) }}</textarea>
							@if ($errors->has('overview'))
								<span id="helpBlockOverview" class="form-control-feedback form-text text-danger">- {{ $errors->first('overview') }}</span>
							@endif
							<span id="helpBlockOverview" class="form-control-feedback form-text text-muted"></span>
						</div>
						<div class="spacer"></div>
						<div class="form-group">
							<label for="due_date" class="control-label font-weight-bold">Due Date</label>
							<input type="text" name="due_date" id="due_date" class="form-control" value="{{ old('due_date', optional($product)->due_date) }}" placeholder="e.g Q3 2018" tabindex="14" autocomplete="off" aria-describedby="helpBlockDueDate">
							@if ($errors->has('due_date'))
								<span id="helpBlockDueDate" class="form-control-feedback form-text text-danger">- {{ $errors->first('due_date') }}</span>
							@endif
							<span id="helpBlockDueDate" class="form-control-feedback form-text text-muted"></span>
						</div>
						<div class="spacer"></div>
						<div class="form-group">
							<label for="npd_feedback_recipient" class="control-label font-weight-bold">Feedback Recipient</label>
							<input type="email" name="npd_feedback_recipient" id="npd_feedback_recipient" class="form-control" value="{{ old('npd_feedback_recipient', optional($product)->npd_feedback_recipient) }}" placeholder="e.g david.mccourt@survitecgroup.com" tabindex="15" autocomplete="off" aria-describedby="helpBlockFeedbackRecipient">
							@if ($errors->has('npd_feedback_recipient'))
								<span id="helpBlockFeedbackRecipient" class="form-control-feedback form-text text-danger">- {{ $errors->first('npd_feedback_recipient') }}</span>
							@endif
							<span id="helpBlockFeedbackRecipient" class="form-control-feedback form-text text-muted">- This is the email address of the person who is to receive product feedback.</span>
						</div>
					</div>
					<div id="video-content-type" class="d-none">
						<div class="spacer"></div>
						<div class="form-group">
							<label for="video" class="control-label font-weight-bold">Video</label>
							<input type="text" name="video" id="video" class="form-control" value="{{ old('video', optional($product)->video) }}" placeholder="e.g https://www.youtube.com/watch?v=xjS6SftYQaQ" tabindex="16" autocomplete="off" aria-describedby="helpBlockVideo">
							@if ($errors->has('video'))
								<span id="helpBlockVideo" class="form-control-feedback form-text text-danger">- {{ $errors->first('video') }}</span>
							@endif
							<span id="helpBlockVideo" class="form-control-feedback form-text text-muted">- Supports regular URLs: http://www.youtube.com/watch?v=xjS6SftYQaQ</span>
							<span id="helpBlockVideo" class="form-control-feedback form-text text-muted">- Supports embedded URLs: http://www.youtube.com/embed/xjS6SftYQaQ</span>
							<span id="helpBlockVideo" class="form-control-feedback form-text text-muted">- Supports playlist URLs: http://www.youtube.com/playlist?list=PLA60DCEB33156E51F or http://www.youtube.com/watch?v=xjS6SftYQaQ&list=SPA60DCEB33156E51F</span>
						</div>
					</div>
					<div id="existing-products-sector" class="d-none">
						<div class="spacer"></div>
						<div class="form-group">
							<label for="minimum_number_of_units" class="control-label font-weight-bold">Minimum Number of Units <span class="text-danger">&#42;</span></label>
							<input type="number" name="minimum_number_of_units" id="minimum_number_of_units" class="form-control" value="{{ old('minimum_number_of_units', optional($product)->minimum_number_of_units ?: 0) }}" placeholder="e.g 10" tabindex="17" autocomplete="off" aria-describedby="helpBlockMinimumNumberOfUnits" min="0" max="1000">
							@if ($errors->has('minimum_number_of_units'))
								<span id="helpBlockMinimumNumberOfUnits" class="form-control-feedback form-text text-danger">- {{ $errors->first('minimum_number_of_units') }}</span>
							@endif
							<span id="helpBlockMinimumNumberOfUnits" class="form-control-feedback form-text text-muted"</span>
						</div>
						<div class="spacer"></div>
						<div class="form-group">
							<label for="ex_feedback_recipient" class="control-label font-weight-bold">Feedback Recipient</label>
							<input type="email" name="ex_feedback_recipient" id="ex_feedback_recipient" class="form-control" value="{{ old('ex_feedback_recipient', optional($product)->ex_feedback_recipient) }}" placeholder="e.g david.mccourt@survitecgroup.com" tabindex="15" autocomplete="off" aria-describedby="helpBlockFeedbackRecipient">
							@if ($errors->has('ex_feedback_recipient'))
								<span id="helpBlockFeedbackRecipient" class="form-control-feedback form-text text-danger">- {{ $errors->first('ex_feedback_recipient') }}</span>
							@endif
							<span id="helpBlockFeedbackRecipient" class="form-control-feedback form-text text-muted">- This is the email address of the person who is to receive product feedback.</span>
						</div>
					</div>
					<div class="spacer"></div>
					<div class="spacer" style="width: auto;margin-left: -15px;margin-right: -15px;margin-bottom: -30px;"><hr></div>
					@yield('formButtons')
				</form>
				@foreach ($supportingFiles as $supportingFile)
					@php ($tempUrl = $supportingFile->custom_properties['url'])
					<div class="modal fade supporting-file supporting-file-{{ $supportingFile->id }}-modal-lg" tabindex="-1" role="dialog" aria-labelledby="supportingFileModalLabel" aria-hidden="true">
						<div class="modal-dialog modal-md modal-lg modal-xl">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="supportingFileModalLabel">{{ $supportingFile->file_name }}</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								</div>
								<div class="modal-body">
									<div class="row d-flex h-100 justify-content-start">
										<div class="col-12 col-sm-12 col-md-12 col-lg-7 col-xl-7 text-center align-self-center">
											@if (starts_with($supportingFile->mime_type, 'image'))
												@if (!empty($supportingFile->custom_properties['thumbnail']))
													@php ($tempThumbnailUrl = $supportingFile->custom_properties['thumbnail']['url'])
													<a href="{{ $tempUrl }}" title="{{ $supportingFile->custom_properties['thumbnail']['name'] }}" class="text-dark"><img src="{{ $tempThumbnailUrl }}" style="max-width: 100%;" class="align-top text-center" alt="{{ $supportingFile->custom_properties['thumbnail']['name'] }}"></a>
													<p class="pt-3"><a href="/cp/products/{{ $product->id }}/supporting-files/{{ $supportingFile->id }}/thumbnail/delete" title="Delete Thumbnail" class="btn btn-sm btn-outline-danger">Delete Thumbnail</a></li>
												@else
													<a href="{{ $tempUrl }}" title="{{ $supportingFile->name }}" target="_blank" class="text-dark"><img src="{{ $tempUrl }}" style="max-width: 100%;" class="align-top text-center" alt="{{ $supportingFile->name }}"></a>
													<p><a href="/cp/products/{{ $product->id }}/supporting-files/{{ $supportingFile->id }}/thumbnail/add" title="Add Thumbnail" class="btn btn-sm btn-outline-secondary">Add Thumbnail</a></li>
												@endif
											@elseif (starts_with($supportingFile->mime_type, 'video'))
												<video poster="" controls crossorigin>
													@if (str_contains($supportingFile->mime_type, 'mp4'))
														<source src="{{ $tempUrl }}" type="video/mp4"></source>
													@endif
													@if (str_contains($supportingFile->mime_type, 'webm'))	
														<source src="{{ $tempUrl }}" type="video/webm"></source>
													@endif
													@if (str_contains($supportingFile->mime_type, 'ogg'))
														<source src="{{ $tempUrl }}" type="video/ogg"></source>
													@endif
													@if (str_contains($supportingFile->mime_type, 'mp4'))
														<a href="{{ $tempUrl }}" download>Download</a>
													@endif
												</video>
												@if (!empty($supportingFile->custom_properties['thumbnail']))
													@php ($tempThumbnailUrl = $supportingFile->custom_properties['thumbnail']['url'])
													<a href="{{ $tempUrl }}" title="{{ $supportingFile->custom_properties['thumbnail']['name'] }}" class="text-dark"><img src="{{ $tempThumbnailUrl }}" style="max-width: 100%;" class="align-top text-center" alt="{{ $supportingFile->custom_properties['thumbnail']['name'] }}"></a>
													<p class="pt-3"><a href="/cp/products/{{ $product->id }}/supporting-files/{{ $supportingFile->id }}/thumbnail/delete" title="Delete Thumbnail" class="btn btn-sm btn-outline-danger">Delete Thumbnail</a></li>
												@else
													<p><a href="/cp/products/{{ $product->id }}/supporting-files/{{ $supportingFile->id }}/thumbnail/add" title="Add Thumbnail" class="btn btn-sm btn-outline-secondary">Add Thumbnail</a></li>
												@endif
											@else
												@if (!empty($supportingFile->custom_properties['thumbnail']))
													@php ($tempThumbnailUrl = $supportingFile->custom_properties['thumbnail']['url'])
													<a href="{{ $tempUrl }}" title="{{ $supportingFile->custom_properties['thumbnail']['name'] }}" class="text-dark"><img src="{{ $tempThumbnailUrl }}" style="max-width: 100%;" class="align-top text-center" alt="{{ $supportingFile->custom_properties['thumbnail']['name'] }}"></a>
													<p class="pt-3"><a href="/cp/products/{{ $product->id }}/supporting-files/{{ $supportingFile->id }}/thumbnail/delete" title="Delete Thumbnail" class="btn btn-sm btn-outline-danger">Delete Thumbnail</a></li>
												@else
													<p>&nbsp;</p>
													<p><a href="{{ $tempUrl }}" title="{{ $supportingFile->name }}" target="_blank" class="text-dark"><i class="fa fa-file fa-5x align-middle" aria-hidden="true"></i><br><br>No Preview Available</a></p>
													<p><a href="/cp/products/{{ $product->id }}/supporting-files/{{ $supportingFile->id }}/thumbnail/add" title="Add Thumbnail" class="btn btn-sm btn-outline-secondary">Add Thumbnail</a></li>
												@endif
											@endif
										</div>
										<div class="col-12 col-sm-12 col-md-12 col-lg-5 col-xl-5 text-left">
											<div class="spacer d-block d-sm-block d-md-block d-lg-none d-xl-none"></div>
											<h5>Meta Data</h5>
											<div class="spacer"></div>
											<form>
												<div class="form-group">
													<label class="d-block">File Uploaded: <strong>{{ $supportingFile->created_at }}</strong></label>
												</div>
												<div class="form-group">
													<label for="file_url">File URL:</label>
													<div class="input-group">
														<input type="text" class="form-control bg-transparent" value="{{ $tempUrl }}" id="file_url" readonly>
														<div class="input-group-append">
															<span class="input-group-text"><a href="javascript:void(0);" title="Copy File URL to clipboard" rel="nofollow" class="clipboard" data-clipboard data-clipboard-target="#file_url"><i class="fa fa-clipboard" title="Copy" aria-hidden="true"></i></a></span>
														</div>
													</div>
												</div>
												<div class="form-group">
													<label>File type: <strong>{{ $supportingFile->mime_type }}</strong></label>
												</div>
												<div class="form-group">
													<label>File size: <strong>{{ $supportingFile->human_readable_size }}</strong></label>
												</div>
												@if ($supportingFile->hasCustomProperty('dimensions.width') && $supportingFile->hasCustomProperty('dimensions.height'))
													<div class="form-group">
														<label>Dimensions: <strong>{{ $supportingFile->getCustomProperty('dimensions.width') }} x {{ $supportingFile->getCustomProperty('dimensions.height') }}</strong></label>
													</div>
												@endif
											</form>
											<div class="spacer tall"><hr></div>
											<ul class="list-unstyled list-inline">
												<li class="list-inline-item pull-right"><a href="/cp/products/{{ $product->id }}/supporting-files/{{ $supportingFile->id }}/delete/" title="Delete Supporting File" class="btn btn-outline-danger">Delete</a></li>
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
				@endforeach			
			</div>
			@include('cp._partials.footer')
		</div>
@endsection
