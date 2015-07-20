var _wdsm_href;

(function ($) {
$(function () {

$(".wdsm_action_link").click(function () {
	var id = $(this).attr('id') + '-container';
	_wdsm_href = $("#" + id).find("input.wdsm_ad_url").val();
	
	$("#"+id).parents(".wdsm_ad_container_wrapper").show();
	var height = $("#"+id).outerHeight();
	$("#"+id).parents(".wdsm_ad_container_wrapper").hide();
	
	tb_show($(this).find(".wdsm_action_title").text(), '#TB_inline?height=' + height + '&width=350&inlineId=' + id);
	return false;
});	

try {
	imgLoader = new Image();// preload image
	imgLoader.src = tb_pathToImage;
} catch (e) {}

$(document).bind("wdsm_button_action", function (e, service) {
	var ad_id = $("#TB_ajaxContent").find("input.wdsm_ad_id").val();
	var $services = $("#TB_ajaxContent").find(".wdsm_services");
	$services.after(
		'<p class="wdsm-service-waiting_response"><img src="' + _wdsm_data.root_url + '/img/ajax-loader.gif" /></p>'
	);
	$("#TB_ajaxContent").height(
			$("#TB_ajaxContent").height() + $("#TB_ajaxContent .wdsm-service-waiting_response").height() + 5
		);
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
		var $old = $("#TB_ajaxContent").find(".wdsm_result");
		if ($old.length) $old.remove();
		$("#TB_ajaxContent .wdsm-service-waiting_response").remove(); // Remove loading indicator
		$("#TB_ajaxContent").append('<div class="wdsm_result">' + html + '</div>');
		$("#TB_ajaxContent").height(
			$("#TB_ajaxContent").height() + $("#TB_ajaxContent .wdsm_result").height() + 5
		);
	});
});

	
});	
})(jQuery);