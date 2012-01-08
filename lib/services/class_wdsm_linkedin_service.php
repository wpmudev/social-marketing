<?php
class Wdsm_LinkedInService extends Wdsm_Service {

	public function settings () {
		$this->_settings = array (
			'id' => 'linkedin',
			'button' => true,
			'text' => false,
		);
	}

	public function add_js () {
		echo '<script src="http://platform.linkedin.com/in.js" type="text/javascript"></script>';
	}

	public function add_handler_js () {
		wp_enqueue_script('linkedin-wdsm', WDSM_PLUGIN_URL . '/js/linkedin.js', array('jquery'));
	}

	public function render ($type, $ad=false) {
		if (!$type) return false;
		if ('text' == $type) return false;
		if ('button' == $type) return $this->_render_button($ad);
	}

	private function _render_button ($ad) {
		return "<div class='wdsm_button wdsm_linkedin_button'><script type='IN/Share' data-counter='top' data-url='{$ad->wdsm->url}' data-onsuccess='wdsm_linkedin_callback' data-title='TITLE'></script></div>";
	}
}