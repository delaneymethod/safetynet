			<div class="modal fade" id="businessCaseModal" tabindex="-1" role="dialog" aria-labelledby="businessCaseModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-md modal-lg modal-xl" role="document">
					<div class="modal-content">
						<div class="row mt-3 ml-0 mr-0 mb-3">
							<div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
								<h5 class="font-interface-bold text-center text-sm-center text-md-center text-lg-left text-xl-left text-white text-uppercase mb-3" id="businessCaseModalLabel">Submit a Business Case<br><small class="text-safetynet-orange">{{ implode(' / ', $breadcrumbs) }}</small></h5>
							</div>
							<div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
								<form name="businessCase" id="businessCase" class="businessCase" role="form" method="POST" action="/{{ $department->slug }}/form-submission" enctype="multipart/form-data">
									{{ csrf_field() }}
									<input type="hidden" name="form" value="Business Case">
									<input type="hidden" name="page" value="{{ implode(' / ', $breadcrumbs) }}" >
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
										<label for="overview" class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 col-form-label text-white text-uppercase">Overview <span class="text-danger">&#42;</span></label>
										<div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
											<textarea name="overview" id="overview" class="form-control" placeholder="" tabindex="2" rows="5" cols="50" aria-describedby="helpBlockOverview" required>{{ old('overview') }}</textarea>
											@if ($errors->has('overview'))
												<span id="helpBlockOverview" class="form-control-feedback form-text text-danger">- {{ $errors->first('overview') }}</span>
											@endif
										</div>
									</div>
									<div class="form-group row mt-3 ml-0 mr-0 mb-3">
										<label for="supporting_files" class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 col-form-label text-white text-uppercase pr-0 mr-0">Supporting Files</label>
										<div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
											<input type="file" name="supporting_files" id="supporting_files" class="form-control"  placeholder="" tabindex="3" autocomplete="off" aria-describedby="helpBlockSupportingFiles" multiple>
											@if ($errors->has('supporting_files'))
												<span id="helpBlockSupportingFiles" class="form-control-feedback form-text text-danger">- {{ $errors->first('supporting_files') }}</span>
											@endif
										</div>
									</div>
									<div class="form-group row mt-3 ml-0 mr-0 mb-3">
										<div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4"></div>
										<div class="col-6 col-sm-6 col-md-6 col-lg-4 col-xl-4 text-left">
											<button type="submit" name="submit_business_case" id="submit_business_case" class="btn btn-safetynet-orange text-uppercase" tabindex="4" title="Submit">Submit</button>
										</div>
										<div class="col-6 col-sm-6 col-md-6 col-lg-4 col-xl-4 text-right">
											<button type="button" name="cancel_business_case" id="cancel_business_case" class="btn btn-secondary text-uppercase" tabindex="5" title="Cancel" data-dismiss="modal">Cancel</button>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
