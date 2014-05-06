<?php

/*
Plugin Name: Reexon Client Access Manager
Plugin URI: http://reexon.net
Description: Plugin per la gestione dei shortcode di avada (solo i row)
Version: 0.9
Author: Loris D'Antonio
Author URI: http://reexon.net
License: GPL2
*/

/*
 * tramite wp_enqueue includo script e stili necessari per il corretto funzionamento e visualizzazione
 */
wp_enqueue_script( 'wp-rmac-js',get_site_url()."/wp-content/plugins/rcam/js/wp-rcam-admin.js",array("jquery") );
wp_enqueue_style( 'wp-rmac-css',get_site_url()."/wp-content/plugins/rcam/css/wp-rcam.css" );
wp_enqueue_style( 'wp-bootstrap-css', '//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css', array(), '3.1.1' );
wp_enqueue_style( 'wp-font-awesome-css', '//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css', array(), '4.0.3' );
wp_enqueue_script( 'wp-bootstrap-js',"//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js",array(),'3.1.1' );

include_once 'includes/inc.php';

include 'object/plugin_obj.php';

include 'object/backup_obj.php';

include 'object/ping_obj.php';

?>