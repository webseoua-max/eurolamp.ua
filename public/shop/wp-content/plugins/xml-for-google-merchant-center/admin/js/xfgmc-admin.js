(function ($) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	$(document).ready(function () {
		if ($('.xfgmc-postbox').length) {

			$(".xfgmc-postbox")
				.find(".postbox-header")
				.append("<div class='handle-actions hide-if-no-js'><button type='button' class='xfgmc-toggle handlediv' aria-expanded='false'><span class='screen-reader-text'>Показать/скрыть панель</span><span class='toggle-indicator' aria-hidden='true'></span></button></div>");

		}
	});

	$(document).on("click", ".xfgmc-toggle", function () {
		var icon = $(this);
		icon.parent().parent().parent().toggleClass("closed");
	});

})(jQuery);
