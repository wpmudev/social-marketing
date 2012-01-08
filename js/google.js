function wdsm_google_callback (data) {
	if (data.state == "off") return false;
	//if (data.href != _wdsm_href) return false;
	
	jQuery(document).trigger("wdsm_button_action", ["google"]);
}