<?php

/**
 * Description of backup_obj
 *
 * @author Loris
 */


class backup_obj {
    
    //$site_url
    
    
    public function __construct(){
        add_action( 'init', array($this,'setup_schedule') );
        
    }

    public function setup_schedule(){
        
        if (!wp_next_scheduled('schedule_backup_events')) {
            wp_schedule_event(time(), 'daily', 'schedule_backup_events');
        }
        add_action( 'schedule_backup_events', array($this,'backup_event') );
    }

    public function  backup_event() {
        $plugin_dir = dirname(dirname(__FILE__));
        include_once $plugin_dir.'/includes/inc.php';
        $location = $_SERVER['DOCUMENT_ROOT'];
        include_once ($location . '/wp-config.php');
        
        $data = date('m-d-Y_H-i-s');
        $file = dirname(__FILE__)."/".$data.".sql.gz";
        $mysqldump = "mysqldump --user=".DB_NAME." --password=".DB_PASSWORD." --host=".DB_HOST ." ". DB_NAME ." | gzip -9 -c > ".$file ;
        exec($mysqldump);
        
        /*
         * necessario per poter caricare e utilizzare $wpdb
         */

        global $wpdb;

            $wpdb->insert( 
            TABLE_BACKUP, 
                array( 
                        'site' => get_site_url(), 
                        'dir' => $file 
                ), 
                array( 
                        '%s', 
                        '%s' 
                )
            );   
        //$this->send_backup($file);
    }
        
    /*
     * si occupa di spedire il file di backup sul server di reexon
     */
    public function send_backup($file_path){
            // preparo l'array che conterrà i dati da inviare via POST
            // in questo caso c'è solo il file da trasmettere
            $dati_post['file_dati'] = "@$file_path";

            // inizializzo la sessione CURL
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_HEADER, 0);
            
            // imposto l'URL dello script destinatario
            curl_setopt($ch, CURLOPT_URL, "http://reexon.net/wp-content/plugins/rcam/endpoint/store_backup.php" );

            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: multipart/form-data"));
            
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux i686; rv:6.0) Gecko/20100101 Firefox/6.0Mozilla/4.0 (compatible;)");
            // indico il tipo di comunicazione da effettuare (POST)
            curl_setopt($ch, CURLOPT_POST, true );

            // indico i dati da inviare attraverso POST
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dati_post);

            // specifico che la funzione curl_exec dovrà restituire l'output
            // prodotto dall'URL contattato (destinatario.php)
            // invece di inviarlo direttamente al browser
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // eseguo la connessione e l'invio dei dati e salvo in
            // $postResult l'output prodotto dall'URL contattato
            $postResult = curl_exec($ch);

            // se ci sono stati degli errori mostro un messaggio esplicativo
            if (curl_errno($ch)) {
                    print curl_error($ch);
            }

            // chiudo la sessione CURL
            curl_close($ch);

            // mostro l'output prodotto da destinatario.php
            //echo $postResult;
    }
    
}

$try = new backup_obj();