<?php
class Wdsm_PublicPages {

	private $_codec;
	private $_wdsm;

	private function __construct () {
		$this->_codec = new Wdsm_Codec;
		$this->_wdsm = Wdsm_SocialMarketing::get_instance();
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
		wp_enqueue_script('jquery');

		$opt = get_option('wdsm');
		$opt = $opt ? $opt : array();

		if ('colorbox' == @$opt['popup_box']) {
			wp_enqueue_script('wdsm-public', WDSM_PLUGIN_URL . '/js/public-cb.js');
			if (!@$opt['internal_colorbox']) {
				wp_enqueue_script('wdsm-colorbox', WDSM_PLUGIN_URL . '/js/external/jquery.colorbox-min.js');
			}
		} else {
			add_thickbox();
			wp_enqueue_script('wdsm-public', WDSM_PLUGIN_URL . '/js/public-tb.js');
		}

		$have_js = $opt['have_js'];
		foreach ($this->_wdsm->get_services() as $id=>$service) {
			$this->_wdsm->add_service_handler_js($id);
			if (!(int)$have_js[$id]) $this->_wdsm->add_service_js($id);
		}

		printf('<script type="text/javascript">var _wdsm_ajax_url="%s";</script>', admin_url('admin-ajax.php'));
	}
	
	/**
	 * Loads css dependencies.
	 */
	function css_load_styles () {
		if (!is_singular()) return false;

		$opt = get_option('wdsm');
		$opt = $opt ? $opt : array();

		if ('colorbox' == @$opt['popup_box']) {
			if (!@$opt['internal_colorbox']) {
				wp_enqueue_style('wdsm-colorbox', WDSM_PLUGIN_URL . '/css/external/colorbox.css');
			}
		} else {
			wp_enqueue_style('thickbox');
		}

		if (!current_theme_supports('wdsm')) {
			wp_enqueue_style('wdsm-public', WDSM_PLUGIN_URL . "/css/wdsm.css");
		}
	}

	/**
	 * Glues everything together.
	 */
	private function _add_hooks () {
		add_action('wp_print_scripts', array($this, 'js_load_scripts'));
		add_action('wp_print_styles', array($this, 'css_load_styles'));

		$this->_codec->register();
	}
}