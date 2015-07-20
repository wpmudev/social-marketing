<?php
class Wdsm_Codec {

	/**
	 * Map of shortcodes and processing methods,
	 * used for automatic codec binding.
	 * 
	 * Format: pfx => shortcode,
	 * method: process_pfx_code
	 */
	private $_shortcodes = array(
		'ad' => 'wdsm_ad',
	);
	private $_wdsm;

	public function __construct () {
		$this->_wdsm = Wdsm_SocialMarketing::get_instance();
	}

	/**
	 * Main shortcode processing handler.
	 */
	public function process_ad_code ($args, $content='', $forced=false) {
		$args = shortcode_atts(array(
			'id' => false,
			'slug' => false,
			'class' => false,
			'container_class' => false,
			'forced' => false,
		), $args);
		$forced = $forced && $forced === 'forced';
		if (!$forced) $forced = isset($args['forced']) && in_array($args['forced'], array('1', 'yes', 'on', 'true'));
		
		if (!is_singular() && !$forced) return '';

		$this->_wdsm->late_bind_frontend_dependencies();

		$ad = false;
		if ($args['id']) {
			$ad = $this->_wdsm->get_ad($args['id']);
		} else if ($args['slug']) {
			$all = get_posts(array(
				'name' => $args['slug'],
				'post_type' => 'social_marketing_ad',
				'post_status' => 'publish',
				'showposts' => 1,
			));
			$ad = isset($all[0]) ? $this->_wdsm->get_ad($all[0]->ID) : false;
		}
		if (!$ad) return $content;

		$class = $args['class'] ? $args['class'] : '';
		$container_class = $args['container_class'] ? $args['container_class'] : '';

		$code = $this->_render_social_ad(compact('ad', 'args', 'class', 'container_class', 'content'));

		return $code;
	}
	
	/**
	 * Rendering routine, used instead of "normal" way of file inclusion
	 * to prevent buffering issues in certain setups.
	 */
	private function _render_social_ad ($args) {
		extract($args);
		$opts = get_option('wdsm');
		// Set unique style class
		$style_class = @$opts['theme'] ? @$opts['theme'] : 'yellow'; 
		
		// Do we already have a "style class" set by user?
		$wdsm = Wdsm_SocialMarketing::get_instance();
		$_styles = $wdsm->get_styles();
		foreach ($_styles as $style => $label) {
			if (!preg_match('/(^|\s)' . preg_quote($style, '/') . '(\s|$)/', $class)) continue;
			// If so, respect his wishes and don't duplicate
			$style_class = '';
			break;
		}
		
		$ret = "
<div class='wdsm_ad'>
	<div class='wdsm_ad_link {$class} {$style_class}'>
		<h4>{$ad->post_title}</h4>
		<div class='wdsm_action_content'>{$ad->post_content}</div>
		<a href='#' id='wdsm_ad-{$ad->ID}' class='wdsm_action_link'>{$ad->wdsm->button_text}</a>
	</div>
</div>
<div class='wdsm_ad_container_wrapper'>
	<div class='' id='wdsm_ad-{$ad->ID}-container'>
		<div class='wdsm_ad_container {$container_class}'>
			<input type='hidden' class='wdsm_ad_url' value='{$ad->wdsm->url}' />
			<input type='hidden' class='wdsm_ad_id' value='{$ad->ID}' />
			<div class='wdsm_ad_content'>
				<h3>{$ad->post_title}</h3>
				{$ad->post_content}
			</div>
			<div class='wdsm_services'>
		";
		foreach ($ad->wdsm->services as $id => $type) {
			$ret .= "<div class='wdsm_service wdsm_service_{$id}'>" .
				$this->_wdsm->get_service_markup($id, $type, $ad) .
			"</div>";
		}
		$ret .= "
			</div>
		</div>";
		$ret .= "
	</div>
</div>";
		return $ret;
	}

	/**
	 * Registers shortcode handlers.
	 */
	function register () {
		foreach ($this->_shortcodes as $key=>$shortcode) {
			add_shortcode($shortcode, array($this, "process_{$key}_code"));
		}
	}

}