/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

;($ => {
	$.delaneyMethodCMSLibrary = options => {
		/* Support multiple elements */
		if (this.length > 1){
			this.each(() => { 
				$(this).delaneyMethodCMSLibrary(options);
			});
			
			return this;
		}
		
		this.name = 'DelaneyMethod CMS - Library -';
		
		this.version = '1.0.0';
		
		this.settings = {};

		this.defaults = {};
		
		this.inputTypeForPassword = 'password';
		
		this.formData = {
			'firstName': '',
			'lastName': '',
			'email': '',
			'suggestedEmail': '',
		};
		
		this.logout = () => {
			if ($('#logout').length) {
				$('#logout').on('click', event => {
					event.preventDefault();
				
					$('#logoutUser').submit();
				});
			}
		};
		
		this.getBreakpoint = () => {
			const w = $(document).innerWidth();
			
			return (w < 768) ? 'xs' : ((w < 992) ? 'sm' : ((w < 1200) ? 'md' : 'lg'));
		};
		
		this.loadAnimations = () => {
			if ($('#message').length) {
				setTimeout(() => {
					$('.message.success').fadeOut('fast');
				}, 4000);
			}
			
			$('#nav-tab, #npd-tabs').find('a:first').addClass('active');
			
			$('#nav-tabContent, #npd-tabContent').find('.tab-pane:first').addClass('show active');
				
			$('.message #hideMessage').on('click', () => {
		 		$('.message').fadeOut('fast');
			});
		
			$('[data-toggle="tooltip"]').tooltip();
			
			$('#open-menu').on('click', () => {
		 		$('.sidebar').toggleClass('d-block d-sm-block d-md-none d-lg-none d-xl-none');
			});
			
			$('.strategic-link, .business-winning-link').on('click', event => {
				event.preventDefault();
				
				if (event.target === event.currentTarget) {
					$(event.target).find('i:last-child').toggleClass('fa-plus fa-minus', 'fast');
				} else {
					$(event.target).toggleClass('fa-plus fa-minus', 'fast');
				}
				
				setTimeout(function () {
					$('.focuspoint').focusPoint('adjustFocus');
				}, 500);
			});
			
			window.FastClick.attach(document.body);
		};
		
		this.loadListeners = () => {
			/* Checks the page for a form and email field so we can check the email address for typos and offers fixes/suggestions if any are found. */
			if ($('form').length && $('[name="email"]').length) {
				this.formData.firstName = $('[name="first_name"]');

				this.formData.lastName = $('[name="last_name"]');

				this.formData.email = $('[name="email"]');
				
				/* Listen for email address being typed */
				$('[name="email"]').on('blur', () => this.gatherFormDataAndCheck());
				
				/* If contact form, listen for submit button being clicked */
				if ($('[type="submit"]').length) {
					$('[type="submit"]').on('click', () => this.gatherFormDataAndCheck());
				}
				
				$('#did-you-mean a').on('click', () => {
					if (this.formData.suggestedEmail) {
						this.formData.email.val(this.formData.suggestedEmail);
					}
	
					this.formData.email.focus();
					
					this.gatherFormDataAndCheck();
				});
			}
			
			if ($('#editUser, #setPassword, #registerUser').length && $('[name="password"]').length) {
				$('#password').strengthify();
			
				$('#hide_show_password').on('click', event => {
					this.togglePasswordFieldType('#password', '#hide_show_password', this.inputTypeForPassword);
				});
				
				$('#hide_show_password_confirmation').on('click', event => {
					this.togglePasswordFieldType('#password_confirmation', '#hide_show_password_confirmation', this.inputTypeForPassword);
				});
			}
		};
		
		this.togglePasswordFieldType = (targetElement, triggerElement, type) => {
			if (type === 'password') {
				this.inputTypeForPassword = 'text';

				$(targetElement).attr({
					'type': 'text'
				});

				$(triggerElement).text('Hide Password');
			} else {
				this.inputTypeForPassword = 'password';

				$(targetElement).attr({
					'type': 'password'
				});

				$(triggerElement).text('Show Password');
			}	
		};
		
		this.gatherFormDataAndCheck = () => {
			this.formData.suggestedEmail = window.CMS.Library.checkForTypo({
				'firstName': this.formData.firstName.val(),
				'lastName': this.formData.lastName.val(),
				'email': this.formData.email.val(),
			});
			
			if (this.formData.suggestedEmail) {
				this.formData.suggestedEmail = this.formData.suggestedEmail.replace('@.', '@');
				
				$('#did-you-mean a').html(this.formData.suggestedEmail);
				
				$('#did-you-mean a').tabindex = 1;
				
				$('#did-you-mean').css('display', 'inline-block');
				
				$('#did-you-mean a').focus();
			} else {
				$('#did-you-mean').hide();
	
				$('#did-you-mean a').html('');
			}
		};
		
		this.checkForCloseMatch = (longString, shortString) => {
			/* Too many false positives with very short strings */
			if (shortString.length < 3) {
				return '';
			}
		
			/* Test if the shortString is in the string (so everything is fine) */
			if (_.includes(longString, shortString)) {
				return ''; 
			}
		
			/* Split the shortString string into two at each postion e.g. g|mail gm|ail gma|il gmai|l and test that each half exists with one gap */
			for (let i = 1; i < shortString.length; i++) {
				const firstPart = shortString.substring(0, i);
				
				const secondPart = shortString.substring(i);
				
				/* Test for wrong letter */
				const wrongLetterRegEx = new RegExp(`${firstPart}.${secondPart.substring(1)}`);
				
				if (wrongLetterRegEx.test(longString)) {
					let typo = longString.replace(wrongLetterRegEx, shortString);
					
					/* Try to be even cleverer and correct TLD typos after applying original fixes */
					typo = typo.replace('es', '.es');
					typo = typo.replace('..es', '.es');

					typo = typo.replace('fr', '.fr');
					typo = typo.replace('..fr', '.fr');

					typo = typo.replace('it', '.it');
					typo = typo.replace('..it', '.it');

					typo = typo.replace('de', '.de');
					typo = typo.replace('..de', '.de');

					typo = typo.replace('be', '.be');
					typo = typo.replace('..be', '.be');

					typo = typo.replace('nl', '.nl');
					typo = typo.replace('..nl', '.nl');

					typo = typo.replace('no', '.no');
					typo = typo.replace('..no', '.no');

					typo = typo.replace('ie', '.ie');
					typo = typo.replace('..ie', '.ie');
					
					typo = typo.replace('co.uk', '.co.uk');
					typo = typo.replace('..co.uk', '.co.uk');
					
					typo = typo.replace('com', '.com');
					typo = typo.replace('..com', '.com');
					
					typo = typo.replace('org', '.org');
					typo = typo.replace('..org', '.org');
					
					typo = typo.replace('net', '.net');
					typo = typo.replace('..net', '.net');
					
					typo = typo.replace('uk', '.uk');
					typo = typo.replace('..uk', '.uk');
					
					typo = typo.replace('eu', '.eu');
					typo = typo.replace('..eu', '.eu');
					
					typo = typo.replace('ch', '.ch');
					typo = typo.replace('..ch', '.ch');
					
					typo = typo.replace('au', '.au');
					typo = typo.replace('..au', '.au');
					
					typo = typo.replace('nz', '.nz');
					typo = typo.replace('..nz', '.nz');
					
					typo = typo.replace('gov', '.gov');
					typo = typo.replace('..gov', '.gov');
					
					typo = typo.replace('info', '.info');
					typo = typo.replace('..info', '.info');
					
					typo = typo.replace('biz', '.biz');
					typo = typo.replace('..biz', '.biz');
					
					typo = typo.replace('@.', '@');
					
					return typo;
				}

				/* Test for extra letter */
				const extraLetterRegEx = new RegExp(`${firstPart}.${secondPart}`);
				
				if (extraLetterRegEx.test(longString)) {
					return longString.replace(extraLetterRegEx, shortString);
				}

				/* Test for missing letter */
				if (secondPart !== 'mail') {
					const missingLetterRegEx = new RegExp(`${firstPart}{0}${secondPart}`);
					
					if (missingLetterRegEx.test(longString)) {
						return longString.replace(missingLetterRegEx, shortString);
					}
				}

				/* Test for switched letters */
				const switchedLetters = [
					shortString.substring(0, i - 1),
					shortString.charAt(i),
					shortString.charAt(i - 1),
					shortString.substring(i + 1),
				].join('');

				if (_.includes(longString, switchedLetters)) {
					return longString.replace(switchedLetters, shortString);
				}
			}

			/* If nothing was close, then there wasn't a typo */
			return '';
		};

		this.checkForDomainTypo = userEmail => {
			const domains = ['gmail', 'hotmail', 'outlook', 'yahoo', 'icloud', 'mail', 'zoho', 'btinternet', 'delaneymethod', 'glazedigital'];
			
			const [leftPart, rightPart] = userEmail.split('@');

			for (let i = 0; i < domains.length; i++) {
				const domain = domains[i];
			
				const result = this.checkForCloseMatch(rightPart, domain);
				
				if (result) {
					return `${leftPart}@${result}`;
				}
			}
			
			return '';
		};
			
		this.checkForNameTypo = (userEmail, name) => {
			const [leftPart, rightPart] = userEmail.split('@');
			
			const result = this.checkForCloseMatch(leftPart, name);
			
			if (result) {
				return `${result}@${rightPart}`;
			}
			
			return '';
		};

		this.checkForCommonTypos = userInput => {
			const commonTypos = [
				{
					pattern: /,com$/,
					fix: str => str.replace(/,com$/, '.com'),
				}, {
					pattern: /,co\.\w{2}$/,
					fix: str => str.replace(/,(co\.\w{2}$)/, '.$1'),
				}, {
					pattern: /@\w*$/,
					fix: str => str + '.com',
				},
			];
		
			typo = commonTypos.find(typo => typo.pattern.test(userInput));
			
			if (typo) {
				let correction = typo.fix(userInput);
				
				/* Try to be even cleverer and correct TLD typos after applying original fixes */
				correction = correction.replace('es.com', '.es');
				correction = correction.replace('fr.com', '.fr');
				correction = correction.replace('it.com', '.it');
				correction = correction.replace('de.com', '.de');
				correction = correction.replace('nl.com', '.nl');
				correction = correction.replace('be.com', '.be');
				correction = correction.replace('no.com', '.no');
				correction = correction.replace('ie.com', '.ie');
				correction = correction.replace('com.com', '.com');
				correction = correction.replace('couk.com', '.co.uk');
				correction = correction.replace('org.com', '.org');
				correction = correction.replace('net.com', '.net');
				correction = correction.replace('uk.com', '.uk');
				correction = correction.replace('eu.com', '.eu');
				correction = correction.replace('ch.com', '.ch');
				correction = correction.replace('au.com', '.au');
				correction = correction.replace('nz.com', '.nz');
				correction = correction.replace('gov.com', '.gov');
				correction = correction.replace('info.com', '.info');
				correction = correction.replace('biz.com', '.biz');
				correction = correction.replace('co.uk.co.uk', '.co.uk');
				correction = correction.replace('@.', '@');
				
				return correction;
			}
			
			return '';
		};
		
		this.checkForTypo = userInput => {
			let firstName = '';
			
			let lastName = '';
			
			if (userInput.firstName) {
				firstName = userInput.firstName.trim().toLowerCase();
			}
			
			if (userInput.lastName) {
				lastName = userInput.lastName.trim().toLowerCase();
			}
			
			const email = userInput.email.trim().toLowerCase();
			
			return this.checkForCommonTypos(email) || this.checkForDomainTypo(email) || this.checkForNameTypo(email, firstName) || this.checkForNameTypo(email, lastName);
		};
		
		this.getNthSuffix = date => {
			switch (date) {
				case 1:
				case 21:
				case 31:
					return 'st';
				
				case 2:
				case 22:
					return 'nd';
				
				case 3:
				case 23:
					return 'rd';
				
				default:
					return 'th';
			}
		};
		
		this.convertTitleToSlug = (element, targetElement, separator = '-') => {
			if ($(element).length) {
				$(element).on('keyup change', event => {
					let slug = $(event.target).val().toString()
						.toLowerCase()
						.trim()
						.replace(/\s+/g, separator) /* Replace spaces with separator */
						.replace(/&/g, separator + 'and' + separator) /* Replace & with 'and' */
						.replace(/[^\w\-]+/g, '') /* Remove all non-word chars */
						.replace(/\-\-+/g, separator) /* Replace multiple - with single separator */
						.replace(/\_\_+/g, separator); /* Replace multiple _ with single separator */
					
					$(targetElement).val(slug);
				});
			}
		};
		
		this.convertTitleToKeywords = (element, targetElement) => {
			if ($(element).length) {
				$(element).on('keyup change', event => {
					let keywords = $(event.target).val().toString()
						.trim()
						.split(' ')
						.join(', ')
						.trim();
					
					$(targetElement).val(keywords);
				});
			}
		};
		
		this.convertFolderToSlug = (element) => {
			if ($(element).length) {
				$(element).on('keyup change', event => {
					let slug = $(event.target).val().toString()
						.toLowerCase()
						.trim()
						.replace(/\s+/g, '--') /* Replace spaces with - */
						.replace(/&/g, '-and-') /* Replace & with 'and' */
						.replace(/[^\w\-]+/g, '') /* Remove all non-word chars */
						.replace(/\-\-+/g, '-'); /* Replace multiple - with single - */
					
					$(element).val(slug);
				});
			}
		};
		
		this.getMonthName = month => {
			let months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
		
			return months[month];
		};
		
		this.attachClipboard = () => {
			let clipboard = new Clipboard('[data-clipboard]');
			
			clipboard.on('success', event => {
				event.clearSelection();
			});
			
			clipboard.on('error', event => this.showErrorModal({
				'message': event.action + ' ' + event.trigger
			}));
		};
		
		this.attachFocusPoint = element => {
			$(element).focusPoint();	
		};
		
		this.adjustGridColumnHeights = () => {
			$('.same-height').height($('.same-height').width());	
		};
		
		this.babyComeBack = () => {
			let title = $('title').text();
			
			$(window).blur(function() {
				$('title').text('Baby come back... ' + title);
			}).focus(function() {
				$('title').text(title)
			});
		};
		
		this.attachFileBrowser = () => {
			const breakpoint = this.getBreakpoint();
			
			if (breakpoint == 'xs' || breakpoint == 'sm' || breakpoint == 'md') {
				/* Defaults to the devices core file select UI features */
			} else {
				$('#supporting_files, #files, #file').fileselect({
					browseBtnClass: 'btn btn-secondary fix-btn-border-radius',
				});
			
				$('.supporting-files input[type="text"]').addClass('custom-file-input-field');
			}
		};
		
		this.showSuccessModal = data => {
			if (data && data.message) {
				$('#successModal').modal('toggle');
			
				$('#successModal').on('shown.bs.modal', event => $(event.currentTarget).find('.modal-body').find('p').html(data.message));
			}
		};
		
		this.showErrorModal = error => {
			let message = '';
				
			if (error && error.message !== undefined) {
				/* Something happened in setting up the request that triggered an Error */
				console.error(error.message);
				
				message = error.message;
			} else if (error && error.response) {
				/* The request was made and the server responded with a status code that falls out of the range of 2xx */
				console.error(error.response.data);
				console.error(error.response.status);
				console.error(error.response.headers);
				
				message = error.response.data;
			} else if (error && error.request) {
				/* The request was made but no response was received `error.request` is an instance of XMLHttpRequest in the browser and an instance of http.ClientRequest in node.js */
				console.error(error.request);
				
				message = error.request;
			}
			
			$('#errorModal').modal('toggle');
			
			$('#errorModal').on('shown.bs.modal', event => $(event.currentTarget).find('.modal-body').find('p').html(message));
		};
		
		this.getFileTypes = () => {		
			var fileTypes = [];
			
			// Archives
			fileTypes['7z'] = 'fa-file-archive-o';
			fileTypes['bz'] = 'fa-file-archive-o';
			fileTypes['gz'] = 'fa-file-archive-o';
			fileTypes['rar'] = 'fa-file-archive-o';
			fileTypes['tar'] = 'fa-file-archive-o';
			fileTypes['zip'] = 'fa-file-archive-o';
			
			// Audio
			fileTypes['aac'] = 'fa-music';
			fileTypes['flac'] = 'fa-music';
			fileTypes['mid'] = 'fa-music';
			fileTypes['midi'] = 'fa-music';
			fileTypes['mp3'] = 'fa-music';
			fileTypes['ogg'] = 'fa-music';
			fileTypes['wma'] = 'fa-music';
			fileTypes['wav'] = 'fa-music';
			
			// Code
			fileTypes['c'] = 'fa-code';
			fileTypes['class'] = 'fa-code';
			fileTypes['cpp'] = 'fa-code';
			fileTypes['css'] = 'fa-code';
			fileTypes['erb'] = 'fa-code';
			fileTypes['htm'] = 'fa-code';
			fileTypes['html'] = 'fa-code';
			fileTypes['java'] = 'fa-code';
			fileTypes['js'] = 'fa-code';
			fileTypes['php'] = 'fa-code';
			fileTypes['pl'] = 'fa-code';
			fileTypes['py'] = 'fa-code';
			fileTypes['rb'] = 'fa-code';
			fileTypes['xhtml'] = 'fa-code';
			fileTypes['xml'] = 'fa-code';
			
			// Databases
			fileTypes['accdb'] = 'fa-hdd-o';
			fileTypes['db'] = 'fa-hdd-o';
			fileTypes['dbf'] = 'fa-hdd-o';
			fileTypes['mdb'] = 'fa-hdd-o';
			fileTypes['pdb'] = 'fa-hdd-o';
			fileTypes['sql'] = 'fa-hdd-o';
			
			// Documents
			fileTypes['csv'] = 'fa-file-text';
			fileTypes['doc'] = 'fa-file-text';
			fileTypes['docx'] = 'fa-file-text';
			fileTypes['odt'] = 'fa-file-text';
			fileTypes['pdf'] = 'fa-file-text';
			fileTypes['xls'] = 'fa-file-text';
			fileTypes['xlsx'] = 'fa-file-text';
			
			// Executables
			fileTypes['app'] = 'fa-list-alt';
			fileTypes['bat'] = 'fa-list-alt';
			fileTypes['com'] = 'fa-list-alt';
			fileTypes['exe'] = 'fa-list-alt';
			fileTypes['jar'] = 'fa-list-alt';
			fileTypes['msi'] = 'fa-list-alt';
			fileTypes['vb'] = 'fa-list-alt';
			
			// Fonts
			fileTypes['eot'] = 'fa-font';
			fileTypes['otf'] = 'fa-font';
			fileTypes['ttf'] = 'fa-font';
			fileTypes['woff'] = 'fa-font';
			
			// Game Files
			fileTypes['gam'] = 'fa-gamepad';
			fileTypes['nes'] = 'fa-gamepad';
			fileTypes['rom'] = 'fa-gamepad';
			fileTypes['sav'] = 'fa-floppy-o';
			
			// Images
			fileTypes['bmp'] = 'fa-picture-o';
			fileTypes['gif'] = 'fa-picture-o';
			fileTypes['jpg'] = 'fa-picture-o';
			fileTypes['jpeg'] = 'fa-picture-o';
			fileTypes['png'] = 'fa-picture-o';
			fileTypes['psd'] = 'fa-picture-o';
			fileTypes['tga'] = 'fa-picture-o';
			fileTypes['tif'] = 'fa-picture-o';
			
			// Package Files
			fileTypes['box'] = 'fa-archive';
			fileTypes['deb'] = 'fa-archive';
			fileTypes['rpm'] = 'fa-archive';
			
			// Scripts
			fileTypes['bat'] = 'fa-terminal';
			fileTypes['cmd'] = 'fa-terminal';
			fileTypes['sh'] = 'fa-terminal';
			
			// Text
			fileTypes['cfg'] = 'fa-file-text';
			fileTypes['ini'] = 'fa-file-text';
			fileTypes['log'] = 'fa-file-text';
			fileTypes['md'] = 'fa-file-text';
			fileTypes['rtf'] = 'fa-file-text';
			fileTypes['txt'] = 'fa-file-text';
			
			// Vector Images
			fileTypes['ai'] = 'fa-picture-o';
			fileTypes['drw'] = 'fa-picture-o';
			fileTypes['eps'] = 'fa-picture-o';
			fileTypes['ps'] = 'fa-picture-o';
			fileTypes['svg'] = 'fa-picture-o';
			
			// Video
			fileTypes['avi'] = 'fa-youtube-play';
			fileTypes['flv'] = 'fa-youtube-play';
			fileTypes['mkv'] = 'fa-youtube-play';
			fileTypes['mov'] = 'fa-youtube-play';
			fileTypes['mp4'] = 'fa-youtube-play';
			fileTypes['mpg'] = 'fa-youtube-play';
			fileTypes['ogv'] = 'fa-youtube-play';
			fileTypes['webm'] = 'fa-youtube-play';
			fileTypes['wmv'] = 'fa-youtube-play';
			fileTypes['swf'] = 'fa-youtube-play';
			
			// Other
			fileTypes['bak'] = 'fa-floppy';
			fileTypes['msg'] = 'fa-envelope';
		
			// Blank
			fileTypes['blank'] = 'fa-file';
				
			return fileTypes;	
		};
		
		this.init = () => {
			console.info(this.name + ' v' + this.version + ' is ready!');
			
			this.settings = $.extend({}, this.defaults, options);
			
			const token = document.head.querySelector('meta[name="csrf-token"]');

			if (token) {
			    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
			} else {
				this.showErrorModal({
					'message': 'CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token'
				});
			}
			
			/* IE10 viewport hack for Surface/desktop Windows 8 bug */
			if (navigator.userAgent.match(/IEMobile\/10\.0/)) {
				let msViewportStyle = document.createElement('style');
				
				msViewportStyle.appendChild(document.createTextNode('@-ms-viewport{width:auto!important}'));
				
				document.head.appendChild(msViewportStyle);
			}
			
			const nua = navigator.userAgent;
			
			const isAndroid = (nua.indexOf('Mozilla/5.0') > -1 && nua.indexOf('Android ') > -1 && nua.indexOf('AppleWebKit') > -1 && nua.indexOf('Chrome') === -1)
			
			if (isAndroid) {
				$('select.form-control').removeClass('form-control').css('width', '100%');
			}
			
			this.logout();
			
			this.loadAnimations();
			
			this.loadListeners();
			
			this.attachClipboard();
			
			this.attachFocusPoint('.focuspoint');
			
			this.attachFileBrowser();
			
			this.adjustGridColumnHeights();
			
			$(window).resize(() => {
				this.adjustGridColumnHeights();
			});
			
			return this;
		};
		
		return this.init();
	};
})(jQuery);
    
window.CMS.Library = $.delaneyMethodCMSLibrary();
