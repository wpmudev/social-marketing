(function ($) {
$(function () {
	
/**
 * Handler tutorial resets.
 */
$(".wdsm-restart_tutorial").click(function () {
	var $me = $(this);
	// Change UI
	$me.after(
		'<img src="' + _wdsm_data.root_url + '/img/ajax-loader.gif" />'
	).remove();
	// Do call
	$.post(ajaxurl, {
		"action": "wdsm_restart_tutorial",
		"tutorial": $me.attr("data-wdsm_tutorial"),
	}, function () {
		window.location.reload();
	});
	return false;
});
	
});	
})(jQuery);
