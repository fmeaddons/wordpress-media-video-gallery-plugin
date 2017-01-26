<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
global $wpdb;
$zgallery_settings = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix. "zvideo_settings");
$zsettings = array();
foreach($zgallery_settings as $zgallery_setting){
	$zsettings[$zgallery_setting->setting_key] = $zgallery_setting->setting_value;
}

if(isset($_POST['submit'])) {

    if ( !current_user_can( apply_filters( 'fmamediagallery_capability', 'manage_options' ) ) )
            die( '-1' );
    
        check_admin_referer( 'fmamediagallery_setting_nonce_action', 'fmamediagallery_setting_nonce_field' );
    
	foreach($_POST as $key=>$value){
		$wpdb->query("Update " . $wpdb->prefix . "zvideo_settings set setting_value='".sanitize_text_field($value)."' where setting_key='".sanitize_text_field($key)."';");
	}
}


?>
<h1><?php _e('Media Gallery Settings', 'wordpress'); ?></h1>
<p><?php _e('Enter your settings below:', 'wordpress'); ?></p>

<form method="post" action="" accept-charset="utf-8">
    <?php wp_nonce_field('fmamediagallery_setting_nonce_action','fmamediagallery_setting_nonce_field'); ?>
	<table class="form-table">
		<tbody>
			<tr>
                <th scope="row">
                    <?php _e('Featured Videos Slider Title:','wordpress'); ?>
                    <p class="description">(<?php _e('This title will be shown on frontend on the featured videos slider.', 'wordpress'); ?>)</p>
                </th>
                <td>
                    <input type="text" class="regular-text" value="<?php echo esc_attr($zsettings['video_slider_title']); ?>" name="video_slider_title" placeholder="<?php _e( 'Featured Videos', 'wordpress' ); ?>"  />

                </td>
            </tr>

			<tr>
                <th scope="row">
                    <?php _e('Featured Videos Slider Sub Title:','wordpress'); ?>
                    <p class="description">(<?php _e('This sub title will be shown on frontend on the featured videos slider next to the title.', 'wordpress'); ?>)</p>
                </th>
                <td>
                    <input type="text" class="regular-text" value="<?php echo esc_attr($zsettings['video_slider_subtitle']); ?>" name="video_slider_subtitle" placeholder="<?php _e( 'Media Gallery Slider', 'wordpress' ); ?>"  />

                </td>
            </tr>

            <tr>
                <th scope="row">
                    <?php _e('All Videos Title:','wordpress'); ?>
                    <p class="description">(<?php _e('This title will be shown on frontend on the all videos box.', 'wordpress'); ?>)</p>
                </th>
                <td>
                    <input type="text" class="regular-text" value="<?php echo esc_attr($zsettings['video_gallery_title']); ?>" name="video_gallery_title" placeholder="<?php _e( 'Media Gallery', 'wordpress' ); ?>"  />

                </td>
            </tr>

            <tr>
                <th scope="row">
                    <?php _e('All Videos Sub Title:','wordpress'); ?>
                    <p class="description">(<?php _e('This subtitle will be shown on frontend on the all videos box next to the title.', 'wordpress'); ?>)</p>
                </th>
                <td>
                    <input type="text" class="regular-text" value="<?php echo esc_attr($zsettings['video_gallery_subtitle']); ?>" name="video_gallery_subtitle" placeholder="<?php _e( 'Grid', 'wordpress' ); ?>"  />

                </td>
            </tr>

            <tr>
                <th scope="row">
                    <?php _e('Per Page Videos:','wordpress'); ?>
                    <p class="description">(<?php _e('This is for paging, how many videos you want to show on each page.', 'wordpress'); ?>)</p>
                </th>
                <td>
                    <input type="number" min="0" class="regular-text" value="<?php echo esc_attr($zsettings['per_page_videos']); ?>" name="per_page_videos" placeholder="<?php _e( '16', 'wordpress' ); ?>"  />

                </td>
            </tr>

		</tbody>
	</table>
	<p class="submit">
        <input type="submit" value="<?php _e( 'Save Changes', 'wordpress' ); ?>" class="button-primary" name="submit">
    	
    </p>
</form>
