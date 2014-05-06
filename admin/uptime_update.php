<?php

/*
 * per poter usufuire del $wpdb
 */
$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require_once( $parse_uri[0] . 'wp-load.php' );

$plugin_dir = dirname(dirname(__FILE__));
include_once $plugin_dir.'/includes/inc.php';



$param_array['ping'] = $_POST['action']=="disable" ? 0 : 1; 

$wpdb->update( 
            TABLE_SITE, 
            $param_array,
                array( 
                        'id_site'       => $_POST['site_id']
                    ), 
                array( '%d' ),
                array( '%d' )
            );

?>