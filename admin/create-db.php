<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
global $wpdb;

$sql = "DROP TABLE IF EXISTS `" . $wpdb->prefix . "zvideo_settings`";
$wpdb->query($sql);

$sql1 = "CREATE TABLE " . $wpdb->prefix . "zvideo_settings (
		`setting_id` int(11) unsigned NOT NULL auto_increment,  
		`setting_key` varchar(100) NOT NULL,                    
		`setting_value` text NOT NULL,                          
		PRIMARY KEY  (`setting_id`)                             
	  ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8";
$wpdb->query($sql1);

$sql = "DROP TABLE IF EXISTS `" . $wpdb->prefix . "zvideo_management`";
$wpdb->query($sql);

$sql2 = "CREATE TABLE " . $wpdb->prefix . "zvideo_management (
			`video_id` int(11) NOT NULL auto_increment,            
			`video_title` varchar(255) default NULL,               
			`video_description` text,                              
			`video_file` mediumtext,                               
			`video_type` tinyint(1) default NULL,                  
			`published` tinyint(4) default NULL,                   
			`video_image` varchar(255) default NULL,               
			`video_url` varchar(255) default NULL,                 
			`featured` tinyint(4) default NULL,                    
			`post_date` date default NULL,                         
			`ordering` smallint(6) default NULL,                   
			`slug` varchar(255) default NULL,                      
			`admin_id` int(11) default NULL,                       
			`hitcount` int(11) default NULL,                       
			PRIMARY KEY  (`video_id`)                              
			) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1";
$wpdb->query($sql2);



$settings = array();

$settings["video_slider_title"] = "Featured Videos";
$settings["video_slider_subtitle"] = "Media Gallery Slider";
$settings["video_gallery_title"] = "Media Gallery";
$settings["video_gallery_subtitle"] = "Grid";
$settings["per_page_videos"] = 16;


foreach ($settings as $val => $innerKey)
{
    $wpdb->query
    (
        $wpdb->prepare
        (
            "INSERT INTO " . $wpdb->prefix . "zvideo_settings (setting_key, setting_value) VALUES(%s, %s)",
            $val,
            $innerKey
        )
    );
}

// Creating the pages for the videohome

$postH = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'posts WHERE post_content="[videohome]"' );
if ( empty( $postH ) ) {

	$contus_home = 'INSERT INTO ' . $wpdb->prefix . 'posts( `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count` )
					VALUES
					( 1, NOW(), NOW(), "[videohome]", "Videos", "", "publish", "open", "open", "", "video-home", "", "", "2011-01-10 10:42:06",
					"2011-01-10 10:42:06", "",0, "'.$site_url.'/?page_id=",0, "page", "", 0 )';

	$wpdb->query( $contus_home );
	$homeId  = $wpdb->get_var( 'SELECT ID FROM ' . $wpdb->prefix . 'posts ORDER BY ID DESC LIMIT 0,1' );
	$homeUpd = 'UPDATE ' . $wpdb->prefix . 'posts SET guid="'.$site_url.'/?page_id='.$homeId.'" WHERE ID="'.$homeId.'"';
	$wpdb->query( $homeUpd );
			// Save video home page id.
			$sql_more_id = "INSERT INTO ".$wpdb->prefix."options (`option_name`,`option_value`,`auto_load`) VALUES ('video_more_id','".$homeId."','yes')";
			$wpdb->query($sql_more_id);
			
}

?>
