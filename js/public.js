var _wdsm_href;

(function ($) {
	
var Wdsm_Popup = function () {
	
	var $overlay = false;
	var $root = false;
	var $body = false;
	var $close = false;
	
	var $_old = false;
	
	function resize () {
		$overlay
			.css({
				"position": "absolute",
				"height": $(document).height(),
				"width": "100%",
				"top": 0,
				"left": 0,
				"z-index": 999998
			})
		;
		$root.css("max-height", "none");
		if ($root.height() > $(window).height()) $root.css({
			"max-height": $(window).height()-30,
			"overflow-y": "scroll"
		});
		else $root.css("overflow-y", "hidden");
		$root
			.css({
				"position": "fixed",
				"z-index": 999999
			})
			.offset({
				"top": (( $(window).height() - $root.height() )/2) + $(window).scrollTop(),
				"left": ( $(window).width() - $root.width() )/2
			})
		;
	}
	
	function conditional_resize () {
		if ($overlay.is(":visible")) resize();
	}
	
	function find (selector) {
		return $body.find(selector);
	}
	
	function append (stuff) {
		$body.append(stuff);
	}
	
	function open (selector) {
		if ($overlay.is(":visible")) close();
		$overlay.show();
		$body.empty().append($(selector).show());
		$_old = $(selector);
		$root.show();
		resize();
		setTimeout(resize, 100); // Opera ffs
	}
	
	function close () {
		$body.empty();
		$root.hide();
		$overlay.hide();
		if ($_old && $_old.length) {
			$_old.hide();
			$("body").append($_old);
			$_old = false;
		}
		return false;
	}
	
	function bind_events () {
		$close.click(close);
		$(document).keydown(function (e) {
			if (e.keyCode == 27) close();
		})
		$(window).resize(conditional_resize);
	}
	
	function create_markup () {
		if ($("#wdsm-popup_wrapper").length) {
			$overlay = $("#wdsm-popup_overlay");
			$root = $("#wdsm-popup_wrapper");
			$body = $("#wdsm-popup_body");
			$close = $("#wdsm-popup_close");
			return false;
		}
		$("body").append(
			'<div id="wdsm-popup_overlay" style="display: none;"></div>' +
			'<div id="wdsm-popup_wrapper" style="display:none;">' + 
				'<div id="wdsm-popup_wrapper-inside">' + 
					'<div id="wdsm-popup_close">' + _wdsm_data.strings.close_button + '</div>' +
					'<div id="wdsm-popup_body"></div>' +
				'</div>' +
			'</div>'
		);
		create_markup();
	}
	
	function init () {
		create_markup();
		bind_events();
	};
	
	init();
	
	return {
		"find": find,
		"resize": resize,
		"append": append,
		"open": open,
		"close": close
	};
};
	
// Init
$(function () {
	
var popup = new Wdsm_Popup();

$(".wdsm_action_link").click(function () {
	var id = $(this).attr('id') + '-container';
	_wdsm_href = $("#" + id).find("input.wdsm_ad_url").val();

	popup.open("#" + id);
	return false;
});

$(document).bind("wdsm_button_action", function (e, service) {
	var ad_id = popup.find("input.wdsm_ad_id").val();
	var $services = popup.find(".wdsm_services");
	$services.after(
		'<p class="wdsm-service-waiting_response"><img src="' + _wdsm_data.root_url + '/img/ajax-loader.gif" /></p>'
	);
	popup.resize();
	$.post(_wdsm_data.ajax_url, {
		"action": "wdsm_show_code",
		"ad_id": ad_id,
		"service": service
	}, function (data) {
		var html = '<div class="wdsm_share_text">' + data.text + '</div>';
		if ("download_url" == data.type) {
			html += '<p><a id="wdsm-ad_download_link-' + ad_id + '" href="' + data.result + '" class="button"><span><strong>' + _wdsm_data.strings.download_button + '</strong></span></a></p>';
		} else {
			html += '<p><textarea cols="32" rows="12">' + data.result + '</textarea></p>';
		}
		var $old = popup.find(".wdsm_result");
		if ($old.length) $old.remove();
		popup.find(".wdsm-service-waiting_response").remove(); // Remove loading indicator
		popup.append('<div class="wdsm_result">' + html + '</div>');
		popup.resize();
	});
});

});	
})(jQuery);
