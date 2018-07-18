			@php ($expert = $category->experts->first())
			<div class="modal fade" id="askAnExpertModal" tabindex="-1" role="dialog" aria-labelledby="askAnExpertModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-md modal-lg modal-xl" role="document">
					<div class="modal-content">
						<div class="modal-header border-0 d-block text-white text-uppercase text-center text-sm-center text-md-center text-lg-left text-xl-left pb-0 mb-0">
							<h5 class="font-interface-bold pb-0 mb-0" id="{{ $modalId }}ModalLabel">Ask An Expert&nbsp;-&nbsp;<span class="text-safetynet-orange">{{ $category->title }}</span></h5>
						</div>
						<div class="row mt-3 ml-0 mr-0 mb-3">
							<div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 mb-3 mb-sm-3 mb-md-3 mb-lg-0 mb-xl-0 expert">
								<img src="{{ $expert->image->url }}" alt="{{ $expert->full_name }}" class="img-fluid">
								<h4 class="font-interface-bold position-absolute text-left text-uppercase bg-white text-safetynet-33">{{ $expert->full_name }}</h4>
								<h5 class="font-interface position-absolute text-left text-uppercase bg-white text-safetynet-orange">{{ $expert->position }}</h5>
							</div>
							<div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">	
								<form name="askAnExpert" id="askAnExpert" class="askAnExpert" role="form" method="POST" action="/{{ $department->slug }}/form-submission">
									{{ csrf_field() }}
									<input type="hidden" name="expert_full_name" value="{{ $expert->full_name }}">
									<input type="hidden" name="expert_email" value="{{ $expert->email }}">
									<input type="hidden" name="form" value="Ask An Expert">
									<input type="hidden" name="page" value="{{ implode(' / ', $breadcrumbs) }}" >
									<input type="hidden" name="category" value="{{ $category->title }}">
									<div class="form-group row mt-3 ml-0 mr-0 mb-3">
										<label for="title" class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 col-form-label text-white text-uppercase">Title <span class="text-danger">&#42;</span></label>
										<div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
											<input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" placeholder="" tabindex="1" autocomplete="off" aria-describedby="helpBlockTitle" required autofocus>
											@if ($errors->has('title'))
												<span id="helpBlockTitle" class="form-control-feedback form-text text-danger">- {{ $errors->first('title') }}</span>
											@endif
										</div>
									</div>
									<div class="form-group row mt-3 ml-0 mr-0 mb-3">
										<label for="question" class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 col-form-label text-white text-uppercase">Question <span class="text-danger">&#42;</span></label>
										<div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
											<textarea name="question" id="question" class="form-control" placeholder="" tabindex="2" rows="10" cols="50" aria-describedby="helpBlockQuestion" required>{{ old('question') }}</textarea>
											@if ($errors->has('question'))
												<span id="helpBlockQuestion" class="form-control-feedback form-text text-danger">- {{ $errors->first('question') }}</span>
											@endif
										</div>
									</div>
									<div class="form-group row mt-3 ml-0 mr-0 mb-3">
										<div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4"></div>
										<div class="col-6 col-sm-6 col-md-6 col-lg-4 col-xl-4 text-left">
											<button type="submit" name="submit_ask_an_expert" id="submit_ask_an_expert" class="btn btn-safetynet-orange text-uppercase" tabindex="3" title="Submit">Submit</button>
										</div>
										<div class="col-6 col-sm-6 col-md-6 col-lg-4 col-xl-4 text-right">
											<button type="button" name="cancel_ask_an_expert" id="cancel_ask_an_expert" class="btn btn-secondary text-uppercase" tabindex="4" title="Cancel" data-dismiss="modal">Cancel</button>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
