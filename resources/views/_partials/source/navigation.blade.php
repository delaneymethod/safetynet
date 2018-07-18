			<div class="{{ $classes }} bg-safetynet-33 navigation">
				<ul class="list-unstyled d-block d-sm-block d-md-none d-lg-block d-xl-block mt-3 text-uppercase">
					<li class="text-white"><a href="/" title="Home" class="d-inline-block"><i class="fa fa-fw fa-lg fa-home" aria-hidden="true"></i> Home</a> / <a href="/{{ $department->slug }}" title="Source" class="d-inline-block">Source Home</a> <a href="javascript:void(0);" title="Logout" class="pull-right" id="logout">Logout</a></li>
					@if (!empty($shopGiveawaysData) || !empty($shopBrochuresAndStationeryData))
						<li><a href="/{{ $department->slug }}/shop" title="Shop" class="d-block"><i class="fa fa-fw fa-lg fa-shopping-basket" aria-hidden="true"></i> Shop</a></li>
					@endif
					@if (!empty($sector))
						<li class="text-white"><a href="/{{ $department->slug }}/{{ $sector->slug }}/events" title="Events Calendar" class="d-inline-block"><i class="fa fa-fw fa-lg fa-calendar" aria-hidden="true"></i> Events Calendar</a> / <a href="/{{ $department->slug }}/request-an-event" class="pull-right" title="Request an Event">Request an Event</a></li>
					@else
						<li class="text-white"><a href="/{{ $department->slug }}/events" title="Events Calendar" class="d-inline-block"><i class="fa fa-fw fa-lg fa-calendar" aria-hidden="true"></i> Events Calendar</a> / <a href="/{{ $department->slug }}/request-an-event" class="pull-right" title="Request an Event">Request an Event</a></li>
					@endif	
					<li><a href="/{{ $department->slug }}/request-a-post" title="Request a Post" class="d-block"><i class="fa fa-fw fa-lg fa-pencil-square-o" aria-hidden="true"></i> Request a Post</a></li>
					@if (!empty($sector) && !empty($updateCategory))
						<li><a href="/{{ $department->slug }}/{{ $sector->slug }}/{{ $updateCategory->slug }}" title="{{ $updateCategory->title }}" class="d-block"><i class="fa fa-fw fa-lg fa-list" aria-hidden="true"></i> {{ $updateCategory->title }}</a></li>
					@endif
					<li>
						<a href="javascript:void(0);" class="d-block business-winning-link" role="button" data-toggle="collapse" data-target="#collapseBusinessWinning" aria-expanded="false" aria-controls="collapseBusinessWinning"><i class="fa fa-fw fa-lg fa-list" aria-hidden="true"></i> Business Winning <i class="fa fa-fw fa-plus" aria-hidden="true"></i></a>
						<ul id="collapseBusinessWinning" class="collapse mt-0 ml-1 pl-2 list-unstyled text-uppercase">
							@if (!empty($salesForce))
								<li class="border-0 pb-2"><a href="{{ $salesForce }}" title="Salesforce" class="d-block p-0"> Salesforce</a></li>
							@endif
							<li class="border-0 pb-2"><a href="/{{ $department->slug }}/bw-toolkits" title="BW Toolkits" class="d-block p-0">BW Toolkits</a></li>
						</ul>
					</li>
					<li>
						<a href="javascript:void(0);" class="d-block strategic-link" role="button" data-toggle="collapse" data-target="#collapseStrategic" aria-expanded="false" aria-controls="collapseStrategic"><i class="fa fa-fw fa-lg fa-list" aria-hidden="true"></i> Strategic <i class="fa fa-fw fa-plus" aria-hidden="true"></i></a>
						<ul id="collapseStrategic" class="collapse mt-0 ml-1 pl-2 list-unstyled text-uppercase">
							<li class="border-0 pb-2"><a href="javascript:void(0);" title="Submit an acquisition target" class="d-block p-0" data-toggle="modal" data-target="#acquisitionTargetModal">Submit an acquisition target</a></li>
							<li class="border-0 pb-2"><a href="javascript:void(0);" title="Submit a business case" class="d-block p-0" data-toggle="modal" data-target="#businessCaseModal">Submit a business case</a></li>
							@if (!empty($sector) && !empty($strategyCategory))
								<li class="border-0 pb-2"><a href="/{{ $department->slug }}/{{ $sector->slug }}/{{ $strategyCategory->slug }}" title="View our Strategy" class="d-block p-0">View our Strategy</a></li>
							@endif
						</ul>
					</li>
				</ul>
				<ul class="list-unstyled list-inline d-none d-sm-none d-md-block d-lg-none d-xl-none m-3 text-uppercase text-center">
					<li class="p-0 m-0 list-inline-item border-bottom-0"><a href="/" title="Home" class="d-block p-3"><i class="fa fa-lg fa-home d-block m-0 pb-2" aria-hidden="true"></i>Home</a></li>
					<li class="p-0 m-0 list-inline-item border-bottom-0"><a href="/{{ $department->slug }}" title="Source Home" class="d-block p-3"><i class="fa fa-lg fa-home d-block m-0 pb-2" aria-hidden="true"></i>SourceHome</a></li>
					@if (!empty($shopGiveawaysData) || !empty($shopBrochuresAndStationeryData))
						<li class="p-0 m-0 list-inline-item border-bottom-0"><a href="/{{ $department->slug }}/shop" title="Shop" class="d-block p-3"><i class="fa fa-lg fa-shopping-basket d-block m-0 pb-2" aria-hidden="true"></i>Shop</a></li>
					@endif
					@if (!empty($sector))
						<li class="p-0 m-0 list-inline-item border-bottom-0"><a href="/{{ $department->slug }}/{{ $sector->slug }}/events" title="Events Calendar" class="d-block p-3"><i class="fa fa-lg fa-calendar d-block m-0 pb-2" aria-hidden="true"></i>Events Calendar</a></li>
					@else
						<li class="p-0 m-0 list-inline-item border-bottom-0"><a href="/{{ $department->slug }}/events" title="Events Calendar" class="d-block p-3"><i class="fa fa-lg fa-calendar d-block m-0 pb-2" aria-hidden="true"></i>Events Calendar</a></li>
					@endif
					<li class="p-0 m-0 list-inline-item border-bottom-0"><a href="/{{ $department->slug }}/request-an-event" title="Request an Event" class="d-block p-3"><i class="fa fa-lg fa-pencil-square-o d-block m-0 pb-2" aria-hidden="true"></i>Request an Event</a></li>
					<li class="p-0 m-0 list-inline-item border-bottom-0"><a href="/{{ $department->slug }}/request-a-post" title="Request a Post" class="d-block p-3"><i class="fa fa-lg fa-pencil-square-o d-block m-0 pb-2" aria-hidden="true"></i>Request a Post</a></li>
					@if (!empty($sector) && !empty($updateCategory))
						<li class="p-0 m-0 list-inline-item border-bottom-0"><a href="/{{ $department->slug }}/{{ $sector->slug }}/{{ $updateCategory->slug }}" title="{{ $updateCategory->title }}" class="d-block p-3"><i class="fa fa-lg fa-list d-block m-0 pb-2" aria-hidden="true"></i> {{ $updateCategory->title }}</a></li>
					@endif
					<li class="p-0 m-0 list-inline-item border-bottom-0"><a href="javascript:void(0);" title="Logout" class="d-block p-3" id="logout"><i class="text-center icon fa fa-sign-out" aria-hidden="true"></i>Logout</a></li>
				</ul>		
				<ul class="list-unstyled list-inline d-none d-sm-none d-md-block d-lg-none d-xl-none m-3 text-uppercase text-center">
					@if (!empty($salesForce))
						<li class="p-0 m-0 list-inline-item border-bottom-0"><a href="{{ $salesForce }}" title="Salesforce" class="d-block p-3"><i class="fa fa-lg fa-cloud d-block m-0 pb-2" aria-hidden="true"></i>Salesforce</a></li>
					@endif
					<li class="p-0 m-0 list-inline-item border-bottom-0"><a href="/{{ $department->slug }}/bw-toolkits" title="BW Toolkits" class="d-block p-3"><i class="fa fa-lg fa-tasks d-block m-0 pb-2" aria-hidden="true"></i>BW Toolkits</a></li>
					<li class="p-0 m-0 list-inline-item border-bottom-0"><a href="javascript:void(0);" title="Submit an acquisition target" class="d-block p-3" data-toggle="modal" data-target="#acquisitionTargetModal"><i class="fa fa-lg fa-tasks d-block m-0 pb-2" aria-hidden="true"></i>Acquisition Target</a></li>
					<li class="p-0 m-0 list-inline-item border-bottom-0"><a href="javascript:void(0);" title="Submit a business case" class="d-block p-3" data-toggle="modal" data-target="#businessCaseModal"><i class="fa fa-lg fa-tasks d-block m-0 pb-2" aria-hidden="true"></i>Business Case</a></li>
					@if (!empty($sector) && !empty($strategyCategory))
						<li class="p-0 m-0 list-inline-item border-bottom-0"><a href="/{{ $department->slug }}/{{ $sector->slug }}/{{ $strategyCategory->slug }}" title="View our Strategy" class="d-block p-3"><i class="fa fa-lg fa-list d-block m-0 pb-2" aria-hidden="true"></i>View our Strategy</a></li>	
					@endif
				</ul>				
				@include('_partials.source.acquisitionTargetModal', [
					'breadcrumbs' => $breadcrumbs,
				])
				@include('_partials.source.businessCaseModal', [
					'breadcrumbs' => $breadcrumbs,
				])
				<form name="logoutUser" id="logoutUser" class="logoutUser" action="/logout" method="POST" style="display: none;">
					{{ csrf_field() }}
				</form>
			</div>
			