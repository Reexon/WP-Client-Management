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
$table_column = array ('site','update_date','recurrence_backup','remote_backup','local_backup','mail_backup',
                        'recurrence_backup_file','remote_backup_file','local_backup_file','mail_backup_file');

//prelevo nome del sito che ha fatto richiesta

$sito = $_POST['site'];

$sql = "SELECT * FROM ". TABLE_SCHEDULE ." JOIN ".TABLE_SITE ." ON id_site = site_id WHERE url = '$sito'";

$backup_schedule = $wpdb->get_row($sql,ARRAY_A);

/*
 * se è diverso da null allora la query ha restituito qualche risultato, quindi lo esaminiamo
 */
 
if ($backup_schedule != null){
    
    $schedule_backup = array() ;
    for( $i = 1 ; $i < count($table_column) ; $i++ ) {

        $schedule_backup[$table_column[$i]] = $backup_schedule[$table_column[$i]]; 
    }

    //prelevo i poteri che l'amministratore di quel sito deve avere
    $json = json_encode( $schedule_backup );

    echo $json;
}