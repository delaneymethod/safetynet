@if (session('status'))
	<div class="container-fluid">
		<div class="row">
			<div class="col-12 text-center">
				<p id="message" class="message {{ session('status_level') ?: 'success' }}">{{ session('status') }}<a href="javascript:void(0);" title="Hide this message" rel="nofollow" class="pull-right" id="hideMessage"><i class="fa fa-times" aria-hidden="true"></i></a></p>
			</div>
		</div>
	</div>
@endif
			