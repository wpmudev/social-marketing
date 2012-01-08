<?php
/**
 * Shows Google+ page box
 */
class Wdsm_WidgetAdvert extends WP_Widget {

	function Wdsm_WidgetAdvert () {
		$widget_ops = array('classname' => __CLASS__, 'description' => __('Shows your selected Social Marketing Advert', 'wdgpo'));
		parent::WP_Widget(__CLASS__, 'Social Marketing Advert', $widget_ops);
		add_action('wp_print_scripts', array($this, 'enqueue_js_dependencies'));
		add_action('wp_print_styles', array($this, 'enqueue_css_dependencies'));
	}
	
	function enqueue_js_dependencies () {
		if (is_admin()) return false;
		if (is_singular()) return false;
		if (!is_active_widget(false, false, $this->id_base)) return false;
		
		$wdsm = Wdsm_SocialMarketing::get_instance();
		
		// JS
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
		foreach ($wdsm->get_services() as $id=>$service) {
			$wdsm->add_service_handler_js($id);
			if (!(int)$have_js[$id]) $wdsm->add_service_js($id);
		}

		printf('<script type="text/javascript">var _wdsm_ajax_url="%s";</script>', admin_url('admin-ajax.php'));
	}
		
	function enqueue_css_dependencies () {
		if (is_admin()) return false;
		if (is_singular()) return false;
		if (!is_active_widget(false, false, $this->id_base)) return false;
		
		$wdsm = Wdsm_SocialMarketing::get_instance();
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
			wp_enqueue_style('wdsm-public', WDSM_PLUGIN_URL . "/css/public.css");
			if (@$opt['theme']) {
				$theme = preg_replace('/[^-_a-z0-9]/', '', strtolower(@$opt['theme']));
				if (!file_exists(WDSM_PLUGIN_BASE_DIR . "/css/themes/{$theme}.css")) return false;
				wp_enqueue_style('wdsm-theme', WDSM_PLUGIN_URL . "/css/themes/{$theme}.css");
			}
		}
	}

	function form($instance) {
		$title = esc_attr($instance['title']);
		$ad_id = @$instance['ad_id'];

		// Set defaults
		// ...
		$wdsm = Wdsm_SocialMarketing::get_instance();
		$ads = $wdsm->get_all_ads();

		$html = '<p>';
		$html .= '<label for="' . $this->get_field_id('title') . '">' . __('Title:', 'wdgpo') . '</label>';
		$html .= '<input type="text" name="' . $this->get_field_name('title') . '" id="' . $this->get_field_id('title') . '" class="widefat" value="' . $title . '"/>';
		$html .= '</p>';

		$html .= '<p>';
		$html .= '<label for="' . $this->get_field_id('ad_id') . '">' . __('Show this advert:', 'wdgpo') . '</label> ';
		$html .= '<select name="' . $this->get_field_name('ad_id') . '" id="' . $this->get_field_id('ad_id') . '">';
		foreach ($ads as $ad) {
			$selected = ($ad->ID == $ad_id) ? 'selected="selected"' : '';
			$html .= "<option value='{$ad->ID}' {$selected}>{$ad->post_title}&nbsp;</option>";
		}
		$html .= '</select>';
		$html .= '</p>';

		echo $html;
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['ad_id'] = strip_tags($new_instance['ad_id']);

		return $instance;
	}

	function widget($args, $instance) {
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);

		$ad_id = $instance['ad_id'];
		if (!$ad_id) return false;
		$codec = new Wdsm_Codec;

		echo $before_widget;
		if ($title) echo $before_title . $title . $after_title;

		echo $codec->process_ad_code(array('id'=>$ad_id), 'LALALA', 'forced');
		echo $after_widget;
	}

	function _show_posts ($feed_id, $limit) {
		$query = new WP_Query(array(
			'post_type' => array('post', 'wdgpo_imported_post'),
			'meta_key' => 'wdgpo_gplus_feed_id',
			'meta_value' => $feed_id,
			'posts_per_page' => (int)$limit,
		));
		echo "<ul class='wdgpo_gplus_posts'>";
		foreach ($query->posts as $post) {
			$url = ('wdgpo_imported_post' == $post->post_type) ? get_post_meta($post->ID, 'wdgpo_gplus_item_id', true) : get_permalink($post->ID);
			echo "<li>";
			echo '<a class="wdgpo_gplus_post_title" href="' . esc_url($url) . '">' . esc_html($post->post_title) . '</a>';
			echo "</li>";
		}
		echo "</ul>";
	}
}