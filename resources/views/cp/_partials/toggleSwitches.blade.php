	<script>
	function toggleSwitches() {
		window.CMS.ControlPanel.toggleSwitches();
	}

	if (window.attachEvent) {
		window.attachEvent('onload', toggleSwitches);
	} else if (window.addEventListener) {
		window.addEventListener('load', toggleSwitches, false);
	} else {
		document.addEventListener('load', toggleSwitches, false);
	}
	</script>
	