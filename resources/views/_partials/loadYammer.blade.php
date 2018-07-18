	@if (!empty($yammer))
		<script>
		function loadYammer() {
			const yammer = '{{ $yammer }}';
			
			let iframe = $('<iframe/>');
			
			iframe.attr({
				'frameborder': 0,
				'height': '100%',
				'scrolling': 'yes',
				'id': 'yammer-embed',
				'allowfullscreen': true,
				'src': 'https://www.yammer.com/embed-feed?container=%23yammer&amp;network=survitecgroup.onmicrosoft.com&feedType=group&feedId=' + yammer + '&config[use_sso]=true&config[header]=false&config[footer]=false&config[showOpenGraphPreview]=false&config[defaultGroupId]=' + yammer + '&config[hideNetworkName]=false&network_permalink=survitecgroup.onmicrosoft.com'
			});
			
			iframe.css({
				'width': '100%',
				'height': '100%',
				'max-width': '100%'
			});
			
			$('#yammer').append(iframe);
			
			$('#yammer-tab').on('click', event => {
				event.preventDefault();
				
				/* Reload it to fix scrollable iframe issue */
				if ($('#yammer-embed').length) {
					$('#yammer-embed').get(0).src = $('#yammer-embed').get(0).src;
				}
			});
		}
	
		if (window.attachEvent) {
			window.attachEvent('onload', loadYammer);
		} else if (window.addEventListener) {
			window.addEventListener('load', loadYammer, false);
		} else {
			document.addEventListener('load', loadYammer, false);
		}
		</script>
	@endif
