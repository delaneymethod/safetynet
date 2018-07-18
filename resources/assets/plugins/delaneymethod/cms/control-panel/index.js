/**
 * @link	  https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license	  https://www.delaneymethod.com/cms/license
 */

;($ => {
	$.delaneyMethodCMSControlPanel = options => {
		/* Support multiple elements */
		if (this.length > 1){
			this.each(() => { 
				$(this).delaneyMethodCMSControlPanel(options);
			});
			
			return this;
		}
		
		this.name = 'DelaneyMethod CMS - Control Panel -';
		
		this.version = '1.0.0';
		
		this.settings = {};

		this.defaults = {};
		
		this.axiosCall = null;
		
		this.loadAnimations = () => {
			$('.sidebar #submenu').on('click', event => {
				event.preventDefault();
				
				$(event.target).toggleClass('highlight');
				
				$(event.target).next('ul').slideToggle(500);
		
				$(event.target).find('span > i').toggleClass('fa-rotate', 'fast');
			});

			$('.content #pageActions').on('click', event => {
				event.preventDefault();
				
				$('.content #pageActions i').removeClass('fa-rotate');
			
				if (event.target === event.currentTarget) {
					$(event.target).find('i').toggleClass('fa-rotate', 'fast');
				} else {
					$(event.target).toggleClass('fa-rotate', 'fast');
				}
			});

			$('.content #submenu').on('hide.bs.dropdown', () => {
				$('.content #pageActions i').removeClass('fa-rotate', 'fast');
			});
		};
		
		this.detachRedactor = element => {
			$(element).unbind().removeData();
		};
		
		this.attachRedactor = element => {
			if ($(element).length) {
				const token = window.Laravel.csrfToken;
				
				let minHeight = 400;
				
				let buttons = ['html', 'format', 'bold', 'italic', 'deleted', 'lists', 'image', 'file', 'link', 'horizontalrule'];
				
				/* 
				let plugins = ['codemirror', 'inlinestyle', 'table', 'alignment', 'definedlinks', 'fullscreen', 'filemanager', 'imagemanager', 'video', 'fontcolor', 'properties', 'textexpander', 'pagebreak'];
				*/
					
				let plugins = ['inlinestyle', 'table', 'alignment', 'fullscreen', 'filemanager', 'imagemanager', 'video', 'fontcolor', 'properties', 'textexpander', 'specialchars'];
				
				if (element == '#excerpt') {
					minHeight = 100;
					
					buttons = ['format', 'bold', 'italic'];
					
					/* plugins = ['codemirror']; */
				}
				
				$(element).redactor({
					'source': true,
					'focus': false,
					'focusEnd': false,
					'animation': false,
					'lang': 'en',
					'direction': 'ltr',
					'spellcheck': true,
					'overrideStyles': true,
					'clickToEdit': false,
					'removeNewlines': true,
					'replaceTags': {
						'b': 'strong',
						'i': 'em',
						'strike': 'del'
					},
					'pastePlainText': false,
					'pasteImages': true,
					'pasteLinks': true,
					'linkTooltip': true,
					'linkNofollow': false,
					'linkSize': 100,
					'pasteLinkTarget': false,	
					'fontcolors': ['#000', '#333', '#555', '#777', '#999', '#aaa', '#bbb', '#ccc', '#ddd', '#eee', '#f4f4f4'],
					'pasteBlockTags': ['pre', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'table', 'tbody', 'thead', 'tfoot', 'th', 'tr', 'td', 'ul', 'ol', 'li', 'blockquote', 'p', 'figure', 'figcaption'],
					'pasteInlineTags': ['br', 'strong', 'ins', 'code', 'del', 'span', 'samp', 'kbd', 'sup', 'sub', 'mark', 'var', 'cite', 'small', 'b', 'u', 'em', 'i'],
					'fileUpload': '/cp/assets?_token=' + token + '&type=file',
					'fileManagerJson': '/cp/assets?format=json&false',
					'imageUpload': '/cp/assets?_token=' + token + '&type=image',
					'imageManagerJson': '/cp/assets?format=json&type=image&false',
					'imageResizable': true,
					'imagePosition': true,
					'structure': true,
					/*'definedLinks': '/cp/links?format=json',*/
					'tabAsSpaces': 4,
					'minHeight': minHeight,
					'buttons': buttons,
					'plugins': plugins,
					/*
					'codemirror': {
						'lineNumbers': true,
						'mode': 'htmlmixed',
						'indentUnit': 4
					},
					*/
					'textexpander': [
						['lorem', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'],
						['dm', 'DelaneyMethod']
					],
					'callbacks': {
						'imageUpload': (image, json) => {
							$(image).attr('id', json.id);
						},
						'imageUploadError': (json, xhr) => {
							alert(json.message);
						},
						'fileUpload': (link, json) => {
							$(link).attr('id', json.id);
						},
						'fileUploadError': json => {
							alert(json.message);
						}
					}
				});
			}
		};
		
		this.detachAssetBrowser = element => {
			$(element).unbind().removeData();
		};
		
		this.attachAssetBrowser = (element, id, value) => {
			var isFile = true;
			
			var isImage = false;
			
			if (!(/\.(gif|jpg|jpeg|tiff|png|doc|docx|txt|pdf|rtf|mp4|mp3|ppt|xls|pptx|xlsx|xml)$/i).test(value)) {
				isFile = false;
			}
			
			if ((/\.(gif|jpg|jpeg|tiff|png)$/i).test(value)) {
				isImage = true;
			}
			
			var fileTypes = window.CMS.Library.getFileTypes();
			
			if (value != '' && isFile) {
				$('#' + id + '-selected-asset').html('<strong>Asset</strong> ' + value + '<div class="spacer d-sm-block d-md-block d-lg-none d-xl-none"></div>');
				
				if (isImage) {
					$('#' + id + '-selected-asset-preview').html('<div class="spacer d-sm-block d-md-block d-lg-none d-xl-none"></div><img src="' + value + '" class="img-fluid" width="100%">');
				} else {
					$('#' + id + '-selected-asset-preview').html('<div class="spacer d-sm-block d-md-block d-lg-none d-xl-none"></div><i class="fa fa-file fa-5x align-middle" aria-hidden="true"></i><br /><br />No Preview Available');
				}
			} else {
				$('#' + id + '-selected-asset').html('');
			
				$('#' + id + '-selected-asset-preview').html('');
			}
			
			/* Loads assets into modal window body */
			$(element).browse({
				root: '/',
				script: '/cp/assets/browse',
			}, file => {
				isImage = false;
				
				if ((/\.(gif|jpg|jpeg|tiff|png)$/i).test(file.path)) {
					isImage = true;
				}
				
				extension = file.path.split('.');
			
				extension = extension[(extension.length) - 1];
				
				$('#' + id + '-selected-asset').html('<strong>Asset</strong> ' + file.rel + '<div class="spacer d-sm-block d-md-block d-lg-none d-xl-none"></div>');
				
				/* Show preview */
				if (isImage) {
					$('#' + id + '-selected-asset-preview').html('<div class="spacer d-sm-block d-md-block d-lg-none d-xl-none"></div><img src="' + file.url + '" class="img-fluid" width="100%">');
				} else {
					$('#' + id + '-selected-asset-preview').html('<div class="spacer d-sm-block d-md-block d-lg-none d-xl-none"></div><i class="fa ' + fileTypes[extension] + ' fa-5x align-middle" aria-hidden="true"></i><br /><br />No Preview Available');
				}
				
				/* Close modal when user clicks select button and set the file URL in the Banner image URL field on the form. */
				$('#' + id + '-select-asset').on('click', () => {
					$('#' + id + '-browse-modal').modal('hide');
					
					if (id == 'data') {
						$('input[name="asset"]').val(true);
					}
					
					/* Update the form field and remove focus */
					$('#' + id).val(file.path).blur();
					
					$('a[data-target="#' + id + '-browse-modal"]').data('value', file.path);
				});
			});
		};
		
		this.axiosGetRequest = () => {
			return url => {
				if (this.axiosCall) {
					this.axiosCall.cancel();
				}
				
				const CancelToken = window.axios.CancelToken;
				
				this.axiosCall = CancelToken.source();
				
				return window.axios.get(url, {
					'cancelToken': this.axiosCall.token
				})
				.then(response => response.data)
				.then(data => {
					if (data.error === true) {
						window.CMS.Library.showErrorModal(data);
					}
					
					return data;
				})
				.catch(thrown => {
					if (window.axios.isCancel(thrown)) {
						/*console.log('Duplicate request cancelled');*/
					} else {
						window.CMS.Library.showErrorModal(thrown);
					}
				});
			}
		};
		
		this.axiosPostRequest = () => {
			return (url, data) => {
				if (this.axiosCall) {
					this.axiosCall.cancel();
				}
				
				const CancelToken = window.axios.CancelToken;
				
				this.axiosCall = CancelToken.source();
				
				return window.axios.post(url, data, {
					'cancelToken': this.axiosCall.token
				})
				.then(response => response.data)
				.then(data => {
					if (data.error === true) {
						window.CMS.Library.showErrorModal(data);
					}
					
					return data;
				})
				.catch(thrown => {
					if (window.axios.isCancel(thrown)) {
						/*console.log('Duplicate request cancelled');*/
					} else {
						window.CMS.Library.showErrorModal(thrown);
					}
				});
			}
		};
		
		this.attachFocusPointSelector = element => {
			if ($(element).length) {
				const axiosPostRequest = this.axiosPostRequest();
				
				$('img.selector, img.target').on('click', function(event) {
					const id = $(this).data('id');
					
					const imageWidth = $(this).width();
					const imageHeight = $(this).height();
					
					let offsetX = event.pageX - $(this).offset().left;
					let offsetY = event.pageY - $(this).offset().top;
					
					let percentageX = (offsetX / imageWidth) * 100;
					let percentageY = (offsetY / imageHeight) * 100;
					
					const focusX = (offsetX / imageWidth - .5) * 2;
					const focusY = (offsetY / imageHeight - .5) * -2;
					
					percentageY = percentageY.toFixed(0);
					percentageX = percentageX.toFixed(0);
					
					$('.reticle').css({
						'top': percentageY + '%',
						'left': percentageX + '%'
					});
					
					axiosPostRequest('/cp/assets/focus-point', {
						'id': id,
						'percentageX': percentageX + '%',
						'percentageY': percentageY + '%',
						'focusX': focusX,
						'focusY': focusY,
					})
					.then(response => window.CMS.Library.showSuccessModal(response));
				});
			}
		};
		
		this.saveSortableOrder = (element, event, ui) => {
			let classes = $(event.target).attr('class').split(' ');
			
			classes = _.without(classes, 'ui-sortable');
			
			const controller = _.head(classes);
			
			const order = $(element).sortable('toArray');
			
			if (order.length > 0) {
				const axiosPostRequest = this.axiosPostRequest();
				
				axiosPostRequest('/cp/' + controller + '/sort', {
					'order': order
				});
			}
		};
		
		this.attachSortable = element => {
			$(element).sortable({
				'opacity': 1,
				'items': '> tr',
				'cursor': 'move',
				'helper': 'clone',
				'appendTo': 'parent',
				'helper': (event, ui) => {
					ui.children().each(function() {
						$(this).width($(this).width());
					});
					
					return ui;
				},
				'placeholder': 'ui-state-highlight',
				'update': (event, ui) => this.saveSortableOrder(element, event, ui)
			});
			
			$(element).disableSelection();
		};
		
		this.adjustSidebarHeight = () => {
			$('.sidebar').not('.mobile').css({'min-height':'100vh'});	
		};
		
		this.toggleSwitchOn = element => {
			if (element == '.product-switch.videos') {
				$('#video-content-type').removeClass('d-none').addClass('d-block');
			}
			
			if (element == '.category-switch.new-product-development') {
				$('#new-product-development-sector').removeClass('d-none').addClass('d-block');
			
				$('#video-content-type').removeClass('d-none').addClass('d-block');
			}
			
			if (element == '.category-switch.existing-products') {
				$('#existing-products-sector').removeClass('d-none').addClass('d-block');
			}
			
			$(element).css('opacity', 1);
				
			$(element + ' input').prop('disabled', false);
	
			$(element + ' .slider').css('cursor', 'pointer').removeClass('off');
		};
				
		this.toggleSwitchOff = element => {
			if (element == '.videos') {
				$('#video-content-type').removeClass('d-block').addClass('d-none');
			}
			
			if (element == '.new-product-development') {
				$('#new-product-development-sector').removeClass('d-block').addClass('d-none');
				
				$('#video-content-type').removeClass('d-block').addClass('d-none');
			}
			
			if (element == '.existing-products') {
				$('#existing-products-sector').removeClass('d-block').addClass('d-none');
			}
			
			if ($(element).css('opacity') == 1) {
				$(element).css('opacity', 0.5);
				
				$(element + ' input').prop({'disabled': true, 'checked': false});
			
				$(element + ' .slider').css('cursor', 'not-allowed').addClass('off');
			
				$(element + ' input').each((index, childElement) => this.toggleSwitchOff('.' + $(childElement).data('slug')));
			}
		};
		
		this.toggleSwitches = () => {
			$('input[name="department_ids[]"]:checked').each((index, element) => this.toggleSwitchOn('.sector-switch.' + $(element).data('slug')));
		
			$('input[name="sector_ids[]"]:checked').each((index, element) => this.toggleSwitchOn('.category-switch.' + $(element).data('slug')));
		
			$('input[name="category_ids[]"]:checked').each((index, element) => this.toggleSwitchOn('.content-type-switch.' + $(element).data('slug')));
			
			$('input[name="content_type_ids[]"]:checked').each((index, element) => this.toggleSwitchOn('.product-switch.' + $(element).data('slug')));
		};
		
		this.attachToggleSwitchListeners = () => {
			['department_ids', 'sector_ids', 'category_ids', 'content_type_ids'].forEach(element => {
				$('input[name="' + element + '[]"]').on('change', event => {
					event.stopImmediatePropagation();
					
					const slug = $(event.currentTarget).data('slug');
					
					/* Toggle an extra field to capture Video data for products. */
					if (slug == 'videos') {
						$('#video-content-type').toggleClass('d-none', 'd-block');
					}
					
					/* Toggle an extra fields to capture Content and Lead Times data for New Product Development products. */
					if (slug == 'new-product-development') {
						$('#new-product-development-sector').toggleClass('d-none', 'd-block');
						
						$('#video-content-type').removeClass('d-none').addClass('d-block');
					}
					
					/* Toggle an extra fields to capture Minimum Number of Units data for Existing Products products. */
					if (slug == 'existing-products') {
						$('#existing-products-sector').toggleClass('d-none', 'd-block');
					}
					
					if ($('.' + slug).length) {
						if (!$(event.currentTarget).is(':checked')) {
							this.toggleSwitchOff('.' + slug);
						} else {
							this.toggleSwitchOn('.' + slug);
						}
					} else {
						if (!$(event.currentTarget).is(':checked') && slug == 'new-product-development') {
							this.toggleSwitchOff('.new-product-development');
						} else if ($(event.currentTarget).is(':checked') && slug == 'new-product-development') {
							this.toggleSwitchOn('.new-product-development');
						}
						
						if (!$(event.currentTarget).is(':checked') && slug == 'existing-products') {
							this.toggleSwitchOff('.existing-products');
						} else if ($(event.currentTarget).is(':checked') && slug == 'existing-products') {
							this.toggleSwitchOn('.existing-products');
						}
					}
				});
			});
		};
		
		this.attachDataTable = element => {
			if ($(element).length) {
				$.extend(true, $.fn.dataTable.defaults, {
					'order': [],
					'deferRender': true,
					'retrieve': true,
					'oLanguage': {
						'sLengthMenu': '_MENU_',
						'sSearch': '<div class="input-group"><div class="input-group-prepend"><span class="input-group-text bg-white"><i class="fa fa-search"></i></span></div>',
						'sSearchPlaceholder': 'Search' + window.CMS.searchBySuggestions,
					},
					'dom': '<"row"<"col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 text-center text-sm-center text-md-left text-lg-left text-xl-left"l><"col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 text-center text-sm-center text-md-right text-lg-right text-xl-right"f>">t<"row"<"col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 text-center text-sm-center text-md-left text-lg-left text-xl-left"i><"col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 text-center text-sm-center text-md-right text-lg-right text-xl-right"p>">',
					'columnDefs': [{
						'targets': 'no-sort',
						'orderable': false,
					}],
					'pageLength': 25,
					'language': {
						'zeroRecords': 'Nothing found - sorry',
						'info': 'Showing page _PAGE_ of _PAGES_',
						'infoEmpty': 'No records available',
						'infoFiltered': '(filtered from _MAX_ total records)',
					},
				});
				
				const dt = $(element).dataTable();
				
				// dt.fnFilter('test string');
			}
		};
		
		this.addMoreDatesTimes = () => {
			$('.multi-field-wrapper').each(function() {
				const $wrapper = $('.multi-fields', this);
				
				$('.multi-field:first-child .remove-field', $wrapper).addClass('d-none');
				
				$('#add-more-dates-times', $(this)).click(function(e) {
					const htmlBlock = $('.multi-field:first-child', $wrapper).clone(true);
					
					htmlBlock.find('.remove-field').removeClass('d-none').addClass('d-block');
					
					htmlBlock.appendTo($wrapper).find('input').val('');
				});
			
				$('.multi-field .remove-field', $wrapper).click(function() {
					if ($('.multi-field', $wrapper).length > 1) {
						$(this).closest('.multi-field').remove();
					}
				});
			});
		};
		
		this.init = () => {
			console.info(this.name + ' v' + this.version + ' is ready!');
			
			this.settings = $.extend({}, this.defaults, options);
			
			this.addMoreDatesTimes();
			
			this.loadAnimations();
			
			this.attachToggleSwitchListeners();
			
			this.attachRedactor('.redactor');
			
			this.attachFocusPointSelector('.focuspoint-selector');
			
			this.attachDataTable('#datatable');
			
			this.attachSortable('#sortable');
			
			this.adjustSidebarHeight();
			
			$(window).resize(() => {
				this.adjustSidebarHeight();
			});
			
			window.CMS.Library.convertTitleToSlug('#title', '#handle', '_');
			
			window.CMS.Library.convertTitleToSlug('#handle', '#handle', '_');
			
			window.CMS.Library.convertTitleToSlug('#title', '#slug', '-');
			
			window.CMS.Library.convertTitleToSlug('#slug', '#slug', '-');
			
			window.CMS.Library.convertFolderToSlug('#createFolderAsset #folder');
			
			return this;
		};
		
		return this.init();
	};
})(jQuery);

window.CMS.ControlPanel = $.delaneyMethodCMSControlPanel();
