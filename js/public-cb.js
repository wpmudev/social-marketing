var _wdsm_href;

(function ($) {
$(function () {
	
$(".wdsm_action_link").each(function () {
	var id = $(this).attr('id') + '-container';
	_wdsm_href = $("#" + id).find("input.wdsm_ad_url").val();
	$(this).colorbox({
		"inline": true,
		"href": "#" + id,
		"width": 350,
		"title": $(this).find(".wdsm_action_title").text()
	});
});

$(document).bind("wdsm_button_action", function (e, service) {
	var ad_id = $("#cboxLoadedContent").find("input.wdsm_ad_id").val();
	var $services = $("#cboxLoadedContent").find(".wdsm_services");
	$services.after(
		'<p class="wdsm-service-waiting_response"><img src="' + _wdsm_data.root_url + '/img/ajax-loader.gif" /></p>'
	);
	$.colorbox.resize();
	$.post(_wdsm_data.ajax_url, {
		"action": "wdsm_show_code",
		"ad_id": ad_id,
		"service": service
	}, function (data) {
		var html = '<div class="wdsm_share_text">' + data.text + '</div>';
		if ("download_url" == data.type) {
			html += '<p><a href="' + data.result + '" class="button"><span><strong>Download</strong></span></a></p>';
		} else {
			html += '<p><textarea cols="32" rows="12">' + data.result + '</textarea></p>';
		}
		var $old = $("#cboxLoadedContent").find(".wdsm_result");
		if ($old.length) $old.remove();
		$("#cboxLoadedContent .wdsm-service-waiting_response").remove(); // Remove loading indicator
		$("#cboxLoadedContent").append('<div class="wdsm_result">' + html + '</div>');
		$.colorbox.resize();
	});
});

	
});	
})(jQuery);