		<div class="col-12 d-block d-sm-block d-md-block d-lg-none d-xl-none sidebar mobile">
			<div class="row no-gutters justify-content-center">
				<div class="col-4 align-self-center">
					<p><a href="javascript:void(0);" title="Open Menu" id="open-menu"><i class="fa fa-bars" aria-hidden="true"></i>Menu</a></p>
				</div>
				<div class="col-8 text-right align-self-center">
					<h3 class="align-middle"><span class="align-middle">{{ $currentUser->first_name }} {{ $currentUser->last_name }}</span>&nbsp;&nbsp;<img src="{{ $currentUser->gravatar }}" alt="{{ $currentUser->first_name }} {{ $currentUser->last_name }}" class="img-fluid rounded align-middle" style="margin-top: -1px;"></h3>
				</div>
			</div>
		</div>
		<div class="{{ $sidebarSmCols }} {{ $sidebarMdCols }} {{ $sidebarLgCols }} {{ $sidebarXlCols }} d-none d-sm-none d-md-none d-lg-block d-xl-block sidebar" style="min-height: 100vh;">
			<h3 class="d-none d-sm-none d-md-block d-lg-block d-xl-block align-middle"><img src="{{ $currentUser->gravatar }}" alt="{{ $currentUser->first_name }} {{ $currentUser->last_name }}" class="img-fluid rounded align-middle">&nbsp;&nbsp;<span class="align-middle">{{ $currentUser->first_name }} {{ $currentUser->last_name }}</span></h3>
			<h4 class="text-uppercase extra-breathing-space">Administration</h4>
			<ul class="list-unstyled">
				<li class="{{ setActive('cp/dashboard') }}"><a href="/cp/dashboard" title=""><i class="text-center icon fa fa-tachometer" aria-hidden="true"></i>Dashboard</a></li>
				@if ($currentUser->hasPermission('view_departments'))
					<li class="{{ setActive('cp/departments') }}"><a href="/cp/departments" title="Departments"><i class="text-center icon fa fa-list" aria-hidden="true"></i>Departments</a></li>
				@endif
				@if ($currentUser->hasPermission('view_sectors'))
					<li class="{{ setActive('cp/sectors') }}"><a href="/cp/sectors" title="Sectors"><i class="text-center icon fa fa-list" aria-hidden="true"></i>Sectors</a></li>
				@endif
				@if ($currentUser->hasPermission('view_categories'))
					<li class="{{ setActive('cp/categories') }}"><a href="/cp/categories" title="Categories"><i class="text-center icon fa fa-list" aria-hidden="true"></i>Categories</a></li>
				@endif
				@if ($currentUser->hasPermission('view_content_types'))
					<li class="{{ setActive('cp/content-types') }}"><a href="/cp/content-types" title="Content Types"><i class="text-center icon fa fa-list" aria-hidden="true"></i>Content Types</a></li>
				@endif
				@if ($currentUser->hasPermission('view_products'))
					<li class="{{ setActive('cp/products') }}"><a href="/cp/products" title="Products"><i class="text-center icon fa fa-list" aria-hidden="true"></i>Products</a></li>
				@endif
				@if ($currentUser->hasPermission('view_models'))
					<li class="{{ setActive('cp/models') }}"><a href="/cp/models" title="Models"><i class="text-center icon fa fa-list" aria-hidden="true"></i>Models</a></li>
				@endif
				@if ($currentUser->hasPermission('view_experts'))
					<li class="{{ setActive('cp/experts') }}"><a href="/cp/experts" title="Experts"><i class="text-center icon fa fa-list" aria-hidden="true"></i>Experts</a></li>
				@endif
				@if ($currentUser->hasPermission('view_ideas'))
					<li class="{{ setActive('cp/ideas') }}"><a href="/cp/ideas" title="Ideas"><i class="text-center icon fa fa-list" aria-hidden="true"></i>Ideas</a></li>
				@endif
				@if ($currentUser->hasPermission('view_locations'))
					<li class="{{ setActive('cp/locations') }}"><a href="/cp/locations" title="Locations"><i class="text-center icon fa fa-list" aria-hidden="true"></i>Locations</a></li>
				@endif
				@if ($currentUser->hasPermission('view_events'))
					<li class="{{ setActive('cp/events') }}"><a href="/cp/events" title="Events"><i class="text-center icon fa fa-calendar" aria-hidden="true"></i>Events</a></li>
				@endif
				@if ($currentUser->hasPermission('view_users'))
					<li class="{{ setActive('cp/users') }}"><a href="/cp/users" title="Users"><i class="text-center icon fa fa-users" aria-hidden="true"></i>Users</a></li>
				@endif
				@if ($currentUser->hasPermission('view_team_members'))
					<li class="{{ setActive('cp/team-members') }}"><a href="/cp/team-members" title="Team Members"><i class="text-center icon fa fa-users" aria-hidden="true"></i>Team Members</a></li>
				@endif
				@if ($currentUser->hasPermission('view_assets'))	
					<li class="{{ setActive('cp/assets') }}"><a href="/cp/assets" title="Assets"><i class="text-center icon fa fa-folder-open" aria-hidden="true"></i>Assets</a></li>
				@endif
				@if ($currentUser->hasPermission('view_globals'))
					<li class="{{ setActive('cp/globals') }}"><a href="/cp/globals" title="Globals"><i class="text-center icon fa fa-globe" aria-hidden="true"></i>Globals</a></li>
				@endif
				@if ($currentUser->hasPermission('view_roles') || $currentUser->hasPermission('view_permissions') || $currentUser->hasPermission('view_statuses'))
					<li>
						<a href="javascript:void(0);" title="Advanced" rel="nofollow" id="submenu" class="{{ setClass('cp/advanced', 'highlight') }}"><i class="text-center icon fa fa-cogs" aria-hidden="true"></i>Advanced<span class="pull-right"><i class="fa fa-angle-left {{ setClass('cp/advanced', 'fa-rotate') }}" aria-hidden="true"></i></span></a>
						<ul class="list-unstyled {{ setClass('cp/advanced', 'open') }}">
							@if ($currentUser->hasPermission('view_roles'))
								<li class="{{ setActive('cp/advanced/roles') }}"><a href="/cp/advanced/roles" title="Roles"><i class="text-center icon fa fa-circle-o" aria-hidden="true"></i>Roles</a></li>
							@endif	
							@if ($currentUser->hasPermission('view_permissions'))
								<li class="{{ setActive('cp/advanced/permissions') }}"><a href="/cp/advanced/permissions" title="Permissions"><i class="text-center icon fa fa-circle-o" aria-hidden="true"></i>Permissions</a></li>
							@endif	
							@if ($currentUser->hasPermission('view_statuses'))
								<li class="{{ setActive('cp/advanced/statuses') }}"><a href="/cp/advanced/statuses" title="Statuses"><i class="text-center icon fa fa-circle-o" aria-hidden="true"></i>Statuses</a></li>
							@endif	
						</ul>
					</li>
				@endif
			</ul>
			<h4 class="text-uppercase">User</h4>
			<ul class="list-unstyled">
				<li class="{{ setActive('cp/users/'.$currentUser->id.'/edit/password') }}"><a href="/cp/users/{{ $currentUser->id }}/edit/password" title="Change Password"><i class="text-center icon fa fa-key" aria-hidden="true"></i>Change Password</a></li>
				<li><a href="javascript:void(0);" title="Logout" id="logout"><i class="text-center icon fa fa-sign-out" aria-hidden="true"></i>Logout</a></li>
			</ul>
			<h4 class="text-uppercase white-label">Support</h4>
			<ul class="list-unstyled white-label">
				<li class="{{ setActive('cp/support') }}"><a href="/cp/support" title="Support"><i class="text-center icon fa fa-life-ring" aria-hidden="true"></i>Overview</a></li>
			</ul>
			<form name="logoutUser" id="logoutUser" class="logoutUser" action="/logout" method="POST" style="display: none;">
				{{ csrf_field() }}
			</form>
		</div>
		