<?php
/**
 * Social Marketing contextual help implementation.
 */

class Wdsm_ContextualHelp {
	
	private $_help;
	
	private $_pages = array(
		'list', 'edit', 'get_started', 'settings',
	);
	
	private $_social_marketing_sidebar = '';
	
	private function __construct () {
		if (!class_exists('WpmuDev_ContextualHelp')) require_once WDSM_PLUGIN_BASE_DIR . '/lib/external/class_wd_contextual_help.php';
		$this->_help = new WpmuDev_ContextualHelp();
		$this->_set_up_sidebar();
	}
	
	public static function serve () {
		$me = new Wdsm_ContextualHelp;
		$me->_initialize();
	}
	
	private function _set_up_sidebar () {
		$this->_social_marketing_sidebar = '' .
			'<h4>' . __('Social Marketing', 'wdsm') . '</h4>' .
			'<ul>' .
				'<li><a href="http://premium.wpmudev.org/social-marketing" target="_blank">' . __('Project page', 'wdsm') . '</a></li>' .
				'<li><a href="http://premium.wpmudev.org/social-marketing/installation/" target="_blank">' . __('Installation and instructions page', 'wdsm') . '</a></li>' .
				'<li><a href="http://premium.wpmudev.org/forums/tags/social-marketing/" target="_blank">' . __('Support forum', 'wdsm') . '</a></li>' .
			'</ul>' . 
		'';
	}
	
	private function _initialize () {
		foreach ($this->_pages as $page) {
			$method = "_add_{$page}_page_help";
			if (method_exists($this, $method)) $this->$method();
		}
		$this->_help->initialize();
	}
	
	private function _add_list_page_help () {
		$this->_help->add_page(
			'edit-social_marketing_ad',
			array(
				array(
					'id' => 'wdsm-intro',
					'title' => __('Intro', 'wdsm'),
					'content' => '<p>' . __('Your existing Social Marketing Adverts are listed here.', 'wdsm') . '</p>',
				),
			),
			$this->_social_marketing_sidebar,
			true
		);
	}
	private function _add_edit_page_help () {
		$this->_help->add_page(
			'social_marketing_ad',
			array(
				array(
					'id' => 'wdsm-intro',
					'title' => __('Intro', 'wdsm'),
					'content' => '' .
							'<p>' . 
								__('This is where you can edit or create a Social Marketing Advert.', 'wdsm') . 
							'</p>' . 
						'',
					),
				array(
					'id' => 'wdsm-tutorial',
					'title' => __('Tutorial', 'wdsm'),
					'content' => '' .
						'<p>' . 
							__('Tutorial dialogs will guide you through the important bits.', 'wdsm') . 
						'</p>' .
						'<p><a href="#" class="wdsm-restart_tutorial" data-wdsm_tutorial="edit">' . __('Restart the tutorial', 'wdsm') . '</a></p>',
				),
			),
			$this->_social_marketing_sidebar,
			true
		);		
	}
	private function _add_get_started_page_help () {
		$this->_help->add_page(
			'social_marketing_ad_page_wdsm-get_started',
			array(
				array(
					'id' => 'wdsm-intro',
					'title' => __('Intro', 'wdsm'),
					'content' => '<p>' . __('This is the guide to get you started with <b>Social Marketing</b> plugin', 'wdsm') . '</p>',
				),
				array(
					'id' => 'wdsm-steps',
					'title' => __('Guide', 'wdsm'),
					'content' => '<p>' . __('Please go through the steps to complete your plugin setup and find your way around', 'wdsm') . '</p>',
				),
			),
			$this->_social_marketing_sidebar,
			true
		);				
	}
	private function _add_settings_page_help () {
		$this->_help->add_page(
			'social_marketing_ad_page_wdsm',
			array(
				array(
					'id' => 'wdsm-intro',
					'title' => __('Intro', 'wdsm'),
					'content' => '<p>' . __('This is where you configure <b>Social Marketing</b> plugin for your site', 'wdsm') . '</p>',
				),
				array(
					'id' => 'wdsm-popup',
					'title' => __('Popup box', 'wdsm'),
					'content' => '' . 
							'<p>' . __('Choose between the WordPress built-in Thickbox, or more pretty Colorbox.', 'wdsm') . '</p>' .
							'<p>' . __("If you decide to go with Colorbox and your site already uses it, don't forget to check &quot;My site already uses Colorbox (via theme or plugin)&quot; option.", 'wdsm') . '</p>' .
						'',
				),
				array(
					'id' => 'wdsm-javascript',
					'title' => __('Javascript', 'wdsm'),
					'content' => '' . 
							'<p>' . __('If your site already uses javascripts from one or more of the supported services, say so here to prevent conflicts.', 'wdsm') . '</p>' .
						'',
				),
				array(
					'id' => 'wdsm-tutorial',
					'title' => __('Tutorial', 'wdsm'),
					'content' => '' .
						'<p>' . 
							__('Tutorial dialogs will guide you through the important bits.', 'wdsm') . 
						'</p>' .
						'<p><a href="#" class="wdsm-restart_tutorial" data-wdsm_tutorial="setup">' . __('Restart the tutorial', 'wdsm') . '</a></p>',
				),
			),
			$this->_social_marketing_sidebar,
			true
		);	
	}
}
