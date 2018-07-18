	<script>
	'use strict';
	
	function loadCalendar() {
		window.CMS.Templates.calendar('#calendar-{{ $calendar->getId() }}', {!! $calendar->getOptionsJson() !!});
	}
	
	if (window.attachEvent) {
		window.attachEvent('onload', () => {
			loadCalendar();
		});
	} else if (window.addEventListener) {
		window.addEventListener('load', () => {
			loadCalendar();
		}, false);
	} else {
		document.addEventListener('load', () => {
			loadCalendar();
		}, false);
	}
	</script>
