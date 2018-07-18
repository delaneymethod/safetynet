@extends('_layouts.cp')

@section('title', 'Edit Model - Models - '.config('app.name'))
@section('description', 'Edit Model - Models - '.config('app.name'))
@section('keywords', 'Edit, Model, Models, '.config('app.name'))

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
		@if ($currentUser->hasPermission('view_models'))
			<a href="/cp/models" title="Cancel" class="btn btn-link text-secondary" title="Cancel">Cancel</a>
		@endif
		<button type="submit" name="submit_edit_model" id="submit_edit_model" class="pull-right btn btn-primary" title="Save Changes"><i class="icon fa fa-check-circle" aria-hidden="true"></i>Save Changes</button>
	</div>
@endsection

@section('content')
		@include('cp._partials.sidebar')
		<div class="{{ $mainSmCols }} {{ $mainMdCols }} {{ $mainLgCols }} {{ $mainXlCols }} main">
			@include('cp._partials.message')
			@include('cp._partials.pageTitle')
			<div class="content padding bg-white">
				<form name="editModel" id="editModel" class="editModel" role="form" method="POST" action="/cp/models/{{ $model->id }}">
					{{ csrf_field() }}
					{{ method_field('PUT') }}
					<input type="hidden" name="id" value="{{ $model->id }}">
					@yield('formButtons')
					<div class="spacer" style="width: auto;margin-left: -15px;margin-right: -15px;"><hr></div>
					<div class="spacer"></div>
					<p><i class="fa fa-info-circle" aria-hidden="true"></i> Fields marked with <span class="text-danger">&#42;</span> are required.</p>
					<div class="spacer"></div>
					<div class="form-group">
						<label for="title" class="control-label font-weight-bold">Title <span class="text-danger">&#42;</span></label>
						<input type="text" name="title" id="title" class="form-control" value="{{ old('title', optional($model)->title) }}" placeholder="e.g Test Title" tabindex="1" autocomplete="off" aria-describedby="helpBlockTitle" required autofocus>
						@if ($errors->has('title'))
							<span id="helpBlockTitle" class="form-control-feedback form-text text-danger">- {{ $errors->first('title') }}</span>
						@endif
						<span id="helpBlockTitle" class="form-control-feedback form-text text-muted"></span>
					</div>
					<div class="spacer"></div>
					<div class="form-group">
						<label for="slug" class="control-label font-weight-bold">Slug <span class="text-danger">&#42;</span></label>
						<input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug', optional($model)->slug) }}" placeholder="e.g Test Slug" tabindex="2" autocomplete="off" aria-describedby="helpBlockSlug" required>
						@if ($errors->has('slug'))
							<span id="helpBlockSlug" class="form-control-feedback form-text text-danger">- {{ $errors->first('slug') }}</span>
						@endif
						<span id="helpBlockSlug" class="form-control-feedback form-text text-muted">- The slug is auto-generated based on the title but feel free to edit it.</span>
					</div>
					<div class="spacer"></div>
					<div class="form-group">
						<label for="minimum_number_of_units" class="control-label font-weight-bold">Minimum Number of Units <span class="text-danger">&#42;</span></label>
						<input type="number" name="minimum_number_of_units" id="minimum_number_of_units" class="form-control" value="{{ old('minimum_number_of_units', optional($model)->minimum_number_of_units) }}" placeholder="e.g 10" tabindex="3" autocomplete="off" aria-describedby="helpBlockMinimumNumberOfUnits" min="0" max="1000" required>
						@if ($errors->has('minimum_number_of_units'))
							<span id="helpBlockMinimumNumberOfUnits" class="form-control-feedback form-text text-danger">- {{ $errors->first('minimum_number_of_units') }}</span>
						@endif
						<span id="helpBlockMinimumNumberOfUnits" class="form-control-feedback form-text text-muted"></span>
					</div>
					<div class="spacer"></div>
					<div class="form-group">
						<label class="control-label font-weight-bold">Status</label>
						<div class="custom-controls-stacked">
							@foreach ($statuses as $status)
								<div class="custom-control custom-radio d-flex h-100 justify-content-start status_id-{{ $status->id }}">
									<input type="radio" name="status_id" id="status_id-{{ str_slug($status->title) }}" class="custom-control-input" value="{{ $status->id }}" tabindex="4" aria-describedby="helpBlockStatusId" {{ ($model->status_id == $status->id || old('status_id') == $status->id) ? 'checked' : '' }}>
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
						<label class="control-label font-weight-bold">Products <span class="text-danger">&#42;</span></label>
						@if (old('product_ids'))
							@php ($productIds = old('product_ids'))
						@else
							@php ($productIds = $model->products->pluck('id')->toArray())
						@endif
						@foreach ($products as $product)
							<div class="spacer"></div>
							<div class="row d-flex h-100 justify-content-center">
								<div class="col-6 align-self-center">{{ $product->title }}</div>
								<div class="col-6 align-self-center text-right">
									<label for="product_id-{{ $product->id }}" class="switch form-check-label"> 
										<input type="checkbox" name="product_ids[]" id="product_id-{{ $product->id }}" value="{{ $product->id }}" data-slug="{{ $product->slug }}" tabindex="5" aria-describedby="helpBlockProductIds" {{ in_array($product->id, $productIds) ? 'checked' : '' }}>
										<span class="slider round"></span>
									</label>
								</div>
							</div>
						@endforeach
						@if ($errors->has('product_ids'))
							<span id="helpBlockProductIds" class="form-control-feedback form-text text-danger">- {{ $errors->first('product_ids') }}</span>
						@endif
						<span id="helpBlockProductIds" class="form-control-feedback form-text text-muted">- Products are filtered by &quot;Existing Products&quot; Sector.</span>
					</div>
					<div class="spacer"></div>
					<div class="spacer" style="width: auto;margin-left: -15px;margin-right: -15px;margin-bottom: -30px;"><hr></div>
					@yield('formButtons')
				</form>
			</div>
			@include('cp._partials.footer')
		</div>
@endsection
