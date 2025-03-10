<?php
/*
Plugin Name: Muki Tag Cloud
Plugin URI: http://www.mukispace.com/wordpress-plugin-muki-tag-cloud
Description: Another wordpress tag cloud plugin based on jQCloud, which is creative, beauty and colorful. Design by <a href="http://muki.tw">MUKI</a>,Code by <a href="http://mesak.tw">Mesak</a>
Author: Muki
Author URI: http://muki.tw
Version: 2.1.4
*/

define('MUKI_TG_NAME', 'muki-tag-cloud');
define('MUKI_TG_PATH', WP_PLUGIN_DIR.'/'.MUKI_TG_NAME ); //load path
define('MUKI_TG_SETTING', 'muki_tag_cloud_setting'); //database option name
add_action('plugins_loaded', 'MukiTagCloud_init');
add_action('widgets_init', 'MukiTagCloud_load');
function MukiTagCloud_init() {
    /* Language Load  */
    load_plugin_textdomain( MUKI_TG_NAME, false, dirname( plugin_basename( __FILE__ ) ). '/languages/'  ); 
}
function MukiTagCloud_load() {
	/* Widget Load */
	include(MUKI_TG_PATH.'/widget.php');
	register_widget('MukiTagCloud_Widget');
}
function muki_tag_cloud_action_links($links, $file) {
    static $this_plugin;
    if (!$this_plugin) {
       $this_plugin = plugin_basename(__FILE__);
    }
    if ($file == $this_plugin) {
        $settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/options-general.php?page=muku-tag-cloud-options">'.__('Setting',MUKI_TG_NAME).'</a>';
        array_unshift($links, $settings_link);
    }
    return $links;
}
add_filter('plugin_action_links', 'muki_tag_cloud_action_links', 10, 2);

function muki_tag_cloud_get_option($name = '')
{
    //add options
	$defaults = array(
		'usejq'          => 'wp', //use wp jquery or template jquery
    	'widget_height'  => '400px',
    	'widget_width'   => '100%',
    	'article_order'  => 'article', // article , random
    	'font_small'     => 12,
    	'font_large'     => 36,
    	'minnum'         => 0, //min tag count 
    	'maxnum'         => 100, //max tag count 
    	'number'         => 0, //min tag count 
    	'color_scheme'   => 'fresh',
    	'usenofollow'	 => 0
	);
	$cache = wp_cache_get( MUKI_TG_NAME .'_option', 'option');
	$options = wp_parse_args($cache, $defaults);
	if( $cache == FALSE)
	{
		$db_options = get_option( MUKI_TG_SETTING );
		$options = wp_parse_args($db_options, $options);
		wp_cache_set(MUKI_TG_NAME.'_option', $options, 'option');
	}
    return isset($options[$name]) ? $options[$name] : $options;
}
/* add new submenu in admin page*/
add_action('admin_menu', 'muki_tc_add_page');
function muki_tc_add_page() {
    /* Setting Load  */
	include(MUKI_TG_PATH.'/setting.php');
    // Add a new submenu under Options:
    add_options_page( __('Muki Tag Cloud - User Options',MUKI_TG_NAME), 'Muki Tag Cloud', 'manage_options', 'muku-tag-cloud-options', 'muki_tag_cloud_option_page');
}

?>
