			<div class="modal fade" id="{{ $modalId }}Modal" tabindex="-1" role="dialog" aria-labelledby="{{ $modalId }}ModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-md modal-lg modal-xl" role="document">
					<div class="modal-content">
						<div class="modal-header border-0 d-block text-white text-uppercase text-center text-sm-center text-md-center text-lg-left text-xl-left pb-0 mb-0">
							<h5 class="font-interface-bold pb-0 mb-0" id="{{ $modalId }}ModalLabel">{{ $idea->title }}&nbsp;-&nbsp;<span class="text-safetynet-orange">{{ $idea->submitted_by }}</span></h5>
						</div>
						<div class="row mt-3 ml-0 mr-0 mb-3">
							<div class="col-12 text-white">
								{!! $idea->description !!}
							</div>
							<div class="col-12 mt-3">
								<button type="button" name="close_{{ $modalClass }}" id="close_{{ $modalClass }}" class="btn btn-secondary text-uppercase" tabindex="1" title="Close" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</div>
			</div>
