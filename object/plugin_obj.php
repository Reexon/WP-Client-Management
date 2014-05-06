<?php

class rcam{
    public function __construct(){
        add_action( 'admin_menu', array( $this , 'add_admin_menu') );
        

        //add_action( 'wp', array($this,'setup_schedule') );
        //add_action( 'schedule_backup_events', array($this,'backup_event') );
        
        register_deactivation_hook( __FILE__,   array( $this , 'deactivate') );
        register_activation_hook(   __FILE__,   array( $this , 'activate'  ) );
        register_uninstall_hook(    __FILE__ ,  array( $this , 'uninstall' ) );
    }
    
    public function activate(){
        $this->createTable();
    }
    
    public function deactivate(){
        wp_clear_scheduled_hook( 'schedule_backup_events' );
        wp_clear_scheduled_hook( 'schedule_uptime_ping' );
    }
    
    public function uninstall(){
        $this->dropTable();
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
        add_submenu_page( 'client-permission', 'Aggiungi Sito', 'Aggiungi Sito', 'administrator', 'addnew-site', array ( $this, 'add_site' ) ); 
        add_submenu_page( 'client-permission', 'Menu Tab', 'menu tab', 'administrator', 'menu-tab', array ( $this, 'menu_tab' ) ); 
    }
    
    public function add_site(){
        include dirname(__FILE__).'/../admin/addsite_page.php';
    }
    public function menu_tab(){
        include dirname(__FILE__).'/../admin/tab_view.php';
    }
      public function show_permission(){
          echo "<div class='wrap'><h2>test</h2>";
          //$this->Zip($_SERVER['DOCUMENT_ROOT']."/", ROOT_PLUGIN."test.zip");
          echo "</div>";
    }
}

$test = new rcam;

?>