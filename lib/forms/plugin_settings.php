<?php
if (!function_exists('wpmudev_do_settings_sections')) require_once WDSM_PLUGIN_BASE_DIR . '/lib/external/wd_settings_api_override.php';
?>
<div id="wdsm-plugin-setting" class="wrap">
<?php screen_icon(); ?>
<h2 id="wdsm-settings_start"><?php _e('Settings', 'wdsm');?></h2>

<div class="metabox-holder">
	
<form action="options.php" method="post">
<?php settings_fields('wdsm'); ?>
<?php wpmudev_do_settings_sections('wdsm_options_page'); ?>

<p class="submit">
<?php if (wdsm_getval($_GET, 'tutorial')) { ?>
	<input name="Submit" type="submit" class="button" value="<?php esc_attr_e('Save Changes'); ?>" />
	<input name="submit_and_go_back" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes and go back to Tutorial'); ?>" />
<?php } else { ?>
	<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
<?php } ?>
</p>

</form>
</div> <!-- .metabox-holder -->

</div>