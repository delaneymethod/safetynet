	@if (!config('app.debug'))
		<script async>
		'use strict';
	
		window.ga=function(){ga.q.push(arguments)};ga.q=[];ga.l=+new Date;
		ga('create','{{ $googleAnalytics }}','auto');ga('send','pageview')
		</script>
		<script async defer src="//www.google-analytics.com/analytics.js"></script>
	@endif
	