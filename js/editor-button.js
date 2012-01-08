function wdsm_openEditor () {
	jQuery(document).trigger('wdsm_selector_open');
	return false;
}

(function ($) {
$(function () {
	
/**
 * Inserts marker into regular (textarea) editor.
 */
function insertAtCursor(fld, text) {
    // IE
    if (document.selection && !window.opera) {
    	fld.focus();
        sel = window.opener.document.selection.createRange();
        sel.text = text;
    }
    // Rest
    else if (fld.selectionStart || fld.selectionStart == '0') {
        var startPos = fld.selectionStart;
        var endPos = fld.selectionEnd;
        fld.value = fld.value.substring(0, startPos)
        + text
        + fld.value.substring(endPos, fld.value.length);
    } else {
    	fld.value += text;
    }
}
	
	
//Create the needed editor container HTML
$('body').append(
	'<div id="wdsm_ad_container" style="display:none">' + 
		'<div id="wdsm_ads">' +
		'</div>' +
	'</div>'
);

// Bind events
$(document).bind('wdsm_selector_open', function () {
	$("#wdsm_ads").html("loading");
	$.post(ajaxurl, {"action": "wdsm_list_ads"}, function (data) {
		var html = '';
		
		// Appearance
		html += '<h4>' + l10nWdsm.appearance + '<span id="wdsm_advanced_trigger"><a href="#">' + l10nWdsm.advanced + '</a></span>' + '</h4>';
		html += '<div class="wdsm_advanced">';
		html += '<label for="wdsm_class">' + 
			l10nWdsm.ad_class +
			'<input id="wdsm_class" type="text" class="widefat" />' +
		'</label>';
		html += '<label for="wdsm_container_class">' + 
		l10nWdsm.ad_container_class +
		'<input id="wdsm_container_class" type="text" class="widefat" />' +
		'</label></div>';
		html += '<div><label>' + l10nWdsm.ad_alignment + '</label> ' +
			'<select id="wdsm_button_alignment">' +
				'<option value="">' + l10nWdsm.default_alignment + '&nbsp;</option>' +
				//'<option value="wdsm_left">' + l10nWdsm.left + '&nbsp;</option>' +
				'<option value="alignleft">' + l10nWdsm.left + '&nbsp;</option>' +
				//'<option value="wdsm_right">' + l10nWdsm.right + '&nbsp;</option>' +
				'<option value="alignright">' + l10nWdsm.right + '&nbsp;</option>' +
				//'<option value="wdsm_center">' + l10nWdsm.center + '&nbsp;</option>' +
				'<option value="aligncenter">' + l10nWdsm.center + '&nbsp;</option>' +
			'</select>';
		html += '</div><br />';
		
		// Existing ads
		//html += '<table class="widefat" border="1">';
		
		//html += '<thead><tr> <th>' + l10nWdsm.ad_title + '</th> <th>' + l10nWdsm.ad_date + '</th> <th>' + l10nWdsm.ad_type + '</th> <th></th> </tr></thead>';
		//html += '<tfoot><tr> <th>' + l10nWdsm.ad_title + '</th> <th>' + l10nWdsm.ad_date + '</th> <th>' + l10nWdsm.ad_type + '</th> <th></th> </tr></tfoot>';
		
		$.each(data, function (idx, ad) {
			/*
			html += '<tr>';
			html += '<td>' + ad.post_title + '</td>';
			html += '<td>' + ad.post_date + '</td>';
			html += '<td>' + (('download_url' == ad.wdsm.type) ? l10nWdsm.download_url : l10nWdsm.coupon_code ) + '</td>';
			html += '<td><a href="#" class="wdsm_insert_ad">' + l10nWdsm.add_ad + ' <input type="hidden" value="' + ad.ID + '" /></a></td>';
			html += '</tr>';
			*/
			html += '<div class="wdsm-insertion_item">';
			html += '<b>' + ad.post_title + '</b>';
			html += '<span class="wdsm-insertion_item-meta">';
			html += ('download_url' == ad.wdsm.type) ? l10nWdsm.download_url : l10nWdsm.coupon_code;			
			html += '</span>';
			html += '<a href="#" class="wdsm_insert_ad button">' + l10nWdsm.add_ad + ' <input type="hidden" value="' + ad.ID + '" /></a>';
			html += '<div style="clear:both"></div></div>';
		});
		
		//html += '</table>';
		
		$("#wdsm_ads").html(html);
		$("#wdsm_advanced_trigger a").click(function () {
			if ($(".wdsm_advanced").is(":visible")) $(".wdsm_advanced").hide();
			else $(".wdsm_advanced").show();
			return false;
		});
	});
});

$(".wdsm_insert_ad").live('click', function () {
	var id = parseInt($(this).find("input:hidden").val());
	var id_str = 'id="' + id + '"';
	var cls = $("#wdsm_class").val();
	var alg = $("#wdsm_button_alignment").val();
	cls += " " + alg;
	var ccls = $("#wdsm_container_class").val();
	var app_str = cls ? 'class="' + cls + '"' : '';
	var capp_str = ccls ? 'container_class="' + ccls + '"' : '';
	var marker = ' [wdsm_ad ' + id_str + ' ' + app_str + ' ' + capp_str + '] ';
	if (window.tinyMCE && ! $('#content').is(':visible')) window.tinyMCE.execCommand("mceInsertContent", true, marker);
	else insertAtCursor($("#content").get(0), marker);
	tb_remove();
	return false;
});

// Find Media Buttons strip and add the new one
var mbuttons_container = $('#media-buttons').length ? /*3.2*/ $('#media-buttons') : /*3.3*/ $("#wp-content-media-buttons");
if (!mbuttons_container.length) return;

mbuttons_container.append('' + 
	'<a onclick="return wdsm_openEditor();" title="' + l10nWdsm.add_ad + '" class="thickbox" id="add_map" href="#TB_inline?width=640&height=594&inlineId=wdsm_ad_container">' +
		'<img onclick="return false;" alt="' + l10nWdsm.add_ad + '" src="' + _wdsm_data.root_url + '/img/menu_inactive.png">' +
	'</a>'
);

});
})(jQuery);