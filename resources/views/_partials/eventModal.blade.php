			<div class="modal fade" id="{{ $eventDateTime->id }}Modal" tabindex="-1" role="dialog" aria-labelledby="{{ $eventDateTime->id }}ModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-md modal-lg modal-xl" role="document">
					<div class="modal-content">
						<div class="modal-header border-0 d-block text-white text-uppercase text-center text-sm-center text-md-center text-lg-left text-xl-left pb-0 mb-0">
							<h5 class="font-interface-bold pb-0 mb-0" id="{{ $eventDateTime->id }}ModalLabel">{{ $event->title }}</h5>
						</div>
						<div class="modal-body row m-0 mt-3 p-0 d-flex justify-content-center" style="min-height: 300px;">
							<div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 mb-3 d-flex text-center justify-content-center">
								@if (!empty($event->image->focus_point))
									<div class="focuspoint align-self-center" data-focus-x="{{ $event->image->focus_point->focusX }}" data-focus-y="{{ $event->image->focus_point->focusY }}">
										<img src="{{ $event->image->url }}" alt="{{ $event->full_name }}" class="img-fluid">
									</div>
								@else
									<div class="align-self-center">
										<img src="{{ $event->image->url }}" alt="{{ $event->full_name }}" class="img-fluid">
									</div>
								@endif
							</div>
							<div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">	
								@if (!empty($event->description))
									<h6 class="text-safetynet-orange text-uppercase">Description</h6>
									@php ($paragraphs = explode('<br />', nl2br($event->description)))
									@foreach ($paragraphs as $paragraph)
										<p class="text-white">{{ $paragraph }}</p>
									@endforeach
								@endif
								<h6 class="text-safetynet-orange text-uppercase">Date</h6>
								@if ($event->all_day == 1)
									@if ($eventDateTime->start->format('Y-m-d H:m:s') === $eventDateTime->end->format('Y-m-d H:m:s'))
										<p class="text-white">{{ $eventDateTime->start->format('Y-m-d') }} (All Day Event)</p>
									@else
										<p class="text-white">{{ $eventDateTime->start->format('Y-m-d') }} to {{ $eventDateTime->end->format('Y-m-d') }} (All Day Events)</p>
									@endif
								@else
									<p class="text-white">{{ $eventDateTime->start->format('Y-m-d') }} to {{ $eventDateTime->end->format('Y-m-d') }}</p>
								@endif
								@if (!empty($event->options))
									@php ($options = json_decode($event->options, true))
									@if (!empty($options['url']))
										<h6 class="text-safetynet-orange text-uppercase">Link</h6>
										<p class="text-white"><a href="{{ $options['url'] }}" title="More information" class="text-white" target="_blank">{{ $options['url'] }}</a></p>
									@endif
								@endif
								@if (!empty($event->organiser_name))
									<h6 class="text-safetynet-orange text-uppercase">Organiser Name</h6>
									<p class="text-white">{{ $event->organiser_name }}</p>
								@endif
								@if (!empty($event->organiser_email))
									<h6 class="text-safetynet-orange text-uppercase">Organiser Email</h6>
									<p class="text-white"><a href="mailto:{{ $event->organiser_email }}" title="Email Organiser" class="text-white">{{ $event->organiser_email }}</a></p>
								@endif
								@if (!empty($event->organiser_contact_number))
									<h6 class="text-safetynet-orange text-uppercase">Organiser Contact Number</h6>
									<p class="text-white"><a href="tel:{{ $event->organiser_contact_number }}" title="Call Organiser" class="text-white">{{ $event->organiser_contact_number }}</a></p>
								@endif
							</div>
						</div>
						<div class="modal-footer border-0 text-right">
							<button type="button" name="cancel_{{ $eventDateTime->id }}" id="cancel_{{ $eventDateTime->id }}" class="btn btn-secondary text-uppercase" tabindex="" title="Close" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</div>
