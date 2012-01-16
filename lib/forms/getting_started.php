<div id="wdsm-plugin-setting" class="wrap">
<?php screen_icon(); ?>
<h2><?php _e('Getting started', 'wdsm');?></h2>

<div class="metabox-holder">
	
	<!-- Getting Started box -->
	<div class="postbox">
		<h3 class="hndle"><span><?php _e('Getting Started Guide', 'wdsm'); ?></span></h3>
		<div class="inside">
			<div class="note">
				<p><?php _e('Welcome to the Social Marketing Getting Started Guide.', 'wdsm'); ?></p>
			</div>
			<ol class="wdsm-steps">
				<li>
					<?php if (wdsm_getval($wdsm_tutorial, 'settings')) { ?>
						<span class="wdsm_del">
					<?php } ?>
						<?php _e('First up, you need to configure your settings. This is where you can set the behavior and appearance of your ads.', 'wdsm'); ?>					
					<?php if (wdsm_getval($wdsm_tutorial, 'settings')) { ?>
						</span>
					<?php } ?> 
					<a href="admin.php?page=wdsm-get_started&intent=settings" class="button"><?php _e('Configure your settings', 'wdsm'); ?></a>
				</li>
				<li>
					<?php if (wdsm_getval($wdsm_tutorial, 'add')) { ?>
						<span class="wdsm_del">
					<?php } ?>
						<?php _e('Next, create a new advert. ', 'wdsm'); ?>					
					<?php if (wdsm_getval($wdsm_tutorial, 'add')) { ?>
						</span>
					<?php } ?> 
					<a href="admin.php?page=wdsm-get_started&intent=add" class="button"><?php _e('Create Advert', 'wdsm'); ?></a>
				</li>
				<li>
					<?php if (wdsm_getval($wdsm_tutorial, 'insert')) { ?>
						<span class="wdsm_del">
					<?php } ?>
						<?php _e('Finally, insert your advert into a post.', 'wdsm'); ?>					
					<?php if (wdsm_getval($wdsm_tutorial, 'insert')) { ?>
						</span>
					<?php } ?> 
					<a href="admin.php?page=wdsm-get_started&intent=insert" class="button"><?php _e('Insert into Post', 'wdsm'); ?></a>
				</li>
			</ol>
		</div>
	</div>
	
	<!-- More Help box -->
	<div class="postbox">
		<h3 class="hndle"><span><?php _e('Need More Help?', 'wdsm'); ?></span></h3>
		<div class="inside">
			<ul>
				<li><a href="http://premium.wpmudev.org/project/social-marketing" target="_blank"><?php _e('Plugin project page', 'wdsm'); ?></a></li>
				<li><a href="http://premium.wpmudev.org/project/social-marketing/installation/" target="_blank"><?php _e('Installation and instructions page', 'wdsm'); ?></a></li>
				<!--<li><a href="#" target="_blank"><?php _e('Video tutorial', 'wdsm'); ?></a></li>-->
				<li><a href="http://premium.wpmudev.org/forums/tags/social-marketing" target="_blank"><?php _e('Support forum', 'wdsm'); ?></a></li>
			</ul>
		</div>
	</div>
</div>

</div>