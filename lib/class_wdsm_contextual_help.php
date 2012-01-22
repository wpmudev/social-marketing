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
		$this->_social_marketing_sidebar = '<h4>' . __('Social Marketing', 'wdsm') . '</h4>';
		if (defined('WPMUDEV_REMOVE_BRANDING') && constant('WPMUDEV_REMOVE_BRANDING')) {
			$this->_social_marketing_sidebar .= '<p>' . __('Social Marketing allows you to create a ton of interest around your product or service by harnessing the real power of social networking.', 'wdsm') . '</p>';
		} else {
				$this->_social_marketing_sidebar .= '<ul>' .
					'<li><a href="http://premium.wpmudev.org/project/social-marketing" target="_blank">' . __('Project page', 'wdsm') . '</a></li>' .
					'<li><a href="http://premium.wpmudev.org/project/social-marketing/installation/" target="_blank">' . __('Installation and instructions page', 'wdsm') . '</a></li>' .
					'<li><a href="http://premium.wpmudev.org/forums/tags/social-marketing/" target="_blank">' . __('Support forum', 'wdsm') . '</a></li>' .
				'</ul>' . 
			'';
		}
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
				array(
					'id' => 'wdsm-general',
					'title' => __('General Info', 'wdsm'),
					'content' => '' .
						'<p>' . __('Social Marketing allows you to create a ton of interest around your product or service by harnessing the real power of social networking.', 'wdsm') . '</p>' .
						'<p><b>' . __('Using Social Marketing on your site:', 'wdsm') . '</b></p>' .
						'<ul>' .
							'<li>' . __('Visitors are enticed by a <b>coupon, discount code, download</b> or other incentive', 'wdsm') . '</li>' .
							'<li>' . __('Simply liking on facebook, retweeting, mentioning on google+ or linked in unlocks the incentive', 'wdsm') . '</li>' .
							'<li>' . __('Add your marketing into any post/page easily through a <b>simple button</b> in the WordPress editor', 'wdsm') . '</li>' .
						'</ul>' .
						'<p>' . sprintf(__('Get up and running quickly with the <a href="%s">Getting Started Guide</a>', 'wdsm'), admin_url('admin.php?page=wdsm-get_started')) . '</p>' .
					''
				),
				array(
					'id' => 'wdsm-available_actions',
					'title' => __('Available Actions', 'wdsm'),
					'content' => '' .
						'<p>' . __('Hovering over a row in the ads list will display action links that allow you to manage your ad. You can perform the following actions:', 'wdsm') . '</p>' .
						'<ul>' .
							'<li>' . __('<b>Edit</b> takes you to the editing screen for that ad. You can also reach that screen by clicking on the post title.', 'wdsm') . '</li>' .
							'<li>' . __('<b>Quick Edit</b> provides inline access to the metadata of your ad, allowing you to update ad details without leaving this screen.', 'wdsm') . '</li>' .
							'<li>' . __('<b>Trash</b> removes your ad from this list and places it in the trash, from which you can permanently delete it.', 'wdsm') . '</li>' .
							'<li>' . __('<b>Preview</b> will show you what your draft ad will look like if you publish it. View will take you to your live site to view the ad. Which link is available depends on your ad\'s status.', 'wdsm') . '</li>' .
						'</ul>' .
					''
				),
				array(
					'id' => 'wdsm-bulk_actions',
					'title' => __('Bulk Actions', 'wdsm'),
					'content' => '' .
						'<p>' . __('You can also edit or move multiple ads to the trash at once. Select the ads you want to act on using the checkboxes, then select the action you want to take from the Bulk Actions menu and click Apply.', 'wdsm') . '</p>' .
						'<p>' . __('When using Bulk Edit, you can change the status of several ads at once.  This may be useful if you want to make several ads available all at once.', 'wdsm') . '</p>' .
					''
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
					'id' => 'wdsm-general',
					'title' => __('General Info', 'wdsm'),
					'content' => '' .
						'<p>' . __('Social Marketing allows you to create a ton of interest around your product or service by harnessing the real power of social networking.', 'wdsm') . '</p>' .
						'<p><b>' . __('Using Social Marketing on your site:', 'wdsm') . '</b></p>' .
						'<ul>' .
							'<li>' . __('Visitors are enticed by a <b>coupon, discount code, download</b> or other incentive', 'wdsm') . '</li>' .
							'<li>' . __('Simply liking on facebook, retweeting, mentioning on google+ or linked in unlocks the incentive', 'wdsm') . '</li>' .
							'<li>' . __('Add your marketing into any post/page easily through a <b>simple button</b> in the WordPress editor', 'wdsm') . '</li>' .
						'</ul>' .
						'<p>' . sprintf(__('Get up and running quickly with the <a href="%s">Getting Started Guide</a>', 'wdsm'), admin_url('admin.php?page=wdsm-get_started')) . '</p>' .
					''
				),
				array(
					'id' => 'wdsm-creation',
					'title' => __('Ad creation', 'wdsm'),
					'content' => '' .
						'<p>' . __('<b>Title</b> - Enter a title for your add. After you enter a title, you\'ll see the permalink below, which you can edit.', 'wdsm') . '</p>' .
						'<p>' . __('<b>Ad Editor</b> - Enter the text for your ad. There are two modes of editing: Visual and HTML. Choose the mode by clicking on the appropriate tab. Visual mode gives you a WYSIWYG editor. Click the last icon in the row to get a second row of controls. The HTML mode allows you to enter raw HTML along with your ad text. You can insert media files by clicking the icons above the ad editor and following the directions. You can go to the distraction-free writing screen via the Fullscreen icon in Visual mode (second to last in the top row) or the Fullscreen button in HTML mode (last in the row). Once there, you can make buttons visible by hovering over the top area. Exit Fullscreen back to the regular ad editor.', 'wdsm') . '</p>' .
						'<p>' . __('<b>Publish</b> - You can set the terms of publishing your ad in the Publish box. For best results we recommend leaving these at their default settings.  Publishing your ad does not make it immediately visible.  It only makes it available as a shortcode which you can then insert into any post, page or custom post type.', 'wdsm') . '</p>' .
					''
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
					'id' => 'wdsm-general',
					'title' => __('General Info', 'wdsm'),
					'content' => '' .
						'<p>' . __('Social Marketing allows you to create a ton of interest around your product or service by harnessing the real power of social networking.', 'wdsm') . '</p>' .
						'<p><b>' . __('Using Social Marketing on your site:', 'wdsm') . '</b></p>' .
						'<ul>' .
							'<li>' . __('Visitors are enticed by a <b>coupon, discount code, download</b> or other incentive', 'wdsm') . '</li>' .
							'<li>' . __('Simply liking on facebook, retweeting, mentioning on google+ or linked in unlocks the incentive', 'wdsm') . '</li>' .
							'<li>' . __('Add your marketing into any post/page easily through a <b>simple button</b> in the WordPress editor', 'wdsm') . '</li>' .
						'</ul>' .
						'<p>' . sprintf(__('Get up and running quickly with the <a href="%s">Getting Started Guide</a>', 'wdsm'), admin_url('admin.php?page=wdsm-get_started')) . '</p>' .
					''
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
					'id' => 'wdsm-general',
					'title' => __('General Info', 'wdsm'),
					'content' => '' .
						'<p>' . __('Social Marketing allows you to create a ton of interest around your product or service by harnessing the real power of social networking.', 'wdsm') . '</p>' .
						'<p><b>' . __('Using Social Marketing on your site:', 'wdsm') . '</b></p>' .
						'<ul>' .
							'<li>' . __('Visitors are enticed by a <b>coupon, discount code, download</b> or other incentive', 'wdsm') . '</li>' .
							'<li>' . __('Simply liking on facebook, retweeting, mentioning on google+ or linked in unlocks the incentive', 'wdsm') . '</li>' .
							'<li>' . __('Add your marketing into any post/page easily through a <b>simple button</b> in the WordPress editor', 'wdsm') . '</li>' .
						'</ul>' .
						'<p>' . sprintf(__('Get up and running quickly with the <a href="%s">Getting Started Guide</a>', 'wdsm'), admin_url('admin.php?page=wdsm-get_started')) . '</p>' .
					''
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
