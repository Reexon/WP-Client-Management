<div id="schedule" class="wprcamtab">
    <h2> Gestione Backup </h2>
    
    <?php
    ////netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css

    //wp_enqueue_script( 'wp-rmacdd-js',get_site_url()."/wp-content/plugins/rcam/js/wp-rcam-schedule.js",array("jquery") );
    
    global $wpdb;

    /*
     * verifico se è stato cliccato il tasto ti salvataggio
     */
    if($_POST['schedule_update'] == 1){

        /*
         * quando le checkbox vengono inviate tramite post
         * il valore delle loro variabili in realtà è ON/OFF
         * quindi bisogna convertire gli on e off in 1/0 per poterlo inserire nelle query
         */
        if( $_POST['remote_backup'] )
            $remote_backup = 1;
        else
            $remote_backup = 0;
        
       if( $_POST['local_backup'] )
           $local_backup = 1;
       else
           $local_backup = 0;
       
       if( $_POST['mail_backup'] )
           $mail_backup = 1;
       else
           $mail_backup = 0;
       
        /*
         * creo array che verrà passato alla query di update
         * 'colonna da aggiornare' => 'nuovo valore'
         * 
         */
        $new_schedule_param = array(
                            'remote_backup' => $remote_b,
                            'local_backup'  => $local_backup,
                            'mail_backup'   => $mail_backup,
                            'recurrence_backup' => $_POST['recurrence_backup']
        );

       $test = $wpdb->update( 
            TABLE_SCHEDULE, 
            $new_schedule_param, 
            array( 'id_schedule' => $_POST['id_schedule'] ), 
            array( 
                    '%d',   //remote_backup
                    '%d',	// local_backup
                    '%d',	// mail_backup
                    '%s'	// recurrence_backup
            ), 
            array( '%d' ) 
        );
       
    }
    
    $sql = "SELECT * FROM ".TABLE_SCHEDULE. " JOIN ".TABLE_SITE . " ON site_id = id_site";
    
    $result = $wpdb->get_results( 
	$sql,ARRAY_A
        );
    
    $recurrence_array = array(
                            'minutely' => "Ogni Minuto",
                            'hourly'   => "Ogni Ora",
                            'daily'    => "Ogni Giorno",
                            'weekly'   => "Ogni Settimana"
    );
    ?>
    
    <table class='widefat'>
        <thead>
                <tr>
                    <th>ID                  </th>
                    <th>Site                </th>
                    <th>Ultimo Aggiornamento</th>
                    <th>Tipo di Backup      </th>
                    <th>Salvataggio         </th>
                    <th>Azioni              </th>
            </tr>
        </thead>
        
        <tbody>
    
    <?php
    foreach ($result as $schedule):?>
    
    <tr>
        <form method="POST" action="schedule_update.php" id="form_schedule">
            <input type="hidden" name="id_schedule" value='<?php echo $schedule['id_schedule']; ?>'>
            <input type="hidden" name="schedule_update" value="1">
            <td>
                <?php echo $schedule['id_site']; ?>
            </td>
            <td>
                <?php echo $schedule['url']; ?>
            </td>
            <td>
                <?php echo $schedule['update_date']; ?>
            </td>
            <td>
                <p>
                    <select name="recurrence_backup">
                        <option value="<?php echo $schedule['recurrence_backup']; ?>" selected> <?php echo $recurrence_array[$schedule['recurrence_backup']];?></option>
                        <?php
                        foreach($recurrence_array as $key => $value){

                            if($key != $schedule['recurrence_backup'])
                                echo "<option value='$key'>".$value."</option>";

                        }

                        ?>
                    </select>
                </p>
                <p>
                    <select name="recurrence_backup_file">
                        <option value="<?php echo $schedule['recurrence_backup_file']; ?>" selected> <?php echo $recurrence_array[$schedule['recurrence_backup_file']];?></option>
                        <?php
                        foreach($recurrence_array as $key => $value){

                            if($key != $schedule['recurrence_backup_file'])
                                echo "<option value='$key'>".$value."</option>";

                        }

                        ?>
                    </select>
                </p>
            </td>
            <td>
                <p>
                    <input type = "checkbox" name = "remote_backup" <?php if($schedule['remote_backup']==1) echo "checked"; ?> >Remoto</input>
                    <input type = "checkbox" name = "local_backup"  <?php if($schedule['local_backup']==1)  echo "checked"; ?> >Local</input>
                    <input type = "checkbox" name = "mail_backup"   <?php if($schedule['mail_backup']==1)   echo "checked"; ?> >Mail</input>
                </p>
                <p>
                    <input type = "checkbox" name = "remote_backup_file" <?php if($schedule['remote_backup_file']==1) echo "checked"; ?> >Remoto</input>
                    <input type = "checkbox" name = "local_backup_file"  <?php if($schedule['local_backup_file']==1)  echo "checked"; ?> >Local</input>
                    <input type = "checkbox" name = "mail_backup_file"   <?php if($schedule['mail_backup_file']==1)   echo "checked"; ?> >Mail</input>
                </p>
            </td>
            <td>
                <?php 
                reexon_get_submit_button("save-schedule-options", "btn btn-primary", $schedule['id_schedule'], "Salvo", null, "Salva", "fa fa-floppy-o", "color:white"); 
                ?>
            </td>
        </form>
    </tr>
    
    <?php 
        endforeach;
    ?>
    </tbody>
    </table>
</div>