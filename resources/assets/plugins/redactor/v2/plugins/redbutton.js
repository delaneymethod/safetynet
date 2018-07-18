(function($)
{
	$.Redactor.prototype.redbutton = function() 
	{
		return {
			init: function() 
			{
				const button = this.button.add('red-button', 'Red Button');
				
				this.button.addCallback(button, this.redbutton.toggleClasses);
				
				this.button.setIcon(button, '<i class="fa fa-registered" aria-hidden="true"></i>');
			},
			toggleClasses: function() 
			{
				this.inline.toggleClass('btn btn-danger');
			}
		};
	};
})(jQuery);
