@extends('_layouts.cp')

@section('title', 'Edit Idea - Ideas - '.config('app.name'))
@section('description', 'Edit Idea - Ideas - '.config('app.name'))
@section('keywords', 'Edit, Idea, Ideas, Cases, '.config('app.name'))

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
		@if ($currentUser->hasPermission('view_ideas'))
			<a href="/cp/ideas" title="Cancel" class="btn btn-link text-secondary" title="Cancel">Cancel</a>
		@endif
		<button type="submit" name="submit_edit_idea" id="submit_edit_idea" class="pull-right btn btn-primary" title="Save Changes"><i class="icon fa fa-check-circle" aria-hidden="true"></i>Save Changes</button>
	</div>
@endsection

@section('content')
		@include('cp._partials.sidebar')
		<div class="{{ $mainSmCols }} {{ $mainMdCols }} {{ $mainLgCols }} {{ $mainXlCols }} main">
			@include('cp._partials.message')
			@include('cp._partials.pageTitle')
			<div class="content padding bg-white">
				<form name="editIdea" id="editIdea" class="editIdea" role="form" method="POST" action="/cp/ideas/{{ $idea->id }}" enctype="multipart/form-data">
					{{ csrf_field() }}
					{{ method_field('PUT') }}
					<input type="hidden" name="id" value="{{ $idea->id }}">
					@yield('formButtons')
					<div class="spacer" style="width: auto;margin-left: -15px;margin-right: -15px;"><hr></div>
					<div class="spacer"></div>
					<p><i class="fa fa-info-circle" aria-hidden="true"></i> Fields marked with <span class="text-danger">&#42;</span> are required.</p>
					<div class="spacer"></div>
					<div class="form-group">
						<label for="title" class="control-label font-weight-bold">Title <span class="text-danger">&#42;</span></label>
						<input type="text" name="title" id="title" class="form-control" value="{{ old('title', optional($idea)->title) }}" placeholder="e.g Test Title" tabindex="1" autocomplete="off" aria-describedby="helpBlockTitle" required autofocus>
						@if ($errors->has('title'))
							<span id="helpBlockTitle" class="form-control-feedback form-text text-danger">- {{ $errors->first('title') }}</span>
						@endif
						<span id="helpBlockTitle" class="form-control-feedback form-text text-muted"></span>
					</div>
					<div class="spacer"></div>
					<div class="form-group">
						<label for="slug" class="control-label font-weight-bold">Slug <span class="text-danger">&#42;</span></label>
						<input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug', optional($idea)->slug) }}" placeholder="e.g Test Slug" tabindex="2" autocomplete="off" aria-describedby="helpBlockSlug" required>
						@if ($errors->has('slug'))
							<span id="helpBlockSlug" class="form-control-feedback form-text text-danger">- {{ $errors->first('slug') }}</span>
						@endif
						<span id="helpBlockSlug" class="form-control-feedback form-text text-muted">- The slug is auto-generated based on the title but feel free to edit it.</span>
					</div>
					<div class="spacer"></div>
					<div class="form-group">
						<label for="description" class="control-label font-weight-bold">Description <span class="text-danger">&#42;</span></label>
						<textarea name="redactor" id="description" class="form-control redactor" autocomplete="off" placeholder="e.g Test Description" rows="5" cols="50" tabindex="3" aria-describedby="helpBlockDescription" required>{{ old('description', optional($idea)->description) }}</textarea>
						@if ($errors->has('description'))
							<span id="helpBlockDescription" class="form-control-feedback form-text text-danger">- {{ $errors->first('description') }}</span>
						@endif
						<span id="helpBlockDescription" class="form-control-feedback form-text text-muted"></span>
					</div>
					<div class="spacer"></div>
					<div class="form-group">
						<label for="image" class="control-label font-weight-bold">Image <span class="text-danger">&#42;</span></label>
						<div class="input-group">
							<input type="text" name="image" id="image" class="form-control" autocomplete="off" placeholder="e.g Test Image" value="{{ old('image', optional($idea)->image->path) }}" tabindex="5" autocomplete="off" aria-describedby="helpBlockImage" required>
							<div class="input-group-append">
								<a href="javascript:void(0);" title="Select Asset" rel="nofollow" data-toggle="modal" data-target="#image-browse-modal" data-field_id="image" data-value="{{ old('image', optional($idea)->image->url) }}" class="btn btn-secondary">Select Asset</a>
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
						<label for="submitted_by" class="control-label font-weight-bold">Submitted By <span class="text-danger">&#42;</span></label>
						<input type="text" name="submitted_by" id="submitted_by" class="form-control" value="{{ old('submitted_by', optional($idea)->submitted_by) }}" placeholder="e.g David" tabindex="6" autocomplete="off" aria-describedby="helpBlockSubmittedBy" required>
						@if ($errors->has('submitted_by'))
							<span id="helpBlockSubmittedBy" class="form-control-feedback form-text text-danger">- {{ $errors->first('submitted_by') }}</span>
						@endif
						<span id="helpBlockSubmittedBy" class="form-control-feedback form-text text-muted"></span>
					</div>
					<div class="spacer"></div>
					<div class="form-group">
						<label class="control-label font-weight-bold">Status</label>
						<div class="custom-controls-stacked">
							@foreach ($statuses as $status)
								<div class="custom-control custom-radio d-flex h-100 justify-content-start status_id-{{ $status->id }}">
									<input type="radio" name="status_id" id="status_id-{{ str_slug($status->title) }}" class="custom-control-input" value="{{ $status->id }}" tabindex="7" aria-describedby="helpBlockStatusId" {{ ($idea->status_id == $status->id || old('status_id') == $status->id) ? 'checked' : '' }}>
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
							@php ($departmentIds = $idea->departments->pluck('id')->toArray())
						@endif
						@foreach ($departments as $department)
							<div class="spacer"></div>
							<div class="row d-flex h-100 justify-content-center">
								<div class="col-6 align-self-center">{{ $department->title }}</div>
								<div class="col-6 align-self-center text-right">
									<label for="department_id-{{ $department->id }}" class="switch form-check-label"> 
										<input type="checkbox" name="department_ids[]" id="department_id-{{ $department->id }}" value="{{ $department->id }}" data-slug="{{ $department->slug }}" tabindex="8" aria-describedby="helpBlockDepartmentIds" {{ in_array($department->id, $departmentIds) ? 'checked' : '' }}>
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
					<div class="form-group supporting-files">
						<label for="supporting_files" class="control-label font-weight-bold">Supporting Files</label>
						@if ($supportingFiles->count())
							<ul class="list-unstyled mt-1">
								@foreach ($supportingFiles as $supportingFile)
									<li><i class="icon fa fa-file" aria-hidden="true"></i><a href="javascript:void(0);" title="{{ $supportingFile->name }}" class="text-info" rel="nofollow" data-toggle="modal" data-target=".supporting-file-{{ $supportingFile->id }}-modal-lg">{{ $supportingFile->name }}</a> (Click to manage)</li>
								@endforeach
							</ul>
						@endif
						<input type="file" name="supporting_files[]" id="supporting_files" class="form-control" value="{{ old('supporting_files') }}" tabindex="9" autocomplete="off" aria-describedby="helpBlockSupportingFiles" multiple>
						@if ($errors->has('supporting_files'))
							<span id="helpBlockSupportingFiles" class="form-control-feedback form-text text-danger">- {{ $errors->first('supporting_files') }}</span>
						@endif
						<span id="helpBlockSupportingFiles" class="form-control-feedback form-text text-muted">- Please click on the browse button to select supporting files.</span>
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
													<p class="pt-3"><a href="/cp/ideas/{{ $idea->id }}/supporting-files/{{ $supportingFile->id }}/thumbnail/delete" title="Delete Thumbnail" class="btn btn-sm btn-outline-danger">Delete Thumbnail</a></li>
												@else
													<a href="{{ $tempUrl }}" title="{{ $supportingFile->name }}" target="_blank" class="text-dark"><img src="{{ $tempUrl }}" style="max-width: 100%;" class="align-top text-center" alt="{{ $supportingFile->name }}"></a>
													<p><a href="/cp/ideas/{{ $idea->id }}/supporting-files/{{ $supportingFile->id }}/thumbnail/add" title="Add Thumbnail" class="btn btn-sm btn-outline-secondary">Add Thumbnail</a></li>
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
													<p class="pt-3"><a href="/cp/ideas/{{ $idea->id }}/supporting-files/{{ $supportingFile->id }}/thumbnail/delete" title="Delete Thumbnail" class="btn btn-sm btn-outline-danger">Delete Thumbnail</a></li>
												@else
													<p><a href="/cp/ideas/{{ $idea->id }}/supporting-files/{{ $supportingFile->id }}/thumbnail/add" title="Add Thumbnail" class="btn btn-sm btn-outline-secondary">Add Thumbnail</a></li>
												@endif
											@else
												@if (!empty($supportingFile->custom_properties['thumbnail']))
													@php ($tempThumbnailUrl = $supportingFile->custom_properties['thumbnail']['url'])
													<a href="{{ $tempUrl }}" title="{{ $supportingFile->custom_properties['thumbnail']['name'] }}" class="text-dark"><img src="{{ $tempThumbnailUrl }}" style="max-width: 100%;" class="align-top text-center" alt="{{ $supportingFile->custom_properties['thumbnail']['name'] }}"></a>
													<p class="pt-3"><a href="/cp/ideas/{{ $idea->id }}/supporting-files/{{ $supportingFile->id }}/thumbnail/delete" title="Delete Thumbnail" class="btn btn-sm btn-outline-danger">Delete Thumbnail</a></li>
												@else
													<p>&nbsp;</p>
													<p><a href="{{ $tempUrl }}" title="{{ $supportingFile->name }}" target="_blank" class="text-dark"><i class="fa fa-file fa-5x align-middle" aria-hidden="true"></i><br><br>No Preview Available</a></p>
													<p><a href="/cp/ideas/{{ $idea->id }}/supporting-files/{{ $supportingFile->id }}/thumbnail/add" title="Add Thumbnail" class="btn btn-sm btn-outline-secondary">Add Thumbnail</a></li>
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
												<li class="list-inline-item pull-right"><a href="/cp/ideas/{{ $idea->id }}/supporting-files/{{ $supportingFile->id }}/delete/" title="Delete Supporting File" class="btn btn-outline-danger">Delete</a></li>
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
