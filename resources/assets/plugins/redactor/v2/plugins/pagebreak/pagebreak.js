(function($)
{
	$.Redactor.prototype.pagebreak = function()
	{
		return {
			langs: {
				en: {
					'insert-page-break': 'Insert Page Break',
				}
			},
			init: function() 
			{
				const button = this.button.add('pagebreak', this.lang.get('insert-page-break'));
							
				this.button.addCallback(button, this.pagebreak.insertPageBreak);
			
				//this.button.setIcon(button, '<i class="icon"></i>');
				this.button.setIcon(button, '<i class="icon-pagebreak"></i>');
			},
			insertPageBreak: function() 
			{
				const pagebreakNode = $('<p style="page-break-after: always;" class="redactor_pagebreak" unselectable="on" contenteditable="false">&nbsp;</p>');
				
				let currentNode = $(this.selection.current());

				if (currentNode.length && $.contains(this.$editor.get(0), currentNode.get(0))) {
					while (currentNode.parent().length && !currentNode.parent().is('div.redactor-layer')) {
						currentNode = currentNode.parent();
					}

					pagebreakNode.insertAfter(currentNode);
				} else {
					pagebreakNode.appendTo(this.$editor);
				}

				this.$editor.focus();

				this.code.sync();
			}
		};
	};
})(jQuery);
