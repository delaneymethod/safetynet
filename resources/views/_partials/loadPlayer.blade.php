	<script>
	function loadPlayer() {
		window.plyr.setup();
	}

	if (window.attachEvent) {
		window.attachEvent('onload', loadPlayer);
	} else if (window.addEventListener) {
		window.addEventListener('load', loadPlayer, false);
	} else {
		document.addEventListener('load', loadPlayer, false);
	}
	</script>
	