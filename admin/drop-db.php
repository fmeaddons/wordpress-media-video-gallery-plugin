<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
global $wpdb;
$sql = "DROP TABLE " . $wpdb->prefix .'zvideo_management';
$wpdb->query($sql);

$sql1 = "DROP TABLE " . $wpdb->prefix .'zvideo_settings';
$wpdb->query($sql1);
?>
