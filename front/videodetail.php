<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
function fmamg_getVideoDetails($getVideoDetails){
	global $wpdb;
	$video_id = $getVideoDetails['id'];
	$videoTable = $wpdb->prefix.'zvideo_management';
	 
		
	// get video details
	$video_details = $wpdb->get_row(
			'SELECT * FROM ' . $videoTable . ' WHERE published=1 AND video_id=' . intval( $video_id ) . ' LIMIT 1'
			);
	
	$videoUrl = $file_type = $video_title = $video_description = $video_thumb  = $post_date = '';
	
	// extracting video data
	if (! empty ( $video_details )) {
		$videoUrl = $video_details->video_file;
		$videoId = $video_details->video_id;
		$video_title = $video_details->video_title;
		$file_type = $video_details->video_type;
		$video_thumb = $video_details->video_image;
		$video_description = $video_details->video_description;
		$post_date = $video_details->post_date;
	}
	$output = '';
	$output .= '<h1 class="video-title">'.$video_title.'</h1>';
	$output .= '<p>'.$video_description.'</p>';

	
	// Check for youtube video
	if (preg_match ( '/www\.youtube\.com\/watch\?v=[^&]+/', $videoUrl, $vresult )) {
		$urlArray = explode ( '=', $vresult [0] );
		$video_id = trim ( $urlArray [1] );
		$videoUrl = 'http://www.youtube.com/embed/' . $video_id;
		$width = '100%';
		$height = '300';
		// Generate youtube embed code for html5 player
		$output .= '<iframe  type="text/html" width="'.$width.'" height="'.$height.'" src="' . $videoUrl . '" frameborder="0"></iframe>';
	} elseif (strpos ( $videoUrl, 'dailymotion' ) > 0 ) { // For dailymotion videos
		$split = explode ( "/", $videoUrl );
		$split_id = explode ( "_", $split [4] );
		$image_url = '';
		$video = $videoUrl = $previewurl = 'http://www.dailymotion.com/embed/video/' . $split_id [0]; 
		$width = '100%';
		$height = '300';					
		$output .= '<iframe src="' . $video . '?allowed_in_playlists=0" width="'.$width.'" height="'.$height.'"  class="iframe_frameborder" ></iframe>';
		
	} 
	
	
	$show_social_icon = true;
	
	if ($show_social_icon) {
		$blog_title = get_bloginfo ( 'name' );
		$current_url = 'http://' . $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'];
		$output .= '
		<div id="fb-root"></div>
		<script>(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.3&appId=216459485049454";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, \'script\', \'facebook-jssdk\'));</script>
		';
		$fb_url = '<div class="fb-share-button" data-href="'.$current_url.'" data-layout="button_count"></div>';
		$t_url = '<a href="https://twitter.com/share" class="twitter-share-button" data-url="'.$current_url.'">Tweet</a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?\'http\':\'https\';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+\'://platform.twitter.com/widgets.js\';fjs.parentNode.insertBefore(js,fjs);}}(document, \'script\', \'twitter-wjs\');</script>';
		$output .= '
			<div class="video-share floatright">
			<div class="floatleft" style="margin-right: 10px; margin-top: 8px;">' . $fb_url . '</div>
			<div class="floatleft" style="margin-right: 10px; margin-top: 12px;">'.$t_url.'</div>
			<div class="floatleft" style="margin-top:12px;"><script type="text/javascript" src="http://apis.google.com/js/plusone.js"></script><div class="g-plusone" data-size="medium" data-count="false"></div></div>';	
		$output .='</div>';
	}	
	echo $output;
}

?>
