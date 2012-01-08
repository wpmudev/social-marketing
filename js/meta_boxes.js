(function ($) {
$(function () {
	
function toggle_type () {
	var $opt = $("#wdsm_type option:selected");
	$(".wdsm_result_item").hide();
	$("#" + $opt.attr("wdsm:for")).show();
}
	
$("#wdsm_type").change(toggle_type);
toggle_type();
	
});
})(jQuery);