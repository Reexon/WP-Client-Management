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

/**
 * @var string $_POST['action']
 *          contiene in stringa l'azione da eseguire
 *      
 * 
 */

switch($_POST['action']){
    /*
     * Creo un nuovo ticket
     * TICKET_TABLE : id_ticket, title, text, open_time, close_time, status_id, site_id, category_id
     */
    case 'new_ticket':
        $site = $wpdb->get_var("SELECT id_site FROM ".TABLE_SITE." WHERE url='http://".$_POST['site']."'" );
        $wpdb->insert( 
                    TABLE_TICKET, 
                        array( 
                                'title'         => $_POST['title'],
                                'text'          => $_POST['text'],
                                'status_id'     => 1,
                                'site_id'       => $site,
                                'category_id'   => $_POST['category_id']
                        ), 
                        array( 
                                '%s', 
                                '%s',
                                '%d',
                                '%s',
                                '%d'
                        )
                    );
    break;

    /**
     * @var $_POST['ticket_id']
     *          contiene l'id del ticket a cui aggiungere la risposta
     * @var $_POST['text']
     *          il testo di risposta da aggiungere
     * @var $_POST['staff_id']
     *          l'id del membro dello staff che ha risposto al ticket
     */
    case 'add_answer':
        /*
         * inserisco la nuova risposta
         */
        $wpdb->insert( 
            TABLE_TICKET_ANSWER, 
                array( 
                        'ticket_id' => $_POST['ticket_id'], 
                        'answer_text' => $_POST['text'],
                        'staff_id' => $_POST['staff_id']
                ), 
                array( 
                        '%d', 
                        '%s',
                        '%d'
                )
            );
        /*
         * prelevo il display name dello staff che ha inserito la nuova risposta
         */
        
        $user = get_userdata( $userid );
        $response['display_name'] = $user->display_name;
        $response['date'] = date('d-m-Y H:i:s');
        echo json_encode($response);
    break;
    
    /**
     * @var $_POST['ticket_id']
     *          contiene l'id del ticket a cui il cliente sta aggiungendo una risposta
     * @var $_POST['text']
     *          il testo della risposta da aggiungere
     * @var $_POST['site']
     *          contiene l'url del sito che sta facendo richiesta di aggiunta risposta
     */
    case 'add_answer_client':
        $wpdb->insert( 
                    TABLE_TICKET_ANSWER, 
                        array( 
                                'ticket_id' => $_POST['ticket_id'], 
                                'answer_text' => $_POST['text'],
                        ), 
                        array( 
                                '%d', 
                                '%s'
                        )
                    );
    break;

    /**
     * @var $_POST['ticket_id'] 
     *          contiene l'id del ticket da chiudere
     */
    case 'close_ticket':
        
        $wpdb->update(TABLE_TICKET,
                    array('status_id' => 0),
                    array('id_ticket' => $_POST['ticket_id']),
                    array ( '%d' ),
                    array ( '%d' )
                );
        
    break;

    /**
     * @var $_POST['ticket_id'] 
     *          contiene l'id del ticket da aprire
     */
    case 'open_ticket':
        $wpdb->update(TABLE_TICKET,
                    array('status_id' => 1),
                    array('id_ticket' => $_POST['ticket_id']),
                    array ( '%d' ),
                    array ( '%d' )
        );
    break;
}

?>