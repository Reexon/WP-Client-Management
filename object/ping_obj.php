<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ping_obj
 *
 * @author Loris
 */
class ping_obj {
    public function __construct() {
        add_action( 'init', array($this,'setup_ping_schedule') );
        add_action( 'admin_menu', array( $this , 'add_admin_menu') );
    }
    
    public function add_admin_menu(){
        
        add_submenu_page( 'client-permission', 'Permessiddd', 'Permessiddd', 'administrator', 'clientsss-permission', array ( $this, 'show_permission' ) ); 
    }
    
    public function show_permission(){
        echo  "<div class='wrap'><pre>";
        //mkdir($_SERVER['DOCUMENT_ROOT']."/wp-content/plugins/rcam/backup/files/"."test", 0700,TRUE);
        echo dirname(__FILE__)."<br/>";
            
        echo $_SERVER['DOCUMENT_ROOT']."/wp-content/plugins/rcam/backup/files/test/file.zip <br/>";
        /*echo BACKUP_DB_DIR;
               $this->Zip($_SERVER['DOCUMENT_ROOT'], $_SERVER['DOCUMENT_ROOT']."/wp-content/plugins/rcam/backup/files/test/file.zip", false, false);
                */echo "</pre></div>";
    }
    
    public function setup_ping_schedule(){
        if (!wp_next_scheduled('schedule_uptime_ping')) {
            wp_schedule_event(time(), 'minutely', 'schedule_uptime_ping');
        }
        add_action( 'schedule_uptime_ping', array($this,'pingAllSite') );
    }
    
    public function pingAllSite(){
        $allSites = $this->getAllSites();
        foreach($allSites as $site){
            $this->pingSite($site[0]);
        }
    }
    
    /*
     * Effettua il test del ping
     */
    private function pingSite($url){

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_exec($ch);
            /*
             * se retcore è 200 allora la connessioen è andata a buon fine
             */
            $retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            /*
             * finalmente registro il risultato del ping
             */
            $this->recordPingResult($retcode, $url);
    }
    
    /*
     * preleva tutti gli url su cui fare il ping
     */
    private function getAllSites(){
        $location = $_SERVER['DOCUMENT_ROOT'];
        include_once ($location . '/wp-config.php');
        
        global $wpdb;
        
         return $wpdb->get_results("SELECT url FROM " .TABLE_SITE." WHERE PING = 1",ARRAY_N);
    }
    
    /*
     * registra il risultato del ping
     */
    private function recordPingResult($result,$url){
        $location = $_SERVER['DOCUMENT_ROOT'];
        include_once ($location . '/wp-config.php');
        
        global $wpdb;
        
        $site_id = $this->getIDfromURL($url);
        
        $wpdb->insert( 
            TABLE_PING, 
                array( 
                        'result' => $result, 
                        'site_id' => $site_id
                ), 
                array( 
                        '%s', 
                        '%d' 
                )
            );   
    }
    
    private function getIDfromURL($url){
        $location = $_SERVER['DOCUMENT_ROOT'];
        include_once ($location . '/wp-config.php');
        
        global $wpdb;
        return $wpdb->get_var("SELECT id_site FROM ".TABLE_SITE." WHERE url='$url'" );
    }

    /*
     * creo lo zip di tutto il folder del sito
     */
    private function Zip($source, $destination, $include_dir = false,$send_remotely = false){

        if (!extension_loaded('zip') || !file_exists($source)) {
            return false;
        }

        if (file_exists($destination)) {
            unlink ($destination);
        }

        $zip = new ZipArchive();
        if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
            return false;
        }
        $source = str_replace('\\', '/', realpath($source));

        if (is_dir($source) === true)
        {

            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

            if ($include_dir) {

                $arr = explode("/",$source);
                $maindir = $arr[count($arr)- 1];

                $source = "";
                for ($i=0; $i < count($arr) - 1; $i++) { 
                    $source .= '/' . $arr[$i];
                }

                $source = substr($source, 1);

                $zip->addEmptyDir($maindir);

            }

            foreach ($files as $file)
            {
                $file = str_replace('\\', '/', $file);

                // Ignore "." and ".." folders
                if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) )
                    continue;

                $file = realpath($file);

                if (is_dir($file) === true)
                {
                    $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
                }
                else if (is_file($file) === true && !strstr($file,".zip") )
                {
                    $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
                }
            }
        }
        else if (is_file($source) === true && !strstr($source,".zip"))
        {
            $zip->addFromString(basename($source), file_get_contents($source));
        }
        
        if($send_remotely){
            $this->send_backup($destination, self::$endpoint_store_backup,BACKUP_FILES);
        }
        
        return $zip->close();
    }
    
}

$boh = new ping_obj;