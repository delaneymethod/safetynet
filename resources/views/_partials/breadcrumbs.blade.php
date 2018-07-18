			@php ($eventsUrl = [''])
			<div class="col-12 m-0 p-0">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb rounded-0 bg-white text-safetynet-33 text-uppercase mb-0">
						<li class="breadcrumb-item"><a href="/" title="Home">Home</a></li>
						@if (!empty($department))
							@php (array_push($eventsUrl, $department->slug))
							<li class="breadcrumb-item"><a href="/{{ $department->slug }}" title="{{ $department->title }}">{{ $department->title }}</a></li>
						@endif
						@if (!empty($sector))
							@php (array_push($eventsUrl, $sector->slug))
							<li class="breadcrumb-item"><a href="/{{ $department->slug }}/{{ $sector->slug }}" title="{{ $sector->title }}">{{ $sector->title }}</a></li>
						@endif
						@if (!empty($category))
							<li class="breadcrumb-item"><a href="/{{ $department->slug }}/{{ $sector->slug }}/{{ $category->slug }}" title="{{ $category->title }}">{{ $category->title }}</a></li>
						@endif
						@if (!empty($contentType))
							<li class="breadcrumb-item"><a href="/{{ $department->slug }}/{{ $sector->slug }}/{{ $category->slug }}/{{ $contentType->slug }}" title="{{ $contentType->title }}">{{ $contentType->title }}</a></li>
						@endif
						@if (!empty($product))
							@if (!empty($contentType))
								<li class="breadcrumb-item"><a href="/{{ $department->slug }}/{{ $sector->slug }}/{{ $category->slug }}/{{ $contentType->slug }}/{{ $product->slug }}" title="{{ $product->title }}">{{ $product->title }}</a></li>
							@else
								<li class="breadcrumb-item"><a href="/{{ $department->slug }}/{{ $sector->slug }}/{{ $category->slug }}/{{ $product->slug }}" title="{{ $product->title }}">{{ $product->title }}</a></li>
							@endif
						@endif
						@if (str_contains(request()->path(), 'shop'))
							<li class="breadcrumb-item"><a href="/{{ $department->slug }}/shop" title="Shop">Shop</a></li>
						@endif
						@if (str_contains(request()->path(), 'events'))
							@php (array_push($eventsUrl, 'events'))						
							<li class="breadcrumb-item"><a href="{{ implode('/', $eventsUrl) }}" title="Events Calendar">Events Calendar</a></li>
						@endif
						@if (str_contains(request()->path(), 'meet-the-team'))
							<li class="breadcrumb-item"><a href="/{{ $department->slug }}/meet-the-team" title="Meet The Team">Meet The Team</a></li>
						@endif
						@if (str_contains(request()->path(), 'ideas'))
							<li class="breadcrumb-item"><a href="/{{ $department->slug }}/ideas" title="Ideas">Ideas</a></li>
						@endif
						@if (str_contains(request()->path(), 'request-a-post'))
							<li class="breadcrumb-item"><a href="/{{ $department->slug }}/request-a-post" title="Request A Post">Request A Post</a></li>
						@endif
						@if (str_contains(request()->path(), 'request-an-event'))
							<li class="breadcrumb-item"><a href="/{{ $department->slug }}/request-an-event" title="Request an Event">Request an Event</a></li>
						@endif
					</ol>
				</nav>
			</div>
