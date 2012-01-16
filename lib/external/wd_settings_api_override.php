<?php
function wpmudev_do_settings_sections($page) {
	global $wp_settings_sections, $wp_settings_fields;

	if ( !isset($wp_settings_sections) || !isset($wp_settings_sections[$page]) )
		return;

	foreach ( (array) $wp_settings_sections[$page] as $section ) {
		if ( $section['title'] )
			echo "<h3>{$section['title']}</h3>\n";
		call_user_func($section['callback'], $section);
		if ( !isset($wp_settings_fields) || !isset($wp_settings_fields[$page]) || !isset($wp_settings_fields[$page][$section['id']]) )
			continue;
		echo '<table class="form-table">';
		wpmudev_do_settings_fields($page, $section['id']);
		echo '</table>';
	}
}
function wpmudev_do_settings_fields($page, $section) {
	global $wp_settings_fields;

	if ( !isset($wp_settings_fields) || !isset($wp_settings_fields[$page]) || !isset($wp_settings_fields[$page][$section]) )
		return;

	foreach ( (array) $wp_settings_fields[$page][$section] as $field ) {
		$idx = 'settings-' . preg_replace('/[^-_a-z0-9]/', '-', strtolower($field['title']));
		echo '<div class="postbox">';
		if ( !empty($field['args']['label_for']) )
			echo '<h3 class="hndle" id="' . $idx . '"><span><label for="' . $field['args']['label_for'] . '">' . $field['title'] . '</label></span></h3>';
		else
			echo '<h3 class="hndle" id="' . $idx . '"><span>' . $field['title'] . '</span></h3>';
		echo '<div class="inside">';
		call_user_func($field['callback'], $field['args']);
		echo '</div>';
		echo '</div>';
	}
}