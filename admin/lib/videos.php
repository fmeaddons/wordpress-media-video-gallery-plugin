<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
		
		function fmamg_insert_video( $videoData ) {
			global $wpdb;
			$video_table = $wpdb->prefix . 'zvideo_management';
			$post_table = $wpdb->prefix . 'posts';
			$current_user   = $current_user->ID;
			$post_id = $wpdb->get_var( 'SELECT ID FROM ' . $post_table . ' order by ID desc' );
			if ( $wpdb->insert( $video_table, $videoData ) ) {
				$last_insert_video_id = $wpdb->insert_id;
				$post_content		  = '[hdvideo id=' . $wpdb->insert_id . ']';
				$post_id			  = $post_id + 1;

				$postsData = array(
					'post_author'			=> $current_user,
					'post_date'				=> date( 'Y-m-d H:i:s' ),
					'post_date_gmt'			=> date( 'Y-m-d H:i:s' ),
					'post_content'			=> $post_content,
					'post_title'			=> $videoData['name'],
					'post_excerpt'			=> '',
					'post_status'			=> 'publish',
					'comment_status'		=> 'open',
					'ping_status'			=> 'closed',
					'post_password'			=> '',
					'post_name'				=> sanitize_title($videoData['video_title']),
					'to_ping'				=> '',
					'pinged'				=> '',
					'post_modified'			=> date( 'Y-m-d H:i:s' ),
					'post_modified_gmt'		=> date( 'Y-m-d H:i:s' ),
					'post_content_filtered' => '',
					'post_parent'			=> 0,
					'menu_order'			=> '0',
					'post_type'				=> 'videogallery',
					'post_mime_type'		=> '',
					'comment_count'			=> '0',
				);
				
				//  Default  wordpress  method  for  post  add
				//if(empty($this->_videoId)) {
					$post_ID = wp_insert_post( $postsData );
				//}
				$guid = get_site_url() . '/?post_type=videogallery&#038;p=' . $post_ID;
				$wpdb->update( $post_table, array( 'guid' => $guid ), array( 'ID' => $post_ID ) );
				$wpdb->update( $video_table, array( 'slug' => $post_ID ), array( 'video_id' => $last_insert_video_id ) );
				return $last_insert_video_id;
			}
		}
?>
