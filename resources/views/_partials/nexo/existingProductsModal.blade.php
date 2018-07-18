			<div class="modal fade" id="{{ $modalId }}Modal" tabindex="-1" role="dialog" aria-labelledby="{{ $modalId }}ModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-md modal-lg modal-xl" role="document">
					<div class="modal-content">
						<div class="modal-header border-0 d-block text-white text-uppercase text-center text-sm-center text-md-center text-lg-left text-xl-left pb-0 mb-0">
							<h5 class="font-interface-bold pb-0 mb-0" id="{{ $modalId }}ModalLabel">{{ $category->title }}</h5>
						</div>
						<div class="row mt-3 ml-0 mr-0 mb-3">
							<div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 mb-3 mb-sm-3 mb-md-3 mb-lg-0 mb-xl-0">
								<img src="{{ $category->image->url }}" alt="{{ $category->title }}" class="img-fluid">
							</div>
							<div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
								<form name="{{ $modalId }}" id="{{ $modalId }}" class="{{ $modalId }} existing-products" role="form" method="POST" action="/{{ $department->slug }}/form-submission" enctype="multipart/form-data">
									{{ csrf_field() }}
									<input type="hidden" name="form" value="Existing Products">
									<input type="hidden" name="page" value="{{ implode(' / ', $breadcrumbs) }}" >
									@if (!empty($product->ex_feedback_recipient))
										<input type="hidden" name="ex_feedback_recipient" value="{{ $product->ex_feedback_recipient }}">
									@else
										<input type="hidden" name="ex_feedback_recipient" value="communications@survitecgroup.com">
									@endif
									<div class="form-group row mt-3 ml-0 mr-0 mb-3">
										<label for="product" class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 col-form-label text-white text-uppercase">Product <span class="text-danger">&#42;</span></label>
										<div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
											<select name="product" id="product" class="form-control" tabindex="1" aria-describedby="helpBlockProduct" required autofocus>
												<option value="">Select a Product</option>
												@foreach ($products as $product)
													<option value="{{ $product->title }}" data-minimum_number_of_units="{{ $product->minimum_number_of_units }}" {{ (old('product') == $product->title) ? 'selected' : '' }}>{{ $product->title }}</option>
												@endforeach
											</select>
											@if ($errors->has('product'))
												<span id="helpBlockProduct" class="form-control-feedback form-text text-danger">- {{ $errors->first('product') }}</span>
											@endif
										</div>
									</div>
									<div class="form-group row mt-3 ml-0 mr-0 mb-3 d-none" id="{{ $modalId }}Model">
										<label for="model" class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 col-form-label text-white text-uppercase">Model <span class="text-danger">&#42;</span></label>
										<div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
											<select name="model" id="model" class="form-control" tabindex="2" aria-describedby="helpBlockModel"></select>
											@if ($errors->has('model'))
												<span id="helpBlockModel" class="form-control-feedback form-text text-danger">- {{ $errors->first('model') }}</span>
											@endif
										</div>
									</div>
									@php ($actions = ['Feedback', 'Request a Modification'])
									<div class="form-group row mt-3 ml-0 mr-0 mb-3 d-none" id="{{ $modalId }}Action">
										<label for="action" class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 col-form-label text-white text-uppercase">Action <span class="text-danger">&#42;</span></label>
										<div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
											<select name="action" id="action" class="form-control" tabindex="3" aria-describedby="helpBlockAction">
												<option value="">Select an Action</option>
												@foreach ($actions as $action)
													<option value="{{ $action }}" {{ (old('action') == $action) ? 'selected' : '' }}>{{ $action }}</option>
												@endforeach
											</select>
											@if ($errors->has('action'))
												<span id="helpBlockAction" class="form-control-feedback form-text text-danger">- {{ $errors->first('action') }}</span>
											@endif
										</div>
									</div>
									<div class="form-group row mt-3 ml-0 mr-0 mb-3 d-none" id="{{ $modalId }}MinimumNumberOfUnits">
										<label for="minimum_number_of_units" class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 col-form-label text-white text-uppercase pr-0 mr-0">Minimum Number of Units</label>
										<div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
											<input type="number" name="minimum_number_of_units" id="minimum_number_of_units" class="form-control" min="0" max="1000" placeholder="" tabindex="4" autocomplete="off" aria-describedby="helpBlockMinimumNumberOfUnits">
											@if ($errors->has('minimum_number_of_units'))
												<span id="helpBlockMinimumNumberOfUnits" class="form-control-feedback form-text text-danger">- {{ $errors->first('minimum_number_of_units') }}</span>
											@endif
											<span id="helpBlockMinimumNumberOfUnits" class="form-control-feedback form-text text-white {{ $modalId }}HelpBlockMinimumNumberOfUnitsAlt"></span>
										</div>
									</div>
									<div class="form-group row mt-3 ml-0 mr-0 mb-3">
										<label for="comments" class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 col-form-label text-white text-uppercase">Comments <span class="text-danger">&#42;</span></label>
										<div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
											<textarea name="comments" id="comments" class="form-control" placeholder="" tabindex="5" rows="5" aria-describedby="helpBlockComments" required>{{ old('comments') }}</textarea>
											@if ($errors->has('comments'))
												<span id="helpBlockComments" class="form-control-feedback form-text text-danger">- {{ $errors->first('comments') }}</span>
											@endif
										</div>
									</div>
									<div class="form-group row mt-3 ml-0 mr-0 mb-3">
										<label for="supporting_files" class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 col-form-label text-white text-uppercase pr-0 mr-0">Supporting Files</label>
										<div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
											<input type="file" name="supporting_files" id="supporting_files" class="form-control" placeholder="" tabindex="6" autocomplete="off" aria-describedby="helpBlockSupportingFiles" multiple>
											@if ($errors->has('supporting_files'))
												<span id="helpBlockSupportingFiles" class="form-control-feedback form-text text-danger">- {{ $errors->first('supporting_files') }}</span>
											@endif
										</div>
									</div>
									<div class="form-group row mt-3 ml-0 mr-0 mb-3">
										<div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4"></div>
										<div class="col-6 col-sm-6 col-md-6 col-lg-4 col-xl-4 text-left">
											<button type="submit" name="submit_{{ $modalClass }}" id="submit_{{ $modalClass }}" class="btn btn-safetynet-orange text-uppercase" tabindex="7" title="Submit">Submit</button>
										</div>
										<div class="col-6 col-sm-6 col-md-6 col-lg-4 col-xl-4 text-right">
											<button type="button" name="cancel_{{ $modalClass }}" id="cancel_{{ $modalClass }}" class="btn btn-secondary text-uppercase" tabindex="8" title="Cancel" data-dismiss="modal">Cancel</button>
										</div>
									</div>
								</form>
								@php ($modalReference = studly_case($modalId))
								@php ($productReference = camel_case($modalReference))
								<script>
								function load{{ $modalReference }}ActionListener() {
									/* Create a new array to store all our product model titles. We use this 2d array later to toggle the models field. */
									let {{ $productReference }}Products = [];
									
									@foreach ($products as $product)
										{{ $productReference }}Products['{{ $product->title }}'] = [];
										
										@if ($product->models->count() > 0)
											@foreach ($product->models as $model)
												{{ $productReference }}Products['{{ $product->title }}'].push({
													'title': '{{ $model->title }}',
													'minimum_number_of_units': {{ $model->minimum_number_of_units }}
												});
											@endforeach
										@endif
									@endforeach
									
									window.CMS.Templates.existingProductsModal('{{ $modalId }}', {{ $productReference }}Products);
								}
							
								if (window.attachEvent) {
									window.attachEvent('onload', load{{ $modalReference }}ActionListener);
								} else if (window.addEventListener) {
									window.addEventListener('load', load{{ $modalReference }}ActionListener, false);
								} else {
									document.addEventListener('load', load{{ $modalReference }}ActionListener, false);
								}
								</script>
							</div>
						</div>
					</div>
				</div>
			</div>
