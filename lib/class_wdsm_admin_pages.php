<?php
/**
 * Handles all Admin access functionality.
 */
class Wdsm_AdminPages {

	private $_wdsm;

	function __construct () {
		$this->_wdsm = Wdsm_SocialMarketing::get_instance();
	}

	/**
	 * Main entry point.
	 *
	 * @static
	 */
	public static function serve () {
		$me = new Wdsm_AdminPages;
		$me->_add_hooks();
	}

	/**
	 * Register settings fields and bind rendering handlers.
	 */
	function register_settings () {
		$form = new Wdsm_AdminFormRenderer;

		register_setting('wdsm', 'wdsm');
		add_settings_section('wdsm_services', ''/*__('Services', 'wdsm')*/, create_function('', ''), 'wdsm_options_page');
		//add_settings_field('wdsm_box', __('Pop-up box', 'wdsm'), array($form, 'create_popup_box'), 'wdsm_options_page', 'wdsm_services');
		add_settings_field('wdsm_js', __('Javascript', 'wdsm'), array($form, 'create_javascript_box'), 'wdsm_options_page', 'wdsm_services');
		add_settings_field('wdsm_theme', __('Appearance', 'wdsm'), array($form, 'create_theme_box'), 'wdsm_options_page', 'wdsm_services');
		add_settings_field('wdsm_getting_started', __('Getting started page', 'wdsm'), array($form, 'create_getting_started_box'), 'wdsm_options_page', 'wdsm_services');
	}
	
	/**
	 * Add supplemental submenu entries, and
	 * also process settings saving.
	 */
	function create_admin_menu_entry () {
		if (@$_POST && isset($_POST['option_page'])) {
			$changed = false;
			if ('wdsm' == wdsm_getval($_POST, 'option_page')) {
				update_option('wdsm', $_POST['wdsm']);
				$changed = true;
			}

			if ($changed) {
				$goback = isset($_POST['submit_and_go_back'])
					? admin_url('admin.php?page=wdsm-get_started')
					: add_query_arg('settings-updated', 'true',  wp_get_referer())
				;
				wp_redirect($goback);
				die;
			}
		}
		$page = "edit.php?post_type=social_marketing_ad";
		$perms = is_multisite() ? 'manage_network_options' : 'manage_options';
		$opts = get_option('wdsm');
		if (wdsm_getval($opts, 'show_getting_started') || (!wdsm_getval($opts, 'show_getting_started') && !$this->_getting_started_complete())) {
			add_submenu_page($page, __('Getting Started', 'wdsm'), __('Getting Started', 'wdsm'), $perms, 'wdsm-get_started', array($this, 'create_getting_started_page'));
		}
		add_submenu_page($page, __('Settings', 'wdsm'), __('Settings', 'wdsm'), $perms, 'wdsm', array($this, 'create_admin_page'));
	}

	/**
	 * Inject settings page markup.
	 */
	function create_admin_page () {
		include(WDSM_PLUGIN_BASE_DIR . '/lib/forms/plugin_settings.php');
	}
	
	/**
	 * Quick hack to reorder CPT menu items
	 * so that the welcome page come first.
	 */
	function reorder_menu () {
		$opts = get_option('wdsm');
		if (!wdsm_getval($opts, 'show_getting_started') && $this->_getting_started_complete()) return;
		global $menu, $submenu;
		
		foreach ($submenu as $idx => $item) {
			if ('edit.php?post_type=social_marketing_ad' != $idx) continue;
			//echo '<pre>'; die(var_export($item));
			$tmp = $item[11];
			unset($item[11]);
			array_unshift($item, $tmp);
			$submenu[$idx] = $item;
		}
	}
	
	/**
	 * Inject welcome page markup.
	 */
	function create_getting_started_page () {
		global $current_user;
		$wdsm_tutorial = get_user_meta($current_user->ID, 'wdsm_tutorial', true);
		$wdsm_tutorial = $wdsm_tutorial ? $wdsm_tutorial : array();
		include(WDSM_PLUGIN_BASE_DIR . '/lib/forms/getting_started.php');
	}
	
	/**
	 * Handle calls from welcome page and record progress.
	 */
	function handle_getting_started_redirects () {
		global $current_user;
		$wdsm_tutorial = get_user_meta($current_user->ID, 'wdsm_tutorial', true);
		$wdsm_tutorial = $wdsm_tutorial ? $wdsm_tutorial : array();
		
		$intent = wdsm_getval($_GET, 'intent');
		switch ($intent) {
			case "settings":
				$wdsm_tutorial['settings'] = 1;
				update_user_meta($current_user->ID, 'wdsm_tutorial', $wdsm_tutorial);
				wp_redirect(admin_url('admin.php?page=wdsm&tutorial=1'));
				exit;
			case "add":
				$wdsm_tutorial['add'] = 1;
				update_user_meta($current_user->ID, 'wdsm_tutorial', $wdsm_tutorial);
				wp_redirect(admin_url('post-new.php?post_type=social_marketing_ad'));
				exit;
			case "insert":
				$wdsm_tutorial['insert'] = 1;
				update_user_meta($current_user->ID, 'wdsm_tutorial', $wdsm_tutorial);
				wp_redirect(admin_url('post-new.php?wdsm=first'));
				exit;
		}
	}

	/**
	 * Redirect to Getting started page on first load.
	 */
	function welcome_first_time_user () {
		if (is_network_admin()) return false; // Not applicable on network pages.
		if ($this->_getting_started_complete()) return false; // User already saw this.
		
		$opts = get_option('wdsm');
		if (!wdsm_getval($opts, 'welcome_redirect')) return false; // Not a first time user, move on.
		
		$opts['welcome_redirect'] = false;
		update_option('wdsm', $opts);
		wp_redirect(admin_url('admin.php?page=wdsm-get_started'));
		die;
	}
	
	/**
	 * Quick "are we done yet" check for welcome page.
	 */
	private function _getting_started_complete () {
		global $current_user;
		$wdsm_tutorial = get_user_meta($current_user->ID, 'wdsm_tutorial', true);
		$wdsm_tutorial = $wdsm_tutorial ? $wdsm_tutorial : array();
		return (
			wdsm_getval($wdsm_tutorial, 'settings') && 
			wdsm_getval($wdsm_tutorial, 'add') && 
			wdsm_getval($wdsm_tutorial, 'insert')
		); 
	}

	/**
	 * Inject admin styles.
	 * CPT icons are injected inline, so we get the paths right.
	 */
	function css_print_styles () {
		$base_url = WDSM_PLUGIN_URL;
		echo <<<EoWdsmStyles
<style type="text/css">
li.menu-icon-social_marketing_ad div.wp-menu-image {
	background: url($base_url/img/menu_inactive.png) no-repeat center;
}
li.menu-icon-social_marketing_ad:hover div.wp-menu-image, li.menu-icon-social_marketing_ad.wp-has-current-submenu div.wp-menu-image {
    background: url($base_url/img/menu_active.png) no-repeat center;
}
li.menu-icon-social_marketing_ad div.wp-menu-image img {
    display: none;
}
</style>
EoWdsmStyles;
		if ('wdsm-get_started' == wdsm_getval($_GET, 'page') || 'wdsm' == wdsm_getval($_GET, 'page') || 'social_marketing_ad' == wdsm_getval($_GET, 'post_type')) {
			wp_enqueue_style('wdsm-admin-style', WDSM_PLUGIN_URL . "/css/wdsm-admin.css");
		}
	}

	/**
	 * Inject basic javascript dataset, for consistency. 
	 */
	function js_print_scripts () {
		if ('wdsm' == wdsm_getval($_GET, 'page') || 'social_marketing_ad' == wdsm_getval($_GET, 'post_type')) {
			wp_enqueue_script('wdsm_editor', WDSM_PLUGIN_URL . '/js/admin.js', array('jquery'));
		}
		printf(
			'<script type="text/javascript">
				var _wdsm_data = {
					"root_url": "%s",
				};
			</script>',
			WDSM_PLUGIN_URL
		);
	}

	/**
	 * Inject post editor button and handler javascript.
	 */
	function js_editor_button () {
		if ('social_marketing_ad' == wdsm_getval($_GET, 'post_type')) return false; // Don't do this if we're on our own page
		global $post;
		if ('social_marketing_ad' == @$post->post_type) return false; // Don't show on edit page either
		
		wp_enqueue_script('wdsm_editor', WDSM_PLUGIN_URL . '/js/editor-button.js', array('jquery'));
		wp_localize_script('wdsm_editor', 'l10nWdsm', array(
			'loading' => __('Loading... please hold on', 'wdsm'),
			'add_ad' => __('Insert Social Ad', 'wdsm'),
			'ad_title' => __('Title', 'wdsm'),
			'ad_date' => __('Date', 'wdsm'),
			'ad_type' => __('Type', 'wdsm'),
			'ad_services' => __('Services', 'wdsm'),
			'appearance' => __('Appearance', 'wdsm'),
			'advanced' => __('Advanced', 'wdsm'),
			'ads' => __('Adverts', 'wdsm'),
			'add_blank' => __('Insert blank placeholder for an ad', 'wdsm'),
			'or_select_below' => __('or select an Ad to insert from the ones listed below', 'wdsm'),
			'dflt' => __('Default', 'wdsm'),
			'ad_class' => __('Additional CSS classes (link)', 'wdsm'),
			'ad_container_class' => __('Additional CSS classes (container)', 'wdsm'),
			'download_url' => __('Download URL', 'wdsm'),
			'coupon_code' => __('Coupon Code', 'wdsm'),
			'ad_alignment' => __('Alignment', 'wdsm'),
			'default_alignment' => __('Default', 'wdsm'),
			'left' => __('Left', 'wdsm'),
			'right' => __('Right', 'wdsm'),
			'center' => __('Center', 'wdsm'),
		));
	}

/* ----- AJAX request handlers ----- */

	/**
	 * Shows Ad code on successful share.
	 */
	function json_show_code () {
		$id = (int)$_POST['ad_id'];
		if (!$id) return false;
		$ad = $this->_wdsm->get_ad($id);
		$type = $ad->wdsm->type;
		$result = $ad->wdsm->result[$type];
		header('Content-type: application/json');
		echo json_encode(array(
			"text" => apply_filters('wdsm-show_code-share_text', do_shortcode($ad->wdsm->share_text), $ad, $id),
			"type" => $type,
			"result" => apply_filters('wdsm-show_code-result', do_shortcode($result), $ad, $id),
		));
		exit();
	}

	/**
	 * Lists all Ads.
	 */
	function json_list_ads () {
		$ads = $this->_wdsm->get_all_ads();
		header('Content-type: application/json');
		echo json_encode($ads);
		exit();
	}

	/**
	 * Glues everything together.
	 */
	private function _add_hooks () {
		add_action('admin_init', array($this, 'handle_getting_started_redirects'));
		add_action('admin_init', array($this, 'welcome_first_time_user'));
		
		// Register options and menu
		add_action('admin_init', array($this, 'register_settings'));
		add_action('admin_menu', array($this, 'create_admin_menu_entry'));

		add_action('admin_menu', array($this, 'reorder_menu'), 999);
		
		add_action('admin_print_scripts', array($this, 'js_print_scripts'));
		add_action('admin_print_styles', array($this, 'css_print_styles'));

		add_action('admin_print_scripts-post.php', array($this, 'js_editor_button'));
		add_action('admin_print_scripts-post-new.php', array($this, 'js_editor_button'));

		add_action('wp_ajax_wdsm_show_code', array($this, 'json_show_code'));
		add_action('wp_ajax_nopriv_wdsm_show_code', array($this, 'json_show_code'));
		add_action('wp_ajax_wdsm_list_ads', array($this, 'json_list_ads'));
	}
}
