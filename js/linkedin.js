function wdsm_linkedin_callback (url) {
	//if (url != _wdsm_href) return false;
	
	jQuery(document).trigger("wdsm_button_action", ["linkedin"]);
}