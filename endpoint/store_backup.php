<?php

// provo a salvare in "dati_ricevuti.txt" il file ricevuto
$file = date('h-i-s').".zip";
$site_folder = str_replace("http://","",$_POST['site']);

/*
 * per usufuire delle tabelle del plugin
 */
$plugin_dir = dirname(dirname(__FILE__));
include_once $plugin_dir.'/includes/inc.php';

if($_POST['type'] == BACKUP_DATABASE):
   /*
    * creo directory per inserire i backup
    * TRUE - Indica che creerà le cartelle (se non presenti), in modo ricorsivo
    */
    mkdir(BACKUP_DB_DIR.$site_folder, 0700,TRUE);

    /**
     * @var $file_backup_path
     *          creo la directori
     *          /home/thrcodes/public_html/reexon/..../filename.sql.gz
     * @var $site_folder
     *          contiene il nome del sito , senza il prefisso http:// 
     *          ma solo la parte <dominio> . <estensione>
     * @var $file
     *          semplice nome del backup che è stato passato, di default
     *          non è altro che la data e ora in cui è stato fatto backup
     *          seguito dall'estensione .sql.gz
     */
    $file_backup_path= BACKUP_DB_DIR.$site_folder."/".$file;

else:
      
    //creo directory per inserire i backup
    mkdir(BACKUP_FILE_DIR.$site_folder, 0700,TRUE);
    
    $file_backup_path = BACKUP_FILE_DIR.$site_folder."/".$file;
    
endif;




/*
 * sposto il file caricato, nella cartella del suo sito
 */
if (move_uploaded_file($_FILES['file_dati']['tmp_name'],$file_backup_path))
{
                $data = file($file_backup_path);
            $data = implode('',$data);
            $crc = crc32($data);
    //$crc = hash_file("crc32b", $file_backup_path);
    
    /*
    * produco url assoluto senza sito
    * /wp-content/plugins/rcam/endpoint/filename.sql.gz
    */
    $file_backup_url_abs_path = explode('/reexon',$file_backup_path);
   
    $location = $_SERVER['DOCUMENT_ROOT'];
    include_once ($location . '/wp-config.php');
    
    /*
    * per poter usufuire del $wpdb
    */
   $parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
   require_once( $parse_uri[0] . 'wp-load.php' );
    
    global $wpdb;
    
    $site_id = $wpdb->get_var("SELECT id_site FROM ".TABLE_SITE." WHERE url='".$_POST['site']."'" );
    
    $wpdb->insert( 
            TABLE_BACKUP, 
                array( 
                        'site_id' => $site_id, 
                        'dir' => get_site_url().$file_backup_url_abs_path[1],
                        'type' => $_POST['type'],
                        'crc' => $crc."-".$_POST['crc']
                ), 
                array( 
                        '%d', 
                        '%s',
                        '%s',
                        '%s'
                )
            );
	// se il salvataggio è andato a buon fine
	echo "Dati ricevuti con successo\n";
}
else
{
	// se c'è stato un probela
	echo "ERRORE! Problema nella ricezione dei dati";
}
?>