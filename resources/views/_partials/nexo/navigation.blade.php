					<div class="{{ $classes }} bg-safetynet-33 navigation">
						<ul class="list-unstyled d-block d-sm-block d-md-none d-lg-block d-xl-block mt-3 text-uppercase">
							<li class="text-white"><a href="/" title="Home" class="d-inline-block"><i class="fa fa-fw fa-lg fa-home" aria-hidden="true"></i> Home</a> <a href="javascript:void(0);" title="Logout" class="pull-right" id="logout">Logout</a></li>
							@if (!empty($sector))
								<li><a href="/{{ $department->slug }}/{{ $sector->slug }}/events" title="Events Calendar" class="d-inline-block"><i class="fa fa-fw fa-lg fa-calendar" aria-hidden="true"></i> Events Calendar</a> / <a href="/{{ $department->slug }}/request-an-event" class="pull-right" title="Request an Event">Request an Event</a></li>
							@else
								<li><a href="/{{ $department->slug }}/events" title="Events Calendar" class="d-inline-block"><i class="fa fa-fw fa-lg fa-calendar" aria-hidden="true"></i> Events Calendar</a> / <a href="/{{ $department->slug }}/request-an-event" class="pull-right" title="Request an Event">Request an Event</a></li>
							@endif
							<li><a href="/{{ $department->slug }}/ideas" title="Ideas" class="d-block"><i class="fa fa-fw fa-lg fa-list" aria-hidden="true"></i> Ideas</a></li>
							<li><a href="javascript:void(0);" title="Submit an Idea" class="d-block" data-toggle="modal" data-target="#submitIdeaModal"><i class="fa fa-fw fa-lg fa-lightbulb-o" aria-hidden="true"></i> Submit an Idea</a></li>
							<li><a href="/{{ $department->slug }}/meet-the-team" title="Meet the Team" class="d-block"><i class="fa fa-fw fa-lg fa-users" aria-hidden="true"></i> Meet the Team</a></li>
							@if (!empty($sector) && !empty($designerHubCategory) && $currentUser->hasPermission('view_designer_hub'))
								<li><a href="/{{ $department->slug }}/{{ $sector->slug }}/{{ $designerHubCategory->slug }}" title="{{ $designerHubCategory->title }}" class="d-block"><i class="fa fa-fw fa-lg fa-list" aria-hidden="true"></i> {{ $designerHubCategory->title }}</a></li>
							@endif
						</ul>
						<ul class="list-unstyled list-inline d-none d-sm-none d-md-block d-lg-none d-xl-none m-3 text-uppercase text-center">
							<li class="list-inline-item border-bottom-0"><a href="/" title="Home" class="d-inline-block p-3"><i class="fa fa-lg fa-home d-block pb-2" aria-hidden="true"></i>Home</a></li>
							@if (!empty($sector))
								<li class="list-inline-item border-bottom-0"><a href="/{{ $department->slug }}/{{ $sector->slug }}/events" title="Events Calendar" class="d-block p-3"><i class="fa fa-lg fa-bullseye d-block pb-2" aria-hidden="true"></i>Events Calendar</a></li>
							@else
								<li class="list-inline-item border-bottom-0"><a href="/{{ $department->slug }}/events" title="Events Calendar" class="d-block p-3"><i class="fa fa-lg fa-bullseye d-block pb-2" aria-hidden="true"></i>Events Calendar</a></li>
							@endif
							<li class="list-inline-item border-bottom-0"><a href="/{{ $department->slug }}/request-an-event" title="Request an Event" class="d-block p-3"><i class="fa fa-lg fa-list d-block pb-2" aria-hidden="true"></i>Request an Event</a></li>
							<li class="list-inline-item border-bottom-0"><a href="/{{ $department->slug }}/ideas" title="Ideas" class="d-block p-3"><i class="fa fa-lg fa-list d-block pb-2" aria-hidden="true"></i>Ideas</a></li>
							<li class="list-inline-item border-bottom-0"><a href="javascript:void(0);" title="Submit an Idea" class="d-block p-3" data-toggle="modal" data-target="#submitIdeaModal"><i class="fa fa-fw fa-lg fa-lightbulb-o pb-2" aria-hidden="true"></i>Submit an Idea</a></li>
							<li class="list-inline-item border-bottom-0"><a href="/{{ $department->slug }}/meet-the-team" title="Meet the Team" class="d-block p-3"><i class="fa fa-lg fa-users d-block pb-2" aria-hidden="true"></i>Meet the Team</a></li>
							@if (!empty($sector) && !empty($designerHubCategory) && $currentUser->hasPermission('view_designer_hub'))
								<li class="list-inline-item border-bottom-0"><a href="/{{ $department->slug }}/{{ $sector->slug }}/{{ $designerHubCategory->slug }}" title="{{ $designerHubCategory->title }}" class="d-block p-3"><i class="fa fa-fw fa-lg fa-list pb-2" aria-hidden="true"></i> {{ $designerHubCategory->title }}</a></li>
							@endif
							<li class="list-inline-item border-bottom-0"><a href="javascript:void(0);" title="Logout" class="d-block p-3" id="logout">Logout</a></li>
						</ul>
						<form name="logoutUser" id="logoutUser" class="logoutUser" action="/logout" method="POST" style="display: none;">
							{{ csrf_field() }}
						</form>
						@include('_partials.nexo.submitIdeaModal', [
							'breadcrumbs' => $breadcrumbs,
						])
					</div>
