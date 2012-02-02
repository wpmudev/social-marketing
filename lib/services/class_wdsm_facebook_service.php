<?php
class Wdsm_FacebookService extends Wdsm_Service {

	public function settings () {
		$this->_settings = array (
			'id' => 'facebook',
			'button' => true,
			'text' => false,
		);
	}

	public function add_js () {
		$locale = preg_replace('/-/', '_', get_locale());
		wp_enqueue_script('facebook-all', 'http://connect.facebook.net/' . $locale . '/all.js');
		add_action('wp_footer', array($this, 'init_fb_script'));
	}

	public function init_fb_script () {
		echo <<<EOFb
<div id="fb-root"></div>
<script>
FB.init({
	status: true,
	cookie: true,
	xfbml: true
});
</script>
EOFb;
	}

	public function add_handler_js () {
		wp_enqueue_script('facebook-wdsm', WDSM_PLUGIN_URL . '/js/facebook.js', array('jquery'));
	}

	public function render ($type, $ad=false) {
		if (!$type) return false;
		if ('button' == $type) return $this->_render_button($ad);
		if ('text' == $type) return $this->_render_text($ad);
	}

	private function _render_button ($ad) {
		return "<div class='wdsm_button wdsm_facebook_button'><fb:like layout='box_count' href='{$ad->wdsm->url}'></fb:like></div>";
		//return "<div class='wdsm_button wdsm_facebook_button'><a href='#' onclick='javascript:wdsm_linkedin_callback(\"{$ad->wdsm->url}\");return false;'>Test service</a></div>";
	}

	private function _render_text ($ad) {
		return "<div class='wdsm_facebook_button'>
			<fb:like href='{$ad->wdsm->url}'>
			<textarea>{$ad->wdsm->share_text}</textarea>
		</div>";
	}
}