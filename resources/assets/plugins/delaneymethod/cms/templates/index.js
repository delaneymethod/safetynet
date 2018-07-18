/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */
 
 ;($ => {
	$.delaneyMethodCMSTemplates = options => {
		/* Support multiple elements */
		if (this.length > 1){
			this.each(() => { 
				$(this).delaneyMethodCMSTemplates(options);
			});
			
			return this;
		}

		this.name = 'DelaneyMethod CMS - Templates -';
		
		this.version = '1.0.0';
		
		this.settings = {};

		this.defaults = {};
		
		this.adjustHeight = () => {
			const breakpoint = window.CMS.Library.getBreakpoint();
			
			/* Added extra 8 pixels to account for padding */
			const navigationHeight = $('.navigation').height() + 8;
			
			$('.banner').css({'min-height' : navigationHeight + 'px'});
			
			/* Added extra 8 pixels to account for padding */
			const bannerHeight = $('.banner').height() + 8;
		
			/* Added extra 20 pixels to account for padding */
			const breadcrumbHeight = $('.breadcrumb').height() + 20;
			
			if (breakpoint == 'md' || breakpoint == 'lg') {
				$('.sidebar').css({'min-height': 'calc(100vh - ' + bannerHeight + 'px)'});
			} else {
				$('.sidebar').css({'min-height': '100%'});
			}
			
			$('.sector-grid').css({'min-height': 'calc(100vh - ' + (bannerHeight + breadcrumbHeight) + 'px)'});
			
			/* Used to dynmaically set the height of the remaining feeds content */
			const gridHeight = $('.grid').height();
			
			const alignSelfEndHeight = $('.sidebar').find('.align-self-end').height();
			
			const alignSelfStartHeight = gridHeight - alignSelfEndHeight;
			
			$('.sidebar').find('.align-self-start').css({'min-height' : alignSelfStartHeight + 'px'});
		
			// const strategicHeight = $('.sidebar').find('.align-self-start').find('.strategic').height();
		
			const feedsHeight = alignSelfStartHeight; // - strategicHeight;
		
			$('.sidebar').find('.align-self-start').find('.feeds').css({'min-height' : feedsHeight + 'px'});
		};
		
		this.setMinimumNumberOfUnits = (modalId, minimumNumberOfUnits) => {
			/* Update the field with the minimum number of units */
			if ($('.' + modalId + 'HelpBlockMinimumNumberOfUnitsAlt').is(':visible')) {
				$('#' + modalId + ' #minimum_number_of_units').attr('min', minimumNumberOfUnits).val(minimumNumberOfUnits);
			
				$('.' + modalId + 'HelpBlockMinimumNumberOfUnitsAlt').html('- This value cannot be less than ' + minimumNumberOfUnits);
			}
		};
		
		this.existingProductsModal = (modalId, products) => {
			let minimumNumberOfUnits = 0;
	
			/* When a product is selected, we need to load the model field is there are models available. (Note: not all products have models) */
			$('#' + modalId + ' #product').on('change', event => {
				event.preventDefault();
				
				/* Grab selected product */
				const product = $(event.target).val();
				
				/* Make sure user has not selected the Select a Product option which is blank */
				if (product.length > 0) {
					/* Check if the selected product has models */
					if (products[product].length > 0) {
						/* Make sure we are working with a clean model field */
						$('#' + modalId + ' #model').empty().append($('<option>', {
							'value': '',
							'html': 'Select a Model'
						}));

						/* Loop over all the models for the selected product, building a new options list and show the models field */
						$(products[product]).each(function(index, model) { 
							$('#' + modalId + ' #model').append($('<option>', {
								'html': model['title'],
								'value': model['title'],
								'data-minimum_number_of_units': model['minimum_number_of_units']
							}));
						});
						
						/* Show the model field */
						$('#' + modalId + 'Model').removeClass('d-none');
					} else {
						$('#' + modalId + 'Model').addClass('d-none');
					
						/* Selected product has no models so lets hide the field again, if its not already hidden */
						$('#' + modalId + ' #model').empty();
					}
					
					/* Grab the selected product option minimum number of units value */
					minimumNumberOfUnits = $('#' + modalId + ' #product option:selected').data('minimum_number_of_units');
					
					this.setMinimumNumberOfUnits(modalId, minimumNumberOfUnits);
					
					/* Now that we have handled the Model field, show the Action field */
					$('#' + modalId + 'Action').removeClass('d-none');
					
					$('#' + modalId + ' #action').val('');
					
					$('#' + modalId + 'MinimumNumberOfUnits').addClass('d-none');
					
					$('#' + modalId + ' #minimum_number_of_units').val(0);
				} else {
					/* No product has been selected so hide the Model, Action and Minimum fields */
					$('#' + modalId + 'Model').addClass('d-none');
					
					$('#' + modalId + 'Action').addClass('d-none');
					
					$('#' + modalId + 'MinimumNumberOfUnits').addClass('d-none');
					
					$('#' + modalId + ' #model').empty();
					
					$('#' + modalId + ' #action').val('');
					
					$('#' + modalId + ' #minimum_number_of_units').val(0);
				}
			});
			
			/* When a model is selected, we need to load update the minimum number of units field */
			$('#' + modalId + ' #model').on('change', event => {
				event.preventDefault();
				
				/* Grab selected model */
				const modal = $(event.target).val();
				
				/* Make sure user has not selected the Select a Model option which is blank */
				if (modal.length > 0) {
					/* Grab the selected model and its minimum number of units. (Note: A model will overwride the product) */
					minimumNumberOfUnits = $('#' + modalId + ' #model option:selected').data('minimum_number_of_units');
				
					/* If the model has a limit, it overrides */
					this.setMinimumNumberOfUnits(modalId, minimumNumberOfUnits);
				} else {
					minimumNumberOfUnits = 0;
					
					this.setMinimumNumberOfUnits(modalId, minimumNumberOfUnits);
				}
			});
			
			$('#' + modalId + ' #action').on('change', event => {
				event.preventDefault();
				
				/* Grab the selected action and toggle the minimum field */
				const action = $(event.target).val();
				
				/* Make sure user has not selected the Select an Action option which is blank */
				if (action.length > 0) {
					if (action == 'Request a Modification') {
						$('#' + modalId + 'MinimumNumberOfUnits').removeClass('d-none');
						
						this.setMinimumNumberOfUnits(modalId, minimumNumberOfUnits);
					} else {
						this.setMinimumNumberOfUnits(modalId, 0);
						
						$('#' + modalId + 'MinimumNumberOfUnits').addClass('d-none');
					}
				} else {
					this.setMinimumNumberOfUnits(modalId, 0);
					
					$('#' + modalId + 'MinimumNumberOfUnits').addClass('d-none');
				}
			});
			
			$('#' + modalId).on('submit', event => {
				let errors = 0;
				
				$('#helpBlockModel, #helpBlockAction').remove();
				
				if ($('#' + modalId + ' #model').is(':visible') && !$('#' + modalId + ' #model').val()) {
					errors++;
					
					$('<span/>').attr({
						'id': 'helpBlockModel',
						'class': 'form-control-feedback form-text text-danger'
					}).html('- Please select a Model').insertAfter('#' + modalId + ' #model');
				}
				
				if (!$('#' + modalId + ' #action').val()) {
					errors++;
					
					$('<span/>').attr({
						'id': 'helpBlockAction',
						'class': 'form-control-feedback form-text text-danger'
					}).html('- Please select an Action').insertAfter('#' + modalId + ' #action');
				}
				
				if (errors > 0) {
					return false;
				}
				
				return true;
			});
		};	
		
		this.calendar = (element, options) => {
			if ($(element).length) {
				$(element).fullCalendar({
					'header': {
						'left': 'prev, next, today',
						'center': 'title',
						'right': 'month, agendaWeek, agendaDay'
					},
					'navLinks': true,
					'buttonText': {
						'today': 'Today',
						'month': 'Month',
						'week': 'Week',
						'day': 'Day',
						'list': 'List'
					},
					'eventRender': (event, elementEvent) => {
						$(elementEvent).attr('data-id', event.id);
						$(elementEvent).attr('data-sectors', event.sectors);
						$(elementEvent).attr('data-slug', event.slug);
						/*
						$(elementEvent).css({
							'backgroundColor': `${event.color} !important`,
							'borderColor': `${event.color} !important`
						});
						*/
					},
					'eventLimit': true,
					'nowIndicator': true,
					'timeFormat': 'HH:mmA',
					// 'events': options.events,
					'eventTextColor': '#FFFFFF',
					'firstDay': options.firstDay,
					'eventBorderColor': 'rgb(254, 80, 1)',
					'eventBackgroundColor': 'rgb(254, 80, 1)',
					'eventClick': (calEvent, jsEvent, view) => {
						$('#' + calEvent.id + 'Modal').modal('toggle');
						
						if (calEvent.url) {
							return false;
						}
					}
				});
				
				const segments = window.location.pathname.split( '/' );
				
				let sector = '';
				
				if (segments.length > 2 && segments[2] !== 'events') {
					sector = segments[2];
				}
				
				$(element).fullCalendar('removeEventSources');
					
				$(element).fullCalendar('addEventSource', '/events/filter/' + sector); 
			}
			
			if ($('.filter-events').length) {
				$('.filter-events').on('click', event => {
					let checkboxesChecked = [];
					
					const checkboxes = $('.filter-events');
					
					for (let i = 0; i < checkboxes.length; i++) {
						if (checkboxes[i].checked) {
							checkboxesChecked.push(checkboxes[i]);
						}
					}
					
					let sectors = [];
					
					if (checkboxesChecked.length > 0) {
						for (let x = 0; x < checkboxesChecked.length; x++) {
							sectors.push($(checkboxesChecked[x]).val());	
						}	
					}
					
					$(element).fullCalendar('removeEventSources');
					
					$(element).fullCalendar('addEventSource', '/events/filter/' + sectors.join(',')); 
				});
			}
		};	
		
		this.requestAnEvent = () => {
			$('#scopeOfAttendance').change(function() {
				if ($(this).val() === 'Partnership') {
					$('#partnershipNameToggle').removeClass('d-none').addClass('d-block');
				} else {
					$('#partnershipNameToggle').removeClass('d-block').addClass('d-none');
				}
			});
		};					
		
		this.init = () => {
			console.log(this.name + ' v' + this.version + ' is ready!');
			
			this.settings = $.extend({}, this.defaults, options);
			
			this.adjustHeight();
			
			this.requestAnEvent();
			
			$(window).resize(() => {
				this.adjustHeight();
			});
			
			return this;
		};
		
		return this.init();
	};
})(jQuery);

window.CMS.Templates = $.delaneyMethodCMSTemplates();
