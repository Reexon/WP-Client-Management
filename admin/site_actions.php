<?php
/*
 * per poter usufuire del $wpdb
 */
$parse_uri = explode('wp-content', $_SERVER['SCRIPT_FILENAME']);
require_once( $parse_uri[0] . 'wp-load.php' );

global $wpdb;

/*
 * per poter usufruire nome tabelle
 */
$plugin_dir = dirname(dirname(__FILE__));
include_once $plugin_dir . '/includes/inc.php';

if($_POST['action'] == "enable")
    $action = 1;
else if($_POST['action'] == "disable")
    $action = 0;

$site_id = $_POST['site_id'];

$result = $wpdb->update(
        TABLE_SITE,
        array('active' => $action),
        array('id_site' => $site_id),
        array( '%d' ),
        array( '%d')
        );

/*
 * $result conterrà il numero di row modificate, controllo se almeno 1 riga è stata modificata
 */
if($result > 0)
    echo $action == 1 ? "attivato" : "bloccato";
else
    echo "gnagna";
?>

