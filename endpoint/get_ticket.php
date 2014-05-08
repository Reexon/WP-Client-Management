<?php

/*
 * per usufuire delle tabelle del plugin
 */
$plugin_dir = dirname(dirname(__FILE__));
include_once $plugin_dir.'/includes/inc.php';

/*
 * per poter usufuire del $wpdb
 */
$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require_once( $parse_uri[0] . 'wp-load.php' );

global $wpdb;

if(isset($_GET['site'])){
    $site = "http://".$_GET['site'];
    $query = "SELECT * FROM ".TABLE_TICKET . " "
            . "JOIN ".TABLE_SITE ." on site_id = id_site "
            . "JOIN ".TABLE_TICKET_CATEGORY ." ON id_category = category_id "
            . "WHERE url='http://finishes.it' LIMIT 20";
    
    $result = $wpdb->get_results($query,ARRAY_A);
    echo json_encode($result);
}
?>