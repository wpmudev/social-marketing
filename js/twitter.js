(function ($) {
$(function () {
	
function wdsm_twitter_callback (data) {
	// Verify?
	// ...
	
	$(document).trigger("wdsm_button_action", ["twitter"]);
}
	
twttr.events.bind('tweet', wdsm_twitter_callback);
	
});
})(jQuery);