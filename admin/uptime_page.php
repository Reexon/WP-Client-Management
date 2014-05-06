<div id="uptime" class="wprcamtab">
    <h2> Uptime Monitor </h2>

    <?php
    
    //wp_enqueue_script( 'wp-rmac-js-uptime',get_site_url()."/wp-content/plugins/rcam/js/wp-rcam-uptime.js",array("jquery") );
    
    global $wpdb;
    $result = $wpdb->get_results("SELECT url,success_ping,total_ping,GET_SUCCESS_PING.ping,total_ping - success_ping AS failed_ping,GET_TOTAL_PING.site_id FROM `GET_SUCCESS_PING` JOIN GET_TOTAL_PING ON GET_SUCCESS_PING.site_id = GET_TOTAL_PING.site_id JOIN ".TABLE_SITE." ON id_site = GET_TOTAL_PING.site_id", ARRAY_A);
    
    ?>
    <table class='widefat'>
        <thead>
            <tr>
                <th>ID                  </th>
                <th>Site                </th>
                <th>Successi            </th>
                <th>Fallimenti          </th>
                <th>Totali              </th>
                <th>% UP                </th>
                <th>Stato               </th>
                <th>Azioni              </th>
            </tr>
        </thead>

        <tbody>
            <?php
            
              foreach ($result as $row){
                  $percentage_uptime = ($row[success_ping]/$row[total_ping])*100;
                  /*
                   * WARNING: uso l'attributo class per poter gestire l'apertura/chiusura del grafico
                   * tramite jquery,tramite id non avrebbe funzionato a dovere, perchè avrebbe considerato solo il primo oggetto.
                   */
                  $stato = "<font id='uptime_status'color='";
                  if($row['ping']==1) 
                      $stato .= "green'>Attivo";
                  else
                    $stato .= "red'>Disattivato";
                  
                  $stato .="</font>";
                  ?>
                    <tr>
                        <td> <?php echo $row['site_id']; ?></td>
                        <td> <?php echo $row['url']; ?></td>
                        <td> <?php echo $row['success_ping']; ?> </td>
                        <td> <?php echo $row['failed_ping']; ?> </td>
                        <td> <?php echo $row['total_ping']; ?></td>
                        <td width="15%"><progress max='100' value='<?php echo $percentage_uptime; ?>' style="width:100%;"></progress></td>
                        <td width="10%"> <?php echo $stato; ?></td>
                        <td width="10%"> <?php 
                        if($row['ping']){//se il ping è attivo
                            reexon_get_submit_button("save-uptime-options", "btn btn-success", $row['site_id'], "Attivo...", "display:none;", "Attiva", "fa fa-check", "color:white;"); 
                            reexon_get_submit_button("save-uptime-options", "btn btn-danger", $row['site_id'], "Blocco...", null, "Blocca", "fa fa-ban", "color:white;"); 
                        }else{
                            reexon_get_submit_button("save-uptime-options", "btn btn-success", $row['site_id'], "Attivo...", null, "Attiva", "fa fa-check", "color:white;"); 
                            reexon_get_submit_button("save-uptime-options", "btn btn-danger", $row['site_id'], "Blocco...", "display:none;", "Blocca", "fa fa-ban", "color:white;"); 
                        }
                        ?></td>
                    </tr>
            <?php
                }
            ?>
        </tbody>
    </table>
</div>
