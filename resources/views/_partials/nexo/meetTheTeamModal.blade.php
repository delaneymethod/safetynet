			<div class="modal fade" id="{{ $modalId }}Modal" tabindex="-1" role="dialog" aria-labelledby="{{ $modalId }}ModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-md modal-lg modal-xl" role="document">
					<div class="modal-content">
						<div class="modal-header border-0 d-block text-white text-uppercase text-center text-sm-center text-md-center text-lg-left text-xl-left pb-0 mb-0">
							<h5 class="font-interface-bold pb-0 mb-0" id="{{ $modalId }}ModalLabel">{{ $teamMember->full_name }}&nbsp;-&nbsp;<span class="text-safetynet-orange">{{ $teamMember->job_title }}</span></h5>
						</div>
						<div class="row mt-3 ml-0 mr-0 mb-3">
							<div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 mb-3 mb-sm-3 mb-md-3 mb-lg-0 mb-xl-0">
								<img src="{{ $teamMember->image->url }}" alt="{{ $teamMember->full_name }}" class="img-fluid">
							</div>
							<div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8 text-white">
								@if (!empty($teamMember->bio))
									<p class="text-uppercase">Bio</p>
									@php ($paragraphs = explode('<br />', nl2br($teamMember->bio)))
									@foreach ($paragraphs as $paragraph)
										<p>{{ $paragraph }}</p>
									@endforeach
								@endif
								@if (!empty($teamMember->email))	
									<p class="text-uppercase">Email</p>
									<p><a href="mailto:{{ $teamMember->email }}" title="Team Member User" class="text-white">{{ $teamMember->email }}</a></p>
								@endif
								@if (!empty($teamMember->location_id))	
									<p class="text-uppercase">Location</p>
									<p>{{ $teamMember->location->title }}</a></p>
								@endif
							</div>
							<div class="col-12 mt-3">
								<button type="button" name="close_{{ $modalClass }}" id="close_{{ $modalClass }}" class="btn btn-secondary text-uppercase" tabindex="1" title="Close" data-dismiss="modal">Close</button>
							</div>		
						</div>
					</div>
				</div>
			</div>
