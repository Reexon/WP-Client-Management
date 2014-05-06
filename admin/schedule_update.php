<?php

/*
 * per poter usufuire del $wpdb
 */
$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require_once( $parse_uri[0] . 'wp-load.php' );

$plugin_dir = dirname(dirname(__FILE__));
include_once $plugin_dir.'/includes/inc.php';


$param_array['recurrence_backup'] = $_POST['recurrence_backup'] ;
$param_array['remote_backup'] = $_POST['remote_backup'] ? 1 : 0;
$param_array['local_backup'] = $_POST['local_backup'] ? 1 : 0;
$param_array['mail_backup'] = $_POST['mail_backup'] ? 1 : 0;
$param_array['recurrence_backup_file'] = $_POST['recurrence_backup_file'] ;
$param_array['remote_backup_file'] = $_POST['remote_backup_file'] ? 1 : 0;
$param_array['local_backup_file'] = $_POST['local_backup_file'] ? 1 : 0;
$param_array['mail_backup_file'] = $_POST['mail_backup_file'] ? 1 : 0;

global $wpdb;

$wpdb->update( 
            TABLE_SCHEDULE, 
            $param_array,
                array( 
                        'id_schedule'       => $_POST['id_schedule']
                    ), 
                array( 
                        '%s', //recurrence_backup
                        '%d', //remote_backup
                        '%d', //local_backuo
                        '%d', //mail_backup
                        '%s', //recurrence_backup_file
                        '%d', //remote_backup_file
                        '%d', //local_backup_file
                        '%d' //mail_backup_file
                ),
                array( '%d' )
            );

?>