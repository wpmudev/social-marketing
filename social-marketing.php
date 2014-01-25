<?php
/*
Plugin Name: Social Marketing
Plugin URI: http://premium.wpmudev.org/project/social-marketing
Description: Marketing on social networks.
Version: 1.2.2
Author: WPMU DEV
Author URI: http://premium.wpmudev.org
Text Domain: wdsm
WDP ID: 253

Copyright 2009-2011 Incsub (http://incsub.com)
Author - Ve Bailovity (Incsub)
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License (Version 2 - GPLv2) as published by
the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

define ('WDSM_PLUGIN_SELF_DIRNAME', basename(dirname(__FILE__)), true);

//Setup proper paths/URLs and load text domains
if (is_multisite() && defined('WPMU_PLUGIN_URL') && defined('WPMU_PLUGIN_DIR') && file_exists(WPMU_PLUGIN_DIR . '/' . basename(__FILE__))) {
	define ('WDSM_PLUGIN_LOCATION', 'mu-plugins', true);
	define ('WDSM_PLUGIN_BASE_DIR', WPMU_PLUGIN_DIR, true);
	define ('WDSM_PLUGIN_URL', WPMU_PLUGIN_URL, true);
	$textdomain_handler = 'load_muplugin_textdomain';
} else if (defined('WP_PLUGIN_URL') && defined('WP_PLUGIN_DIR') && file_exists(WP_PLUGIN_DIR . '/' . WDSM_PLUGIN_SELF_DIRNAME . '/' . basename(__FILE__))) {
	define ('WDSM_PLUGIN_LOCATION', 'subfolder-plugins', true);
	define ('WDSM_PLUGIN_BASE_DIR', WP_PLUGIN_DIR . '/' . WDSM_PLUGIN_SELF_DIRNAME, true);
	define ('WDSM_PLUGIN_URL', WP_PLUGIN_URL . '/' . WDSM_PLUGIN_SELF_DIRNAME, true);
	$textdomain_handler = 'load_plugin_textdomain';
} else if (defined('WP_PLUGIN_URL') && defined('WP_PLUGIN_DIR') && file_exists(WP_PLUGIN_DIR . '/' . basename(__FILE__))) {
	define ('WDSM_PLUGIN_LOCATION', 'plugins', true);
	define ('WDSM_PLUGIN_BASE_DIR', WP_PLUGIN_DIR, true);
	define ('WDSM_PLUGIN_URL', WP_PLUGIN_URL, true);
	$textdomain_handler = 'load_plugin_textdomain';
} else {
	// No textdomain is loaded because we can't determine the plugin location.
	// No point in trying to add textdomain to string and/or localizing it.
	wp_die(__('There was an issue determining where Social Marketing plugin is installed. Please reinstall.'));
}
$textdomain_handler('wdsm', false, WDSM_PLUGIN_SELF_DIRNAME . '/languages/');

if (file_exists(WDSM_PLUGIN_BASE_DIR . '/lib/external/wpmudev-dash-notification.php')) {
	require_once WDSM_PLUGIN_BASE_DIR . '/lib/external/wpmudev-dash-notification.php';
}

require_once WDSM_PLUGIN_BASE_DIR . '/lib/class_wdsm_installer.php';
Wdsm_Installer::check();

require_once WDSM_PLUGIN_BASE_DIR . '/lib/wdsm_exceptions.php';
require_once WDSM_PLUGIN_BASE_DIR . '/lib/class_wdsm_codec.php';
require_once WDSM_PLUGIN_BASE_DIR . '/lib/class_wdsm_social_marketing.php';
Wdsm_SocialMarketing::init();

// Widgets
require_once WDSM_PLUGIN_BASE_DIR . '/lib/class_wdsm_widget.php';
add_action('widgets_init', create_function('', "register_widget('Wdsm_WidgetAdvert');"));


if (is_admin()) {
	require_once WDSM_PLUGIN_BASE_DIR . '/lib/class_wdsm_tutorial.php';
	Wdsm_Tutorial::serve();

	require_once WDSM_PLUGIN_BASE_DIR . '/lib/class_wdsm_contextual_help.php';
	Wdsm_ContextualHelp::serve();
	
	require_once WDSM_PLUGIN_BASE_DIR . '/lib/class_wdsm_admin_form_renderer.php';
	require_once WDSM_PLUGIN_BASE_DIR . '/lib/class_wdsm_admin_pages.php';
	Wdsm_AdminPages::serve();
} else {
	require_once WDSM_PLUGIN_BASE_DIR . '/lib/class_wdsm_public_pages.php';
	Wdsm_PublicPages::serve();
}