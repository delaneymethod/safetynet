				<div class="row">
					<div class="col-12 text-center text-sm-center text-md-left text-lg-left text-xl-left">
						<div class="content">
							<div class="row">
								<div class="col-12 col-sm-8 col-md-8 col-lg-8 col-xl-8 order-2 order-sm-1 order-md-1 order-lg-1 order-xl-1 text-center text-sm-left text-md-left text-lg-left text-xl-left">
									<h1 class="page-title">{{ $title }}&nbsp;<small class="text-muted d-block d-sm-block d-md-inline-block d-lg-inline-block d-xl-inline-block">{{ $subTitle }}</small></h1>
								</div>
								<div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4 order-1 order-sm-2 order-md-2 order-lg-2 order-xl-2 text-center text-sm-right text-md-right text-lg-right text-xl-right">
									<a href="/" title="View Site" target="_blank" class="btn btn-safetynet-orange">View Site</a>
									<div class="d-block d-sm-none d-md-none d-lg-none d-xl-none">&nbsp;</div>
								</div>
							</div>
							@if (!empty($leadParagraph))
								<div class="row">
									<div class="col-12">
										<p class="p-0 m-0 mb-1 lead text-center text-sm-left text-md-left text-lg-left text-xl-left">{!! $leadParagraph !!}</p>
									</div>
								</div>
							@endif
						</div>
					</div>
				</div>
				