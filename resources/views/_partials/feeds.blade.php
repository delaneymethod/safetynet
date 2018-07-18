							@if (!empty($yammer) || !empty($stream))
								<div class="row feeds" {!! (str_contains(request()->path(), 'events')) ?  'style="min-height:900px;"' : '' !!}>
									<div class="col-12">
										<nav>
											<div class="nav nav-tabs border-0" id="nav-tab" role="tablist">
												@if (!empty($yammer))
													<a href="javascript:void(0);" class="font-interface-bold border-0 text-uppercase text-muted text-center col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 rounded-0 nav-item nav-link" id="yammer-tab" data-toggle="tab" data-target="#yammer" role="tab" aria-controls="yammer" aria-selected="true">Yammer</a>
												@endif
												@if (!empty($stream))
													<a href="javascript:void(0);" class="font-interface-bold border-0 text-uppercase text-muted text-center col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 rounded-0 nav-item nav-link" id="stream-tab" data-toggle="tab" data-target="#stream" role="tab" aria-controls="stream" aria-selected="false">Stream</a>
												@endif	
											</div>
										</nav>
										<div class="p-3 tab-content border-0 bg-white h-100" id="nav-tabContent">
											@if (!empty($yammer))
												<div class="tab-pane fade" id="yammer" role="tabpanel" style="height: 100%;" aria-labelledby="yammer"></div>
											@endif
											@if (!empty($stream))
												<div class="tab-pane fade" id="stream" role="tabpanel" style="height: 100%;" aria-labelledby="stream"></div>
											@endif
										</div>
									</div>
								</div>
							@endif
