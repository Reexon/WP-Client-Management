<div id="backup" class="wprcamtab">
    <h2>Backup</h2>

    <?php
    $url_replace = array("www.", "http://");

    global $wpdb;

    $result = $wpdb->get_results(
            "SELECT COUNT(*) as backup_totali,url FROM " . TABLE_BACKUP . " JOIN " . TABLE_SITE . " ON site_id = id_site GROUP BY url ORDER BY url ASC", ARRAY_A
    );

    $result_backup = $wpdb->get_results(
            "SELECT * FROM " . TABLE_BACKUP . " JOIN " . TABLE_SITE . " ON site_id = id_site ORDER BY time DESC", ARRAY_A
    );
    ?>


    <div style="width:150px;float:left;">
        <ul class="nav nav-pills nav-stacked">
            <?php
            $i = 0;
            foreach ($result as $sites):
                $site[$i]['backup_totali'] = $sites['backup_totali'];
                $site[$i]['url'] = str_replace($url_replace, '', $sites['url']);
                ?>
                <li class="<?php if ($i == 0) echo 'active'; ?> backup_site_info"><a href="#"><i class="fa fa-book fa-fw"></i> <?php echo $site[$i]['url']; ?> </a></li>
    <?php $i++;
endforeach; ?>
        </ul>
    </div>
    <div id="backup_selection"style="width:auto;float:left;display: block;margin-left:25%;margin-bottom:20px;">
        <input type="checkbox" name="files_backup" checked ><i class="fa fa-folder-o"></i> File</input>
        <input type="checkbox" name="database_backup" checked ><i class="fa fa-folder-o"></i> Database</input>
    </div>
<?php 
// mi serve solo per impostare la prima tabella visible e le altre nascoste
$is_first_site = true;
foreach ($site as $info_site): ?>
        <div style="float:left;">
            <table name="<?php echo str_replace(".", "_", $info_site['url']); ?>" class="wp-list-table widefat fixed tags" style="<?php if(!$is_first_site) echo 'display:none;'; ?>width:auto !important; margin-left:60px;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Dir</th>
                        <th>Type</th>
                        <th>Integrità</th>
                        <th>Time</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $is_first_site = false;
                    $count = 1;
                    foreach ($result_backup as $backup):

                        if ($info_site['backup_totali'] >= $count):
                            ?>
                            <tr>
                                <td>
                                    <?php echo $backup['id_backup']; ?>
                                </td>
                                <td>
                                    <?php echo $backup['dir']; ?>
                                </td>
                                <td>
                                    <?php echo $backup['type']; ?>
                                </td>
                                <td>
                                    <?php echo $backup['crc']; ?>
                                </td>
                                <td>
                                    <?php echo $backup['time']; ?>
                                </td>
                                <td>
                                    <form method = "POST">
                                        <input type="hidden" name="delete_backup_id" value="<?php echo $backup['id_backup']; ?>"/>
                                        <button type="submit" class="btn btn-danger" name="delete-backup" data-loading-text="Deleting...">
                                            <i class="fa fa-trash-o" style="color:white;"></i> Elimina
                                        </button>
                                        
                                        <a href="<?php 
                                            echo $backup['dir']; ?>
                                            " class="btn btn-primary" style="display:inline;">
                                                <i class="fa fa-download" style="color:white;"></i> Download
                                        </a>
                                    </form>
                                </td>
                            </tr>
                            <?php
                            if ($info_site['backup_totali'] == $count) {
                                break;
                            }
                            $count++;
                        endif;
                    endforeach;
                    ?>
                </tbody>
                <tfoot>
                <th colspan="6" align="right">
                    <button type="submit" class="btn btn-danger" name="delete-all-backup" data-loading-text="Deleting...">
                        <i class="fa fa-trash-o" style="color:white;"></i> Elimina Tutto
                    </button>
                </th>
                </tfoot>

            </table>
        </div>
<?php endforeach; ?>
</div>
