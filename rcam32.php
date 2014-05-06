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

class rcam{
    public function __construct(){
        add_action( 'admin_menu', array( $this , 'add_admin_menu') );
        
        
        add_action( 'wp', array($this,'setup_schedule') );
        add_action( 'schedule_backup_events', array($this,'backupevent') );
        
        register_deactivation_hook( __FILE__,   array( $this , 'deactivate') );
        register_activation_hook(   __FILE__,   array( $this , 'activate'  ) );
        register_uninstall_hook(    __FILE__ ,  array( $this , 'uninstall' ) );
    }
    
    public function activate(){
        $this->createTable();
    }
    
    public function deactivate(){
        wp_clear_scheduled_hook( 'schedule_backup_events' );
    }
    
    public function uninstall(){
        $this->dropTable();
    }
    
    public function setup_schedule(){
        if (!wp_next_scheduled('schedule_backup_events')) {
            wp_schedule_event(time(), 'minutely', 'schedule_backup_events');
        }
    }
    
    public function  backupevent() {
        $location = $_SERVER['DOCUMENT_ROOT'];
        include ($location . '/wp-config.php');
        
        $data = date('m-d-Y_H-i-s');
        $file = dirname(__FILE__)."/".$data.".sql.gz";
        $mysqldump = "mysqldump --user=".DB_NAME." --password=".DB_PASSWORD." --host=".DB_HOST ." ". DB_NAME ." | gzip -9 -c > ".$file ;
        exec($mysqldump);
        
        
        global $wpdb;

            $wpdb->insert( 
            'rcam_backup', 
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
        
    public function send_backup($file_path){
            // preparo l'array che conterrà i dati da inviare via POST
            // in questo caso c'è solo il file da trasmettere
            $dati_post['file_dati'] = "@$file_path";

            // inizializzo la sessione CURL
            $ch = curl_init();

            // imposto l'URL dello script destinatario
            curl_setopt($ch, CURLOPT_URL, "http://reexon.net/wp-content/plugins/rcam/endpoint/store_backup.php" );

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
    
    /*
     * Creazione delle tabelle
     */
    private function createTable(){
        global $wpdb;
        $column = array ('article','media','pages','comments','portfolio','faq',
                        'themes','plugins','users','tools','settings','update');
        $sql = "CREATE TABLE rcam_access (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                site VARCHAR(60) NOT NULL,";
            foreach($column as $s):
                $sql .= "$s boolean DEFAULT false NOT NULL, ";
            endforeach;
                $sql .= "PRIMARY KEY id (id) );";
                
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
        
    }
    
    /*
     * il metodo viene richiamato solo in caso di disinstallazione del plugin
     * si occupa di droppare completamente il database.
     */
    private function dropTable(){
        global $wpdb;
        $sql = "DROP TABLE rcam;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }
    
    public function add_admin_menu(){
        add_menu_page( 'RCAM', 'RCAM', 'administrator', 'client-permission');
        add_submenu_page( 'client-permission', 'Permessi', 'Permessi', 'administrator', 'client-permission', array ( $this, 'show_permission' ) ); 
        add_submenu_page( 'client-permission', 'Test', 'Test', 'administrator', 'test-permission', array ( $this, 'test' ) ); 
        add_submenu_page( 'client-permission', 'Test backup', 'Test backup', 'administrator', 'client-backup', array ( $this, 'backup_db' ) ); 
        add_submenu_page( 'client-permission', 'Menu Tab', 'menu tab', 'administrator', 'menu-tab', array ( $this, 'menu_tab' ) ); 
    }
    
    public function menu_tab(){
        include 'admin/tab_view.php';
    }
    
    public function backup_db(){
        echo "<div class='wrap'><h2>LOOOOL</h2>";
        $location = $_SERVER['DOCUMENT_ROOT'];
        include ($location . '/wp-config.php');
        
        $data = date('m-d-Y_H-i-s');
        $file = dirname(__FILE__)."/".$data.".sql.gz";
        $mysqldump = "mysqldump --user=".DB_NAME." --password=".DB_PASSWORD." --host=".DB_HOST ." ". DB_NAME ." | gzip -9 -c > ".$file ;
        
        global $wpdb;
        
        $wpdb->insert( 
	'rcam_backup', 
            array( 
                    'site' => get_site_url(), 
                    'dir' => $file 
            ), 
            array( 
                    '%s', 
                    '%s' 
            )
        );
        exec($mysqldump);
        
        echo "</div>";
    }
    public function show_permission(){
        include 'admin/table_permission.php';
    }
    
    public function test(){
        echo "<div class='wrap'>";
        echo wp_get_schedule( 'schedule_backup_events' );
        echo "</div>";
    }
}

    $test = new rcam;
?>