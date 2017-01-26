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
function fmamg_home_thumbdata($thumImageorder, $where) {
	global $wpdb;
	$prefix = $wpdb->prefix;
	$videoTable = 'zvideo_management';
	$catTable = 
	$query = 'SELECT distinct v.* FROM ' . $prefix . 'zvideo_management v ';
	$query .= 'LEFT JOIN ' . $prefix . 'posts s ON s.ID = v.slug
			WHERE v.published=1 ' . $where . ' GROUP BY v.video_id ORDER BY ' . $thumImageorder;
	return $wpdb->get_results ( $query );
}


function fmamg_home_featured_thumbdata($thumImageorder, $where) {
	global $wpdb;
	$prefix = $wpdb->prefix;
	$videoTable = 'zvideo_management';
	$catTable = 
	$query = 'SELECT distinct v.* FROM ' . $prefix . 'zvideo_management v ';
	$query .= 'LEFT JOIN ' . $prefix . 'posts s ON s.ID = v.slug
			WHERE v.published=1 AND v.featured=1' . $where . ' GROUP BY v.video_id ORDER BY ' . $thumImageorder;
	return $wpdb->get_results ( $query );
}

/**
 * Get video  link
 */
 function fmamg_get_video_permalink( $postid ) {

	global $wp_rewrite;
	$link = $wp_rewrite->get_page_permastruct();					## check whether permalink enabled or not
	$video_details = get_post( $postid );			
	if ( ! empty( $link ) ) {		## Return SEO video URL if permalink enabled
		return get_site_url() . '/' . $video_details->post_type . '/' . $video_details->post_name . '/';
	} else {					## Return Non SEO video URL if permalink disabled
		return $video_details->guid;
	}
}

function fmamg_allVideos() {
	global $wpdb;
	$zgallery_settings = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix. "zvideo_settings");
	$zsettings = array();
	foreach($zgallery_settings as $zgallery_setting){
		$zsettings[$zgallery_setting->setting_key] = $zgallery_setting->setting_value;
	}
	$where = '';
	$thumImageorder = 'v.video_id DESC';
	$TypeOFvideos   = fmamg_home_thumbdata( $thumImageorder, $where );
	$featured_videos  = fmamg_home_featured_thumbdata( $thumImageorder, $where );

	if ( ! empty( $featured_videos ) ) { ?>

	<div class="media_gallery_slider">
    	<h3><?php echo $zsettings['video_slider_title'] ?> - <span><?php echo $zsettings['video_slider_subtitle'] ?></span></h3>
    	<div class="container-carousel">
    		<div id="owl-demo" class="owl-carousel owl-theme">
    			
				<?php 
					$videolist = 0;
					foreach ( $featured_videos as $video ) {
						$imageFea[$videolist]    = $video->video_image;
						$file_type       = $video->video_type;	
						$guid[$videolist]  = fmamg_get_video_permalink( $video->slug );		
						if ( $imageFea[$videolist] == '' ) {							
							$imageFea[$videolist] = '';
						} else {
							
							
							$imageFea[$videolist] = $imageFea[$videolist];
							
						}
						$vidF[$videolist]      = $video->video_id;
						$nameF[$videolist]     = $video->video_title;
						$videolist++;
					}
				for ( $videolist = 0; $videolist < count( $TypeOFvideos ); $videolist++ ) { ?>
					
					<div class="item">
			          <div class="video"> 
			          	<a href="<?php echo $guid[$videolist]; ?>">
							<img src="<?php echo $imageFea[$videolist]; ?>" alt="<?php echo $nameF[$videolist]; ?>" title="<?php echo $nameF[$videolist]; ?>" />
						</a>
			          </div>
			        </div>

				<?php } ?>

    		</div>
    	</div>
  	</div>

	<?php } ?>
	
	<?php 
	if ( ! empty( $TypeOFvideos ) ) { ?>

		<input type='hidden' id='current_page' />
		<input type='hidden' id='show_per_page' />
		<div class="media_gallery_grid clearfix">
			<h3><?php echo $zsettings['video_gallery_title'] ?> - <span><?php echo $zsettings['video_gallery_subtitle'] ?></span></h3>
			<div class="grid clearfix">
				<ul id="content" >
					<?php 
						$videolist = 0;
						foreach ( $TypeOFvideos as $video ) {
							$imageFea[$videolist]    = $video->video_image;
							$file_type       = $video->video_type;	
							$guid[$videolist]  = fmamg_get_video_permalink( $video->slug );		
							if ( $imageFea[$videolist] == '' ) {							
								$imageFea[$videolist] = '';
							} else {
								
								
								$imageFea[$videolist] = $imageFea[$videolist];
								
							}
							$vidF[$videolist]      = $video->video_id;
							$nameF[$videolist]     = $video->video_title;
							$videolist++;
						}
						
						for ( $videolist = 0; $videolist < count( $TypeOFvideos ); $videolist++ ) { ?>

							<li>
								<a href="<?php echo $guid[$videolist]; ?>">
									<img src="<?php echo $imageFea[$videolist]; ?>" alt="<?php echo $nameF[$videolist]; ?>" title="<?php echo $nameF[$videolist]; ?>" />
								</a>
								<h4>
									<a href="<?php echo $guid[$videolist]; ?>">
										<?php if ( strlen( $nameF[$videolist] ) > 10 ) {
											echo substr( $nameF[$videolist], 0, 10 ) . '..';
										} else {
											echo  $nameF[$videolist];
										} ?>
									</a> 
									<a href="<?php echo $guid[$videolist]; ?>"><img src="<?php echo plugins_url( 'images/video_icon.png', dirname( __FILE__ ) ); ?>" alt=""></a>
								</h4>
							</li>

						<?php }
					?>
				</ul>
				
			</div>

			
		</div>
		<div id='page_navigation'></div>

	<?php }
}

?>

<script type="text/javascript">
jQuery(document).ready(function(){
	
	//how much items per page to show
	var show_per_page = '<?php echo $zsettings["per_page_videos"] ?>'; 
	//getting the amount of elements inside content div
	var number_of_items = jQuery('#content ul').children().size();
	//calculate the number of pages we are going to have
	var number_of_pages = Math.ceil(number_of_items/show_per_page);

	if(number_of_pages > 1) {
	
		//set the value of our hidden input fields
		jQuery('#current_page').val(0);
		jQuery('#show_per_page').val(show_per_page);
		
		//now when we got all we need for the navigation let's make it '
		
		/* 
		what are we going to have in the navigation?
			- link to previous page
			- links to specific pages
			- link to next page
		*/
		var navigation_html = '<a class="previous_link" href="javascript:previous();">Prev</a>';
		var current_link = 0;
		while(number_of_pages > current_link){
			navigation_html += '<a class="page_link" href="javascript:go_to_page(' + current_link +')" longdesc="' + current_link +'">'+ (current_link + 1) +'</a>';
			current_link++;
		}
		navigation_html += '<a class="next_link" href="javascript:next();">Next</a>';
		
		jQuery('#page_navigation').html(navigation_html);
		
		//add active_page class to the first page link
		jQuery('#page_navigation .page_link:first').addClass('active_page');
		
		//hide all the elements inside content div
		jQuery('#content ul').children().css('display', 'none');
		
		//and show the first n (show_per_page) elements
		jQuery('#content ul').children().slice(0, show_per_page).css('display', 'block');
	}
	
});

function previous(){
	
	new_page = parseInt(jQuery('#current_page').val()) - 1;
	//if there is an item before the current active link run the function
	if(jQuery('.active_page').prev('.page_link').length==true){
		go_to_page(new_page);
	}
	
}

function next(){
	new_page = parseInt(jQuery('#current_page').val()) + 1;
	//if there is an item after the current active link run the function
	if(jQuery('.active_page').next('.page_link').length==true){
		go_to_page(new_page);
	}
	
}
function go_to_page(page_num){
	//get the number of items shown per page
	var show_per_page = parseInt(jQuery('#show_per_page').val());
	
	//get the element number where to start the slice from
	start_from = page_num * show_per_page;
	
	//get the element number where to end the slice
	end_on = start_from + show_per_page;
	
	//hide all children elements of content div, get specific items and show them
	jQuery('#content ul').children().css('display', 'none').slice(start_from, end_on).css('display', 'block');
	
	/*get the page link that has longdesc attribute of the current page and add active_page class to it
	and remove that class from previously active page link*/
	jQuery('.page_link[longdesc=' + page_num +']').addClass('active_page').siblings('.active_page').removeClass('active_page');
	
	//update the current page input field
	jQuery('#current_page').val(page_num);
}
  
</script>
<style>
#page_navigation {
width: auto;
float: right;
margin-top: 15px;
}
#page_navigation a{
	padding:5px 10px;
	border:1px solid #e1e1e1;
	margin:2px;
	color:#666;
	text-decoration:none;
	font-family: NS_med,Arial,Helvetica,sans-serif;
	font-size: 12px;
}
.active_page{
	background:#bc0000;
	color:white !important;
}
</style>

