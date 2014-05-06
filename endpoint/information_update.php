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

$site_url = $_POST['site'];
$wp_version = $_POST['version'];

/*
 * aggiorno le informazioni sul database riguardo al sito in questione.
 */
$wpdb->update(
            TABLE_SITE, 
                array( 'version' => $wp_version), 
                array( 'url' => $site_url ),
                array( '%s' ),
                array( '%s' )
);

?>
