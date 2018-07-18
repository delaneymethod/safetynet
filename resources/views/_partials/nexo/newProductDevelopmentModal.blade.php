			<div class="modal fade" id="{{ $modalId }}Modal" tabindex="-1" role="dialog" aria-labelledby="{{ $modalId }}ModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-md modal-lg modal-xl" role="document">
					<div class="modal-content">
						<div class="modal-header border-0 d-block text-white text-uppercase text-center text-sm-center text-md-center text-lg-left text-xl-left pb-0 mb-0">
							<h5 class="font-interface-bold pb-0 mb-0" id="{{ $modalId }}ModalLabel">{{ $product->title }}@if (!empty($product->due_date))&nbsp;-&nbsp;<span class="text-safetynet-orange">Due {{ $product->due_date }}</span>@endif</h5>
						</div>
						<div class="row mt-3 ml-0 mr-0 mb-3">
							<div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 mb-3 mb-sm-3 mb-md-3 mb-lg-0 mb-xl-0">
								<ul class="nav nav-tabs mr-0 pr-0 border-0 col-12" id="npd-tabs" role="tablist">
									@if (!empty($product->video))
										<li class="nav-item ml-0 mr-0 pl-0 pr-0 col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6"><a href="javascript:void(0);" class="nav-link font-interface-bold border-0 text-uppercase text-white text-center bg-transparent rounded-0" id="video-tab" data-toggle="tab" data-target="#video" role="tab" aria-controls="video" aria-selected="true">Video</a></li>
									@endif
									<li class="nav-item ml-0 mr-0 pl-0 pr-0 col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6"><a href="javascript:void(0);" class="nav-link font-interface-bold border-0 text-uppercase text-white text-center bg-transparent rounded-0" id="image-tab" data-toggle="tab" data-target="#image" role="tab" aria-controls="image" aria-selected="false">Image</a></li>
								</ul>
								<div class="tab-content" id="npd-tabContent">
									@if (!empty($product->video))
										<div class="tab-pane fade" id="video" role="tabpanel" aria-labelledby="video-tab">
											@if (str_contains($product->video, 'youtube'))
												<div class="mt-3" data-type="youtube" data-video-id="{{ $product->video }}"></div>
											@elseif (str_contains($product->video, 'vimeo'))
												<div class="mt-3" data-type="vimeo" data-video-id="{{ $product->video }}"></div>
											@endif
										</div>
									@endif
									<div class="tab-pane fade pt-3" id="image" role="tabpanel" aria-labelledby="image-tab">
										<img src="{{ $product->image->url }}" alt="{{ $product->title }}" class="img-fluid">
									</div>
								</div>
							</div>
							<div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
								<form name="{{ $modalId }}" id="{{ $modalId }}" class="{{ $modalId }}" role="form" method="POST" action="/{{ $department->slug }}/form-submission" enctype="multipart/form-data">
									{{ csrf_field() }}
									<input type="hidden" name="form" value="New Product Development">
									<input type="hidden" name="page" value="{{ implode(' / ', $breadcrumbs) }}" >
									@if (!empty($product->npd_feedback_recipient))
										<input type="hidden" name="npd_feedback_recipient" value="{{ $product->npd_feedback_recipient }}">
									@else
										<input type="hidden" name="npd_feedback_recipient" value="communications@survitecgroup.com">
									@endif
									@if (!empty($product->overview))
										<div class="form-group row mt-3 ml-0 mr-0 mb-3">
											<label class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-4 col-form-label text-white text-uppercase">Overview</label>
											<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-8">
												@php ($paragraphs = explode('<br />', nl2br($product->overview)))
												@foreach ($paragraphs as $paragraph)
													<p class="col-form-label text-white">{{ $paragraph }}</p>
												@endforeach
											</div>
										</div>
									@endif
									<div class="form-group row mt-3 ml-0 mr-0 mb-3">
										<label for="comments" class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 col-form-label text-white text-uppercase">Comments <span class="text-danger">&#42;</span></label>
										<div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
											<textarea name="comments" id="comments" class="form-control" placeholder="" tabindex="1" rows="5" aria-describedby="helpBlockComments" required>{{ old('comments') }}</textarea>
											@if ($errors->has('comments'))
												<span id="helpBlockComments" class="form-control-feedback form-text text-danger">- {{ $errors->first('comments') }}</span>
											@endif
										</div>
									</div>
									<div class="form-group row mt-3 ml-0 mr-0 mb-3">
										<div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4"></div>
										<div class="col-6 col-sm-6 col-md-6 col-lg-4 col-xl-4 text-left">
											<button type="submit" name="submit_{{ $modalClass }}" id="submit_{{ $modalClass }}" class="btn btn-safetynet-orange text-uppercase" tabindex="2" title="Submit">Submit</button>
										</div>
										<div class="col-6 col-sm-6 col-md-6 col-lg-4 col-xl-4 text-right">
											<button type="button" name="cancel_{{ $modalClass }}" id="cancel_{{ $modalClass }}" class="btn btn-secondary text-uppercase" tabindex="3" title="Cancel" data-dismiss="modal">Cancel</button>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
