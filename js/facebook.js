(function ($) {
$(function () {
	
function wdsm_facebook_callback (url) {
	//if (url != _wdsm_href) return false;
	
	$(document).trigger("wdsm_button_action", ["facebook"]);
}

function wdsm_facebook_register () {
	if (typeof FB != 'undefined') FB.Event.subscribe('edge.create', wdsm_facebook_callback);
	else setTimeout(wdsm_facebook_register, 200);
}
wdsm_facebook_register();
	
});	
})(jQuery);