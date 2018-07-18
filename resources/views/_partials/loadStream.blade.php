	@if (!empty($stream))
		<script>
		function loadStream() {
			const stream = '{{ $stream }}';
			
			let iframe = $('<iframe/>');
			
			iframe.attr({
				'frameborder': 0,
				'height': '100%',
				'scrolling': 'yes',
				'id': 'stream-embed',
				'allowfullscreen': true,
				'src': 'https://web.microsoftstream.com/embed/channel/' + stream
			});
			
			iframe.css({
				'width': '100%',
				'height': '100%',
				'max-width': '100%'
			});
			
			$('#stream').append(iframe);
			
			$('#stream-tab').on('click', event => {
				event.preventDefault();
				
				/* Reload it to fix scrollable iframe issue */
				if ($('#stream-embed').length) {
					$('#stream-embed').get(0).src = $('#stream-embed').get(0).src;
				}
			});
		}
	
		if (window.attachEvent) {
			window.attachEvent('onload', loadStream);
		} else if (window.addEventListener) {
			window.addEventListener('load', loadStream, false);
		} else {
			document.addEventListener('load', loadStream, false);
		}
		</script>
	@endif
