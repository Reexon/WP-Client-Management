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


/*
 * prelevo l'id del backup su cui effettuare azioni
 */
$backup_id = $_POST['backup_id'];

/*
 * controllo l'azione da eseguire con il backup
 */
if ($_POST['action'] == "delete"):

    /*
     * cancello il backup dal database
     */
    $wpdb->delete(TABLE_BACKUP, array( 'id_backup' => $backup_id ), array( '%d' ) );

    else:
        
        if ($_POST['action'] == "delete-all-backup"):
            /*
             * prima di eliminare tutti i backup faccio la select
             * in modo da avere tutti i loro percorsi (che sono memorizzati nel DB)
             */
            $result = $wpdb->get_results("SELECT dir FROM ".TABLE_BACKUP ,ARRAY_N);
        
            foreach ($result as $backup):
                $temp_dir = explode('reexon.net',$backup);
                $abs_dir = $_SERVER['DOCUMENT_ROOT'].$temp_dir[1];
                unlink($abs_dir);
            endforeach;
            /*
             * svuoto la tabella backup
             */
            $wpdb->query( $wpdb->prepare("DELETE FROM ".TABLE_BACKUP) );
        endif;


endif;

    echo "cancellato";
    
?>