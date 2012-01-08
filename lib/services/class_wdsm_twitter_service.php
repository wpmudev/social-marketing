<?php
class Wdsm_TwitterService extends Wdsm_Service {

	public function settings () {
		$this->_settings = array (
			'id' => 'twitter',
			'button' => true,
			'text' => false,
		);
	}

	public function add_js () {
		echo '<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>';
	}

	public function add_handler_js () {
		wp_enqueue_script('twitter-wdsm', WDSM_PLUGIN_URL . '/js/twitter.js', array('jquery'));
	}

	public function render ($type, $ad=false) {
		if (!$type) return false;
		if ('button' == $type) return $this->_render_button($ad);
		if ('text' == $type) return $this->_render_text($ad);
	}

	private function _render_button ($ad) {
		return "<div class='wdsm_button wdsm_facebook_button'><a href='http://twitter.com/share' class='twitter-share-button' data-count='vertical' data-url='{$ad->wdsm->url}'>Tweet</a></div>";
	}

	private function _render_text ($ad) {
		return "<div class='wdsm_facebook_button'>
			<fb:like href='{$ad->wdsm->url}'>
			<textarea>{$ad->wdsm->share_text}</textarea>
		</div>";
	}
}