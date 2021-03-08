/**
 * @license
 * @name jquery.otherdropdown
 * @description A small jQuery plugin to support a text area when selecting an 'Other' option in a dropdown
 * @version 1.2.2
 * @author Jonathan Stassen <jstassen.com>
 * @see https://github.com/TheBox193/jquery.otherdropdown
 */
;(function (factory) {
	if (typeof define === 'function' && define.amd) {
		// AMD (Register as an anonymous module)
		define(['jquery'], factory);
	} else if (typeof exports === 'object') {
		// Node/CommonJS
		module.exports = factory(require('jquery'));
	} else {
		// Browser globals
		factory(jQuery);
	}
}(function ($) {
	'use strict';
	$.fn.otherDropdown = function(opts) {
		var $this = this;
		// Allow a different name/value to trigger, default to 'other'
		var options = opts || {};
		options.placeholder = options.placeholder || options.value || '+';
		options.value = options.value || '+';
		options.name_lower = options.value.toLowerCase();
        options.max = Number(options.max) || 999;

		// Prepare text input
		var $textInput = $('<input type="number" onkeypress="return event.charCode >= 48" min="1" max="' + Number(options.max) + '" onkeyup="if(this.value > ' + Number(options.max) + ') this.value = ' + Number(options.max) + '" class="otherdropdown '+options.classes+'" placeholder="' + options.placeholder + '" />');

		// Bind to all change events to swap in the text area if 'other' option was chosen
		$this.change( function(ev){
			if (this.value.toLowerCase() === options.name_lower) {
				$this.hide().after( $textInput );
				$textInput.focus();
			}
		});


		// Bind to blur to swap back to select dropdown
		$textInput.change( function(ev) {
			var value = this.value;
			this.value = '';
			this.remove();
			$this.show();

			if (value === '' || this.value.toLowerCase() === options.name_lower) {
				return;

				//$('select').selectmenu('refresh');
			}

			// Create a new option with that value
			var $searchedOption = $this.children('[value="' + value + '"]');

			// If value doesn't exist, added it.
			if ( $searchedOption.length < 1  ) {
				var $option = $('<option value="' + value + '">' + value + ' </option>');
				$this.append($option);
			}

			// Focus the value
			$this.val( value );
		});




		// TODO
		// var doCallback = function(name) {
		// 	if ( options[name] ) {
		// 		options[name]();
		// 	}
		// };
	};

}));