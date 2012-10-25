<?php
class Wdsm_PublicPages {

	private $_codec;
	private $_wdsm;
	private $_data;

	private function __construct () {
		$this->_codec = new Wdsm_Codec;
		$this->_wdsm = Wdsm_SocialMarketing::get_instance();
		$this->_data = get_option('wdsm', array());
	}

	/**
	 * Main entry point.
	 */
	public static function serve () {
		$me = new Wdsm_PublicPages;
		$me->_add_hooks();
	}

	/**
	 * Loads javascript dependencies.
	 */
	function js_load_scripts () {
		if (!is_singular()) return false;
		$this->_wdsm->include_frontend_javascript();
	}
	
	/**
	 * Loads css dependencies.
	 */
	function css_load_styles () {
		if (!is_singular()) return false;
		$this->_wdsm->include_frontend_stylesheet();
	}

	/**
	 * Glues everything together.
	 */
	private function _add_hooks () {
		if (!wdsm_getval($this->_data, 'enable_late_binding')) {
			add_action('wp_print_scripts', array($this, 'js_load_scripts'));
			add_action('wp_print_styles', array($this, 'css_load_styles'));
		}
		//$this->_wdsm->late_bind_frontend_dependencies();

		$this->_codec->register();
	}
}