<?php
class Wdsm_GoogleService extends Wdsm_Service {

	public function settings () {
		$this->_settings = array (
			'id' => 'google',
			'button' => true,
			'text' => false,
		);
	}

	public function add_js () {
		echo '<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>';
	}

	public function add_handler_js () {
		wp_enqueue_script('google-wdsm', WDSM_PLUGIN_URL . '/js/google.js', array('jquery'));
	}

	public function render ($type, $ad=false) {
		if (!$type) return false;
		if ('text' == $type) return false;
		if ('button' == $type) return $this->_render_button($ad);
	}

	private function _render_button ($ad) {
		return "<div class='wdsm_button wdsm_google_button'><g:plusone size='tall' href='{$ad->wdsm->url}' callback='wdsm_google_callback'></g:plusone></div>";
	}
}