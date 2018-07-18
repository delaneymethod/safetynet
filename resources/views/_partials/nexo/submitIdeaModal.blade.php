			<div class="modal fade show" id="submitIdeaModal" tabindex="-1" role="dialog" aria-labelledby="submitIdeaModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-md modal-lg modal-xl" role="document">
					<div class="modal-content">
						<div class="row mt-3 ml-0 mr-0 mb-3">
							<div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
								<h5 class="font-interface-bold text-center text-sm-center text-md-center text-lg-left text-xl-left text-white text-uppercase mb-3" id="submitIdeaModalLabel">Submit an Idea<br><small class="text-safetynet-orange">{{ implode(' / ', $breadcrumbs) }}</small></h5>
							</div>
							<div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
								<form name="submitIdea" id="submitIdea" class="submitIdea" role="form" method="POST" action="/cp/ideas" enctype="multipart/form-data">
									{{ csrf_field() }}
									<input type="hidden" name="department_ids[]" value="2">
									<input type="hidden" name="slug" value="empty">
									<input type="hidden" name="image" value="empty">
									<input type="hidden" name="new_idea" value="true">
									<input type="hidden" name="status_id" value="2">
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
											<textarea name="redactor" id="redactor" class="form-control" placeholder="" tabindex="2" rows="5" cols="50" aria-describedby="helpBlockOverview" required>{{ old('redactor') }}</textarea>
											@if ($errors->has('redactor'))
												<span id="helpBlockOverview" class="form-control-feedback form-text text-danger">- {{ $errors->first('redactor') }}</span>
											@endif
										</div>
									</div>
									<div class="form-group row mt-3 ml-0 mr-0 mb-3">
										<label for="submitted_by" class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 col-form-label text-white text-uppercase">Submitted By <span class="text-danger">&#42;</span></label>
										<div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
											<input type="text" name="submitted_by" id="submitted_by" class="form-control" value="{{ old('submitted_by') }}" placeholder="" tabindex="3" autocomplete="off" aria-describedby="helpBlockSubmittedBy" required>
											@if ($errors->has('submitted_by'))
												<span id="helpBlockSubmittedBy" class="form-control-feedback form-text text-danger">- {{ $errors->first('submitted_by') }}</span>
											@endif
										</div>
									</div>
									<div class="form-group row mt-3 ml-0 mr-0 mb-3">
										<label for="supporting_files" class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 col-form-label text-white text-uppercase pr-0 mr-0">Supporting Files</label>
										<div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
											<input type="file" name="supporting_files[]" id="supporting_files" class="form-control"  placeholder="" tabindex="4" autocomplete="off" aria-describedby="helpBlockSupportingFiles" multiple>
											@if ($errors->has('supporting_files'))
												<span id="helpBlockSupportingFiles" class="form-control-feedback form-text text-danger">- {{ $errors->first('supporting_files') }}</span>
											@endif
										</div>
									</div>
									<div class="form-group row mt-3 ml-0 mr-0 mb-3">
										<div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4"></div>
										<div class="col-6 col-sm-6 col-md-6 col-lg-4 col-xl-4 text-left">
											<button type="submit" name="submit_idea" id="submit_idea" class="btn btn-safetynet-orange text-uppercase" tabindex="5" title="Submit">Submit</button>
										</div>
										<div class="col-6 col-sm-6 col-md-6 col-lg-4 col-xl-4 text-right">
											<button type="button" name="cancel_idea" id="cancel_idea" class="btn btn-secondary text-uppercase" tabindex="6" title="Cancel" data-dismiss="modal">Cancel</button>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			