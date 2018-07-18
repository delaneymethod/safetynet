				@if (session('status'))
					<div class="row">
						<div class="col-12 text-center text-sm-center text-md-left text-lg-left text-xl-left">
							<p id="message" class="message {{ session('status_level') }}">{{ session('status') }}<a href="javascript:void(0);" title="Hide this message" rel="nofollow" class="pull-right" id="hideMessage"><i class="fa fa-times" aria-hidden="true"></i></a></p>
						</div><!-- /.col -->
					</div><!-- /.row -->
				@endif
				