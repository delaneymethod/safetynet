			<div class="{{ $classes }} pl-0 pr-0 bg-safetynet-33 sidebar">
				<div class="row d-flex h-100 align-items-end justify-content-end">
					<div class="col-12 align-self-start">				
						@include('_partials.feeds')
					</div>						
					@if ($showSidebarFooterTitle)
						@include('_partials.sidebarFooterTitle', [
							'classes' => 'p-0 m-0'
						])
					@endif
				</div>
			</div>
				