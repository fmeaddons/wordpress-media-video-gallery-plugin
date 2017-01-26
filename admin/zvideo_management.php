<?php 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>
<?php include_once( plugin_dir_path( __FILE__ ) . 'lib/videos.php' );	?>
<script type="text/javascript">
    function conf(str) {
       if(confirm("Are you sure you want delete") == true){ location.replace(str);}
    }
</script>
<div class="wrap">
<?php if(!isset($_GET['add']) && $_GET['add']!= 1){ ?>
	<h2>Manage Videos <a href="admin.php?page=zvideo_management&add=1" class="add-new-h2">Add New</a></h2>
	<form action="" method="post">
	<p class="search-box">
		<label class="screen-reader-text" for="user-search-input">Search:</label>
		<input type="search" id="user-search-input" name="s" value="">
		<input type="submit" name="" id="search-submit" class="button button-primary button-large" value="Search"></p>
	</form> 
<?php }else{ 
		if(isset($_GET['video_id']) && $_GET['video_id']!= ''){?>	
		<h2>Edit Video Details</h2>
		<?php }else{ ?>
		<h2>Add New Video</h2>
		<?php } ?>
<?php } ?>

<?php 

function fmamg_hd_getyoutubepage( $url ) {
$apidata = wp_remote_get($url);
return wp_remote_retrieve_body($apidata);
}

function fmamg_hd_getsingleyoutubevideo( $youtube_media ) {
if ( $youtube_media == '' ) {
return;
}


$url = 'https://www.googleapis.com/youtube/v3/videos?id='.$youtube_media.'&part=contentDetails,snippet,statistics';
$video_details =  fmamg_hd_getyoutubepage( $url ); 
$decoded_data = json_decode($video_details);
return get_object_vars($decoded_data); 

}
	
	
	global $wpdb;
	$title = 'Video';
	$action = "admin.php?page=zvideo_management";
	
	$table_name = $wpdb->prefix . "zvideo_management";
	

	if(isset($_GET['video_id']) && $_GET['video_id'] != '') {
		$getvideo_id = intval($_GET['video_id']);
	} else {
		$getvideo_id = 0;
	}
	
	if(isset($_POST['check_sub']) && $_POST['check_sub'] == 1){

		if ( !current_user_can( apply_filters( 'fmamediagallery_capability', 'manage_options' ) ) )
            die( '-1' );
    
        check_admin_referer( 'fmamediagallery_nonce_action', 'fmamediagallery_nonce_field' );
		global $wpdb;

		 if($_POST['video_id'] != ''){
			 $pre = ' updated';
		 }else{
			/*
			**	Video Add Code
			*/
			$videoName = $video_slug = filter_input( INPUT_POST, 'video_title' );
			$videoDescription    = filter_input( INPUT_POST, 'video_description' );
			$videoLinkurl		 = filter_input( INPUT_POST, 'video_url' );
			$sorder				 = $act_playlist = '';
			if ( ! empty( $_POST['video_cats'] ) ) {
				$video_cats = $_POST['video_cats'];
			}

			$videoFeatured    = filter_input( INPUT_POST, 'featured_video' );
			$videoDate        = date( 'Y-m-d H:i:s' );
			
			
			
			$ordering       = $wpdb->get_var( 'SELECT count( ordering ) FROM ' . $table_name );
			$videoPublish   = filter_input( INPUT_POST, 'publish_video' );
			$video_added_method = filter_input(INPUT_POST, 'video_type');
			
			if ( $videoLinkurl != '' ) {
				if ( preg_match( '#https?://#', $videoLinkurl ) === 0 ) {
					$videoLinkurl = 'http://' . $videoLinkurl;
				}
				$act_filepath = addslashes( trim( $videoLinkurl ) );
				$file_type    = '2';
			
				if ( strpos( $act_filepath, 'youtube' ) > 0 ) {
					$imgstr     = explode( 'v=', $act_filepath );
					$imgval     = explode( '&', $imgstr[1] );
					$match      = $imgval[0];
					$previewurl = 'http://img.youtube.com/vi/' . $imgval[0] . '/hqdefault.jpg';
					$img        = 'http://img.youtube.com/vi/' . $imgval[0] . '/hqdefault.jpg';
					$act_image  = $img;
					$act_opimage = $previewurl;
				} else if ( strpos( $act_filepath, 'dailymotion' ) > 0 ) {	## check video url is dailymotion
					$split     = explode( '/', $act_filepath );
					$split_id  = explode( '_', $split[4] );
					$img = $act_imgage = $act_opimage = $previewurl = 'http://www.dailymotion.com/thumbnail/video/' . $split_id[0];
					$file_type = '2';
				} 
			} 
			
		
			
			$act_link      = $act_hdpath = $act_name = $act_opimage = '';

			if ( ! empty( $act_filepath ) ) {
				if ( strpos( $act_filepath, 'youtube' ) > 0  ) {
					if ( strpos( $act_filepath, 'youtube' ) > 0 ) {
						$imgstr = explode( 'v=', $act_filepath );
						$imgval = explode( '&', $imgstr[1] );
						$match  = $imgval[0];
					} 
			
					$act_image    = 'http://i3.ytimg.com/vi/' . $match . '/hqdefault.jpg';
					$act_opimage  = 'http://i3.ytimg.com/vi/' . $match . '/hqdefault.jpg';
					$youtube_data = fmamg_hd_getsingleyoutubevideo( $match );
					if ( $youtube_data ) {
						if ( $act_name == '' )
							$act_name = addslashes( $youtube_data['title'] );
						if ( $act_image == '' )
							$act_image = 'http://i3.ytimg.com/vi/' . $youtube_data['id'] . '/hqdefault.jpg';
						if ( $act_link == '' )
							$act_link = $act_filepath;
						$file_type = '1';
					}
			
					else{
						$this->render_error( __( 'Could not retrieve Youtube video information') );
					}
				}else if ( strpos( $act_filepath, 'dailymotion' ) > 0 ) {			  ## check video url is dailymotion
					$split     = explode( '/', $act_filepath );
					$split_id  = explode( '_', $split[4] );
					$act_image = $act_opimage = 'http://www.dailymotion.com/thumbnail/video/' . $split_id[0];
					$file_type = '1';
				} 
			} else {
				if ( $video1 != '' )
					$act_filepath = $video1;
				if ( $video2 != '' )
					$act_hdpath = $video2;
				if ( $img1 != '' )
					$act_image = $img1;
				if ( $img2 != '' )
					$act_opimage = $img2;
			}
			
			
			$user_id = get_current_user_id();
			$videoData = array(
				'video_title'				=> $videoName,
				'video_description'		=> $videoDescription,
				'video_file'			=> $act_filepath,
				'video_type'			=> $video_added_method,
				'video_image'					=> $act_image,
				'video_url'					=> $videoLinkurl,
				'featured'			=> $videoFeatured,
				'published'			=> $videoPublish,
				'admin_id'			=> $user_id,
			);     				
			$videoData['post_date'] = $videoDate;	
			$videoData['ordering']  = $ordering;
			$videoData['slug']      = '';
			
			//echo "<pre>";print_r($videoData);exit;
			
			fmamg_insert_video( $videoData);
			
			$pre = ' added';
		 }
		 echo '<div class="updated below-h2"><p>'.$title. $pre.' succesfully</p></div>';
	}
	
	if(isset($_GET['video_id']) && $_GET['video_id'] != ''){
		global $wpdb;
		$query_chk = "SELECT * FROM ".$table_name." where video_id = '".intval($_GET['video_id'])."'";
		$query_chk_list = $wpdb->get_row( $query_chk, ARRAY_A );

		//$category_name = $query_chk_list[0][$field_name];
		
		$editaction = '&video_id='.intval($_GET['video_id']);	
		$btn = 'Update';
		$action = "admin.php?page=zvideo_management&add=1".$editaction;
	}

	if(isset($_POST['v_id']) && $_POST['v_id']!='') {

		if ( !current_user_can( apply_filters( 'fmamediagallery_capability', 'manage_options' ) ) )
            die( '-1' );
    
        check_admin_referer( 'fmamediagallery_nonce_action', 'fmamediagallery_nonce_field' );

		global $wpdb;

		$res2 = $wpdb->query("UPDATE ".$table_name." set video_title = '".sanitize_text_field($_POST['video_title'])."', video_description = '".sanitize_text_field($_POST['video_description'])."', featured = '".sanitize_text_field($_POST['featured_video'])."', published = '".sanitize_text_field($_POST['publish_video'])."' WHERE video_id = ".intval($_POST['v_id']));

		

	}
	
	if($_GET['del_id'] != ''){
		if ( !current_user_can( apply_filters( 'fmamediagallery_capability', 'manage_options' ) ) )
			die( '-1' );
			$retrieved_nonce = $_REQUEST['_fmamediagallerywpnonce'];
			if (!wp_verify_nonce($retrieved_nonce, 'delete_my_rec' ) ) die( 'Failed security check' );
		global $wpdb;
		$res = $wpdb->query("delete from ".$table_name."  where video_id = '".intval($_GET['del_id'])."'");
		echo '<p style="color:green">Deleted Successfully</p>';
	}
	
		if($_GET['add'] == 1){
	?>
<div id="poststuff">
	<div class="stuffbox" style="padding:15px 0 15px 15px;">
    <form action="<?php echo $action;?>" method="post" enctype="multipart/form-data" name="video_upload_form" id="video_upload_form">
	<input type="hidden" name = "v_id" value="<?php echo intval($_GET['video_id']); ?>" />
	<?php wp_nonce_field('fmamediagallery_nonce_action','fmamediagallery_nonce_field'); ?>
	<table class="form-table">
	<tbody>
		<?php if($getvideo_id == 0) { ?>
		<tr valign="top">
			<th scope="row"><label for="video_type">Video Type</label></th>
			<td>
				<select name="video_type" id="video_type">
					<option value="2" <?php selected(1, esc_attr($query_chk_list['video_type'])); ?>>Youtube URL</option>
					<option value="3" <?php selected(2, esc_attr($query_chk_list['video_type'])); ?>>Dailymotion URL</option>
				</select>
			</td>
		</tr>
		<?php } ?>
		
		
		<?php if($getvideo_id == 0) { ?>
		<tr id="id_url1">
			<th>Video URL</th>
			<td><input type="text" name="video_url" id="video_url" value="<?php echo esc_attr($query_chk_list['video_url']); ?>" style="width:80%;" /></td>
		</tr>
		<?php } ?>
		
		<tr>
			<th>Video Title</th>
			<td><input type="text" name="video_title" id="video_title" value="<?php echo esc_attr($query_chk_list['video_title']); ?>" /></td>
		</tr>
		<tr>
			<th>Video Description</th>
			<td><?php $video_description = $query_chk_list['video_description']; $settings = array( 'media_buttons' => false, 'textarea_rows' => 5 ); wp_editor( $video_description, 'video_description', $settings ); ?></td>
		</tr>
		
		<tr>
			<th>Featured Video</th>
			<td><input <?php checked(1, esc_attr($query_chk_list['featured'])); ?> type="radio" name="featured_video" id="featured_video" value="1" checked="checked" /> Yes <input <?php checked(0, $query_chk_list['featured']); ?> type="radio" name="featured_video" id="featured_video" value="0" /> No</td>
		</tr>
		
		<tr>
			<th>Publish Video</th>
			<td><input <?php checked(1, esc_attr($query_chk_list['published'])); ?> type="radio" name="publish_video" id="publish_video" value="1" checked="checked" /> Yes <input <?php checked(0, $query_chk_list['published']); ?> type="radio" name="publish_video" id="publish_video" value="0" /> No</td>
		</tr>
		</div>
	<input name="video_id" type="hidden" id="video_id" value="<?php echo $_GET['video_id']?>" class="regular-text code">
	<input name="check_sub" type="hidden" id="check_sub" value="1" class="regular-text code">
	</tbody>
	</table>

	<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary button-large" value="<?php echo $btn?$btn:'Add'; ?> <?php echo $title?>"></p>	
	</form>
	</div>
</div>
<?php				
		}
	?>

<?php if(!isset($_GET['add']) && $_GET['add']!= 1){ ?>
   
   <?php
   		global $wpdb;
   		
		$pagenum = isset( $_GET['paged'] ) ? absint( $_GET['paged'] ) : 0;
		if ( empty( $pagenum ) )
			$pagenum = 1;
	
		$per_page = (int) get_user_option( 'ms_users_per_page' );
		if ( empty( $per_page ) || $per_page < 1 )
			$per_page = 15;
	
		$per_page = apply_filters( 'ms_users_per_page', $per_page );

		if($_GET['orderby'] != '' && $_GET['order'] != ''){
			$orderby = 'order by '.$_GET['orderby'].' '.$_GET['order'];	
			if($_GET['order'] == 'asc'){
				$actionOrder = 'admin.php?page=zvideo_management&orderby=video_title&amp;order=desc';
			}
			if($_GET['order'] == 'desc'){
				$actionOrder = 'admin.php?page=zvideo_management&orderby=video_title&amp;order=asc';
			}
		}else{
			$orderby = 'order by video_id desc';	
			$actionOrder = 'admin.php?page=zvideo_management&orderby=video_title&amp;order=asc';	
		}
		
		$where = '';
		if(trim($_POST['s']) != ''){
			$where = "where ".$field_name." like '%".$_POST['s']."%' ";
		}
		
		$query = "SELECT * FROM ".$wpdb->prefix."zvideo_management ".$where.$orderby;
		
		$total = $wpdb->get_var( str_replace( 'SELECT *', 'SELECT COUNT(video_id)', $query ) );

		$query .= " LIMIT " . intval( ( $pagenum - 1 ) * $per_page) . ", " . intval( $per_page );
	
		$galleries_list = $wpdb->get_results( $query, ARRAY_A );
		
		$num_pages = ceil( $total / $per_page );
		$page_links = paginate_links( array(
			'base' => add_query_arg( 'paged', '%#%' ),
			'format' => '',
			'end_size'     => 1,
			'mid_size'     => 9,
			'prev_text' => __( '&laquo;' ),
			'next_text' => __( '&raquo;' ),
			'total' => $num_pages,
			'current' => $pagenum
		));
   ?> 
   <?php if ( $page_links ) { ?>
      <div class="tablenav-pages">
        <?php $page_links_text = sprintf( '<span class="displaying-num">' . __( 'Displaying %s&#8211;%s of %s' ) . '</span>%s',
			number_format_i18n( ( $pagenum - 1 ) * $per_page + 1 ),
			number_format_i18n( min( $pagenum * $per_page, $total ) ),
			number_format_i18n( $total ),
			$page_links
			); echo $page_links_text; ?>
      </div>
      <?php } ?>

<table class="wp-list-table widefat fixed users" cellspacing="0">
	<thead>
		<tr>
        <th scope="col" id="galleryid" class="manage-column column-galleryid sortable desc" style="">
        <span style="padding: 10px;">ID</span>
        </th>
        <th scope="col" id="galleryname" class="manage-column column-galleryname sortable desc" style="">
        <a href="<?php echo $actionOrder?>"><span>Title</span><span class="sorting-indicator"></span></a>
        </th>
		<th scope="col" id="galleryauth" class="manage-column column-galleryauth sortable desc" style="">
        <span>Author</span>
        </th>
		<th scope="col" id="galleryfeature" class="manage-column column-galleryfeature sortable desc" style="">
        <span>Featured</span>
        </th>
		<th scope="col" id="gallerydate" class="manage-column column-gallerydate sortable desc" style="">
        <span>Date</span>
        </th>
		<th scope="col" id="actions" class="manage-column column-counter" style="">
        <span>Actions</span>
        </th>
		</tr>
	</thead>

	<tfoot>
		<tr>
        <th scope="col" id="galleryid" class="manage-column column-galleryid sortable desc" style="">
        <span style="padding: 10px;">ID</span>
        </th>
        <th scope="col" id="galleryname" class="manage-column column-galleryname sortable desc" style="">
        <a href="<?php echo $actionOrder?>"><span>Title</span><span class="sorting-indicator"></span></a>
        </th>
		<th scope="col" id="galleryauth" class="manage-column column-galleryauth sortable desc" style="">
        <span>Author</span>
        </th>
		<th scope="col" id="galleryfeature" class="manage-column column-galleryfeature sortable desc" style="">
        <span>Featured</span>
        </th>
		<th scope="col" id="gallerydate" class="manage-column column-gallerydate sortable desc" style="">
        <span>Date</span>
        </th>
		<th scope="col" id="actions" class="manage-column column-counter" style="">
        <span>Actions</span>
        </th>
		</tr>
	</tfoot>

	<tbody id="the-list" data-wp-lists="list:user">
	<?php 
	if(!empty($galleries_list)){
		$my_nonce = wp_create_nonce('delete_my_rec');
		 $i= 1;
		foreach($galleries_list as $_galleries_list){
			$class = 'alternate';
			if($i%2)
				$class='';
			
			$featured = 'No';
			if($_galleries_list['featured'])
				$featured = 'Yes';
	?>
	<tr id="user-<?php echo $_galleries_list['video_id']?>" class="<?php echo $class; ?>">
		<td class="username column-username"><?php echo $_galleries_list['video_id']?></td>
		<td class="username column-username"><a href="admin.php?page=zvideo_management&add=1&video_id=<?php echo $_galleries_list['video_id']?>"><?php echo $_galleries_list['video_title']?></a></td>
		<td class="username column-username"><?php echo get_the_author_meta( 'display_name', $_galleries_list['admin_id'] ); ?></td>
		<td class="username column-username"><?php echo $featured; ?></td>
		<td class="username column-username"><?php echo $_galleries_list['post_date']?></td>
		<td class="username column-username"><a href="admin.php?page=zvideo_management&add=1&video_id=<?php echo $_galleries_list['video_id']?>">Edit</a> | <a href="#" onclick="conf('admin.php?page=zvideo_management&del_id=<?php echo $_galleries_list['video_id']?>&_fmamediagallerywpnonce=<?php echo $my_nonce ?>')" >Delete</a>
	</td>
    </tr>	
    <?php $i++;}}else{ ?>
   <tr id="user-1" class="alternate"><td colspan="6"> No record found</td></tr>
    <?php }
	wp_reset_query();
	?>
    </tbody>
</table>

<?php } ?>

