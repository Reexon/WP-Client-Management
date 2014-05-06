<?php


define( 'TABLE_ACCESS'   ,   'rcam_access'  );
define( 'TABLE_PING'     ,   'rcam_ping'    );
define( 'TABLE_SITE'     ,   'rcam_site'    );
define( 'TABLE_LOAD'     ,   'rcam_load'    );
define( 'TABLE_BACKUP'   ,  'rcam_backup'   );
define( 'TABLE_SCHEDULE' ,  'rcam_schedule' );
define( 'TABLE_TICKET'   ,  'rcam_ticket' );
define( 'TABLE_TICKET_CATEGORY'   ,  'rcam_ticket_category' );
define( 'TABLE_TICKET_ANSWER'   ,  'rcam_ticket_answer' );
define ('TABLE_USERS','sot_users');
define ('BACKUP_DATABASE', 'database' );
define ('BACKUP_FILES'   , 'files' );
define('ROOT_PLUGIN'    ,$_SERVER['DOCUMENT_ROOT']."/wp-content/plugins/rcam/");
define('BACKUP_DB_DIR'  ,$_SERVER['DOCUMENT_ROOT']."/wp-content/plugins/rcam/backup/database/");
define('BACKUP_FILE_DIR',$_SERVER['DOCUMENT_ROOT']."/wp-content/plugins/rcam/backup/files/");

/*
 * Lista di funzioni usate molto di frequente.
 * Li scrivo qua dentro , per evitare ogni volta di dover ri-scrivere la funzione in ogni pagina
 */

function reexon_get_submit_button($name,$class,$value,$text_loading,$style,$text,$icon_class,$icon_style){?>

    <button type="submit" name="<?php echo $name; ?>" 
            data-loading-text="<?php echo $text_loading; ?>" 
                class="<?php echo $class; ?>"
                value="<?php echo $value; ?>"
                <?php if($style) echo "style='$style'"; ?> >
                  <i class="<?php echo $icon_class;?>" 
                     <?php if($icon_style) echo 'style="color:white;"'; ?>>
                  </i> 
                 <?php echo $text; ?>
            </button>
<?php
}
?>