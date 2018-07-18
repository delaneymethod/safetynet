	@if (config('app.env') == 'local' || config('app.env') == 'staging')
		<div class="server-env">
			<p class="text-center text-uppercase text-dark font-weight-bold p-2 ml-2 mr-2 mb-0 mt-0"><i class="fa fa-server" aria-hidden="true"></i> Server: {{ title_case(config('app.env')) }}</p>
		</div>
	@endif

