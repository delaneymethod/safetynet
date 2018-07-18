			@if (!empty($sector))
				<div class="col-12 m-0 p-3 border-top">
					@foreach ($sectors as $a_sector)
						<div class="custom-control custom-checkbox custom-control-inline">
							<input class="custom-control-input filter-events" type="checkbox" id="{{ $a_sector->slug }}" data-calendar-id="{{ $calendar->getId() }}" value="{{ $a_sector->slug }}" {{ ($sector->slug === $a_sector->slug) ? 'checked' : '' }}>
							<label class="custom-control-label" for="{{ $a_sector->slug }}" style="color: {{ $a_sector->colour }}">{{ strtoupper($a_sector->title) }}</label>
						</div>
					@endforeach
				</div>
			@else
				<div class="col-12 m-0 p-3 border-top">
					@foreach ($sectors as $a_sector)
						<div class="custom-control custom-checkbox custom-control-inline">
							<input class="custom-control-input filter-events" type="checkbox" id="{{ $a_sector->slug }}" data-calendar-id="{{ $calendar->getId() }}" value="{{ $a_sector->slug }}">
							<label class="custom-control-label" for="{{ $a_sector->slug }}" style="color: {{ $a_sector->colour }}">{{ strtoupper($a_sector->title) }}</label>
						</div>
					@endforeach
				</div>
			@endif
