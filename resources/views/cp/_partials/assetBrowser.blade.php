	<script async>
	'use strict';
	
	function loadAssetsBrowser(id) {
		if ($('#' + id + '-browse-modal').length) {
			$('#' + id + '-browse-modal').on('show.bs.modal', event => {
				const button = $(event.relatedTarget);
				
				const fieldId = button.data('field_id');
				
				const value = button.data('value');
				
				window.CMS.ControlPanel.attachAssetBrowser('#' + id + '-container', fieldId, value);
			});
			
			$('#' + id + '-reset-field').on('click', () => {
				$('#' + id).val('').blur();
				
				$('a[data-target="#' + id + '-browse-modal"]').data('value', '');
			});
		}
	}
	
	if (window.attachEvent) {
		window.attachEvent('onload', () => {
			loadAssetsBrowser('image');
			loadAssetsBrowser('banner');
			loadAssetsBrowser('data');
		});
	} else if (window.addEventListener) {
		window.addEventListener('load', () => {
			loadAssetsBrowser('image');
			loadAssetsBrowser('banner');
			loadAssetsBrowser('data');
		}, false);
	} else {
		document.addEventListener('load', () => {
			loadAssetsBrowser('image');
			loadAssetsBrowser('banner');
			loadAssetsBrowser('data');
		}, false);
	}
	</script>
	