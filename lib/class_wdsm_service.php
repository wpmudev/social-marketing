<?php
/**
 * Abstract service class.
 * All classes in lib/services implement this.
 */
abstract class Wdsm_Service {

	protected $_settings;

	abstract public function settings ();
	abstract public function add_js ();
	abstract public function add_handler_js ();
	abstract public function render ($type, $ad=false);

	public function __construct () { $this->settings(); }

	public function get_meta_box ($sel=false) {
		$ret = '<div class="wdsm_services_box" id="wdsm_service-' . $this->_settings['id'] . '">';

		$ret .= '<table width="100%"><tr>';
		$ret .= '<td><img src="' . $this->_get_icon() . '" /></td>';
		$ret .= '<td>';

		$selected = (!$sel || 'nothing' == $sel) ? 'checked="checked"' : '';
		$ret .= '<input ' . $selected . ' type="radio" name="wdsm[services][' . $this->_settings['id'] . ']" id="wdsm-services-' . $this->_settings['id'] . '-nothing" value="" /> ' .
			'<label for="wdsm-services-' . $this->_settings['id'] . '-nothing">' . __("Don't show this service", 'wdsm') . '</label><br />';
		if ($this->_settings['button']) {
			$selected = ('button' == $sel) ? 'checked="checked"' : '';
			$ret .= '<input ' . $selected . ' type="radio" name="wdsm[services][' . $this->_settings['id'] . ']" id="wdsm-services-' . $this->_settings['id'] . '-button" value="button" /> ' .
				'<label for="wdsm-services-' . $this->_settings['id'] . '-button">' . __("Show share button", 'wdsm') . '</label><br />';
		}
		if ($this->_settings['text']) {
			$selected = ('text' == $sel) ? 'checked="checked"' : '';
			$ret .= '<input ' . $selected . ' type="radio" name="wdsm[services][' . $this->_settings['id'] . ']" id="wdsm-services-' . $this->_settings['id'] . '-text" value="text" /> ' .
				'<label for="wdsm-services-' . $this->_settings['id'] . '-text">' . __("Show share text", 'wdsm') . '</label><br />';
		}

		$ret .= '</td>';
		$ret .= '</tr></table>';

		$ret .= '</div>';
		return $ret;
	}

	public function get_setting ($what) {
		return @$this->_settings[$what];
	}

	private function _get_icon () {
		return WDSM_PLUGIN_URL . '/img/' . strtolower($this->_settings['id']) . '.png';
	}
}