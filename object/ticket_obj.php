<?php

/**
 * Description of ticket_obj
 *
 * @author Loris
 */
class ticket_obj {
    
   public function __construct() {
       add_action('admin_menu',array($this,'add_admin_menu'));
   }
   
    public function add_admin_menu(){
        add_submenu_page( 'ticket-manage', 'Permessi', 'Permessi', 'administrator', 'ticket-manage', array ( $this, 'ticket_page' ) ); 
        //add_submenu_page( 'client-permission', 'Test', 'Test', 'administrator', 'test-permission', array ( $this, 'test' ) ); 
        //add_submenu_page( 'client-permission', 'Test backup', 'Test backup', 'administrator', 'client-backup', array ( $this, 'backup_db' ) ); 
    }
}

$boh = new ticket_obj();

?>