<?php
/**
 * Plugin Name:       FMA Media Gallery
 * Plugin URI:        http://fmeaddons.com/
 * Description:       Free Wordpress video gallery plugin allows you to create and manage unlimited media gallery on your website.
 * Version:           1.0.0
 * Author:            FME Addons
 * Developed By:      Hanan Ali, Raja Usman Mehmood
 * Author URI:        http://fmeaddons.com/
 * Support:           http://support.fmeaddons.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
if (!defined("FMA_GALLERY_ZVIDEO_PLUGIN_DIR")) define("FMA_GALLERY_ZVIDEO_PLUGIN_DIR",  plugin_dir_path( __FILE__ ));
if (!defined("FMA_GALLERY_ZVIDEO_PLUGIN_url")) define("FMA_GALLERY_ZVIDEO_PLUGIN_url",  plugin_dir_path( __FILE__ ));



function fmamg_settings() {
	include('admin/zvideo_settings.php');
}

function fmamg_management() {
	include('admin/zvideo_management.php');
}

if ( is_admin() ) {

    add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'fmamg_support_link' );
}

function fmamg_support_link( $actions ) {
            
        $custom_actions = array();

        // support url
        $custom_actions['support'] = sprintf( '<a href="%s" target="_blank">%s</a>', 'http://support.fmeaddons.com/', __( 'Support', 'fmepiw' ) );
        
        // add the links to the front of the actions list
        return array_merge( $custom_actions, $actions );
        
    }


function fmamg_admin_menu_actions() {  	
	add_menu_page('ZVideo Gallery', 'ZVideo Gallery', 'read', 'fmamg_settings','', '');
	add_submenu_page( 'zvideo_settings', 'Settings', 'Settings', 'read', 'zvideo_settings','fmamg_settings','');
	add_submenu_page( 'zvideo_settings', 'Manage Videos', 'Manage Videos', 'read', 'zvideo_management','fmamg_management','');

}

function fmamg_admin_js_loads(){
    wp_enqueue_script("jquery");
	wp_enqueue_script('jquery-ui');
}

function fmamg_front_css_loads()
{
    wp_enqueue_style("jquery-ui" );
    wp_enqueue_style("zvideo-gallery-css", plugins_url("/css/zvideo-gallery.css",__FILE__));
    wp_enqueue_style("fme-style-css", plugins_url("/css/style.css",__FILE__));
    wp_enqueue_style("owl-carousel-css", plugins_url("/js/owl-carousel/owl.carousel.css",__FILE__));

}


function fmamg_front_js_loads() {

	wp_enqueue_script("jquery");
	wp_enqueue_script("owl-carousel-js", plugins_url("/js/owl-carousel/owl.carousel.js",__FILE__));
	wp_enqueue_script("fme-function-js", plugins_url("/js/jquery-function.js",__FILE__));
	

}

function fmamg_create_installation_tables()
	{
		include FMA_GALLERY_ZVIDEO_PLUGIN_DIR . "/admin/create-db.php";
	}

function fmamg_drop_installation_tables()
	{
		include FMA_GALLERY_ZVIDEO_PLUGIN_DIR . "/admin/drop-db.php";
	}

function fmamg_homereplace($atts) {
		include FMA_GALLERY_ZVIDEO_PLUGIN_DIR . "/front/videohome.php";
		$allVideos     = fmamg_allVideos();
		//$contentFeatured   = $pageOBJ->home_thumb( 'featured' );
		return $allVideos;
}

function fmamg_shortcodeplace( $arguments = array() ) {
		global $frontControllerPath, $frontModelPath, $frontViewPath;
		//videogallery_jcar_js_css();
		include FMA_GALLERY_ZVIDEO_PLUGIN_DIR . '/front/videodetail.php';
		$videoDetail = fmamg_getVideoDetails( $arguments );
		return $videoDetail;
}

function fmamg_register() {
		$labels = array(
				'name' => _x( 'Video Gallery', 'post type general name' ),
				'singular_name' => _x( 'Video Gallery Item', 'post type singular name' ),
				'add_new' => _x( 'Add New', 'portfolio item' ),
				'add_new_item' => __( 'Add New Video Gallery Item' ),
				'edit_item' => __( 'Edit Video Gallery Item' ),
				'new_item' => __( 'New Video Gallery Item' ),
				'view_item' => __( 'View Video Gallery Item' ),
				'search_items' => __( 'Search Video Gallery' ),
				'not_found' => __( 'Nothing found' ),
				'not_found_in_trash' => __( 'Nothing found in Trash' ),
				'parent_item_colon' => '',
		);
		$args = array(
				'labels' => $labels,
				'public' => true,
				'publicly_queryable' => true,
				'show_ui' => false,
				'query_var' => true,
				'menu_icon' => FMA_GALLERY_ZVIDEO_PLUGIN_DIR . '/images/fma.png',
				'rewrite' => true,
				'capability_type' => 'post',
				'hierarchical' => false,
				'menu_position' => null,
				'supports' => array( 'title', 'editor', 'thumbnail', 'comments' ),
		);
		register_post_type( 'videogallery', $args );
}


register_activation_hook(__FILE__, "fmamg_create_installation_tables");
register_uninstall_hook(__FILE__, "fmamg_drop_installation_tables");

add_shortcode("videohome", "fmamg_homereplace");
add_shortcode('hdvideo','fmamg_shortcodeplace');
add_action('admin_menu', 'fmamg_admin_menu_actions');
add_action("admin_init", "fmamg_admin_js_loads");

if ( !is_admin() ) {
	add_action("init", "fmamg_front_css_loads");
	add_action("init", "fmamg_front_js_loads");
}

add_action( 'init', 'fmamg_register' );
?>
