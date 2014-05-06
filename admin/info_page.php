<div id="info" class="wprcamtab">
    <h2>Informazioni</h2>
    
    <?php
    
    global $wpdb;
    
    /*
     * seleziono tutti i siti presenti
     */
    $result = $wpdb->get_results("SELECT * FROM " . TABLE_SITE , ARRAY_A );
    ?>
    
    <table class="widefat">
        <thead>
            <tr>
                <th>#</th>
                <th>Sito Web</th>
                <th>Versione</th>
                <th>Proprietario</th>
                <th>eMail</th>
                <th>Integrità</th>
                <th width="8%">Stato</th>
                <th width="20%">Azioni</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($result as $site):?>
            
            <tr>
                <td><?php echo $site['id_site']; ?></td>
                <td>
                    <a href="<?php echo $site['url']; ?>">
                        <?php echo $site['url']; ?>
                    </a>
                </td>
                
                <td>
                    <?php echo $site['version']; ?>
                </td>
                
                <td>
                    <?php echo $site['name'] . " " . $site['surname']; ?>
                </td>
                
                <td>
                    <a href="mailto:<?php echo $site['mail']; ?>">
                        <?php echo $site['mail']; ?>
                    </a>
                </td>
                
                <td>
                    <?php echo hash_file("crc32", __FILE__);?>
                </td>
                
                <td>
                    
                    <font color="<?php echo $site['active']==1 ? 'green' : 'red'; ?>" id="status">
                        <?php echo $site['active']==1 ? 'Attivo' : 'Bloccato'; ?>
                    </font>
                    
                </td>
                <td>
                        <?php 

                         get_lock_button($site['active'], $site['id_site']);
                         get_unlock_button($site['active'], $site['id_site']);
                        ?>
                </td>
            </tr>
            
            <?php endforeach; ?>
            
        </tbody>
    </table>
    
</div>

<?php


function get_lock_button($state,$id_site){ ?>

        <button type="submit" 
                name="action_site" 
                data-loading-text="Blocco..."
                class="btn btn-danger"
                value="<?php echo $id_site; ?>"
                <?php if($state ==0 ) echo ' style="display:none;"'; ?>
        >
                    <i class="fa fa-ban" style="color:white;"></i>
                Blocca
        </button>
<?php                                
}

function get_unlock_button($state,$id_site){
?>
    <button type="submit" name="action_site" data-loading-text="Attivo..." 
                class="btn btn-success"
                value="<?php echo $id_site; ?>"
                <?php if($state ==1 ) echo ' style="display:none;"'; ?> >
                  <i class="fa fa-check" style="color:white;"></i> 
                 Attiva
            </button>
<?php
}
?>