<?php
$plugin_dir = dirname(dirname(__FILE__));
include_once $plugin_dir.'/includes/inc.php';

$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require_once( $parse_uri[0] . 'wp-load.php' );

global $wpdb;
$table_column = array ('active','url','article','media','pages','comments','portfolio',
                        'themes','plugins','users','tools','settings','updates');

//prelevo nome del sito che ha fatto richiesta

$sito = $_POST['site'];


$sql = "SELECT * FROM ".TABLE_ACCESS." JOIN ".TABLE_SITE ." ON id_site = site_id WHERE url = '$sito'";

$site_permission = $wpdb->get_row($sql,ARRAY_A);

/*
 * se è diverso da null allora la query ha restituito qualche risultato, quindi lo esaminiamo
 */
 
if ($site_permission != null){
    
    $array_permission = array() ;
    
    /*
     * l'indice parte da 1 , per saltare $table_column[0] che contiene url del sito.
     */
    for( $i = 0 ; $i < count($table_column) ; $i++ ) {
        
        /*
         * a questo punto creo l'array associativo dei permessi consentiti e non
         * $array_permission[article] = 0
         * $array_permission[media] = 0
         */
        $array_permission[$table_column[$i]] = $site_permission[$table_column[$i]];
        
    }

    //prelevo i poteri che l'amministratore di quel sito deve avere
    $json = json_encode( $array_permission );

    echo $json;
}