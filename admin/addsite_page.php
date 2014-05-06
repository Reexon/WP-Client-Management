<?php

$permission = array(
        'article'   => "Articoli",
        'media'     => "Media",
        'pages'     => "Pagine",
        'comments'  => "Commenti",
        'portfolio' => "Portfolio",
        'themes'    => "Temi",
        'plugins'   => "Plugins",
        'users'     => "User",
        'tools'     => "Strumenti",
        'settings'  => "Setting",
        'updates'    => "Aggiornamenti"
);

?>

<div class='wrap'>

    <form method="POST" id="form_addsite">
        
        
        <!-- scheduling file sito -->
        Sito Web:<input type="text" name ="url_site" title="Link del sito da gestire http://www.demo.it">
        <p>
            <select name ="backup_recurrence_file">
                <option value="minutely">Ogni minuto</option>
                <option value="hourly">Ogni ora</option>
                <option value="daily">Ogni giorno </option>
                <option value="weekly">Ogni settimana</option>
            </select>
        

            <!-- tipo di backup dei file -->
            <input type="checkbox" name="remote_backup_file"> Remote Backup
            <input type="checkbox" name ="local_backup_file"> Local Backup
            <input type="checkbox" name="mail_backup_file"> Mail Backup
        </p>
        <p>
            <!-- scheduling database -->
            <select name ="backup_recurrence_database">
                <option value="minutely">Ogni minuto</option>
                <option value="hourly">Ogni ora</option>
                <option value="daily">Ogni giorno </option>
                <option value="weekly">Ogni settimana</option>
            </select>

            <!-- tipo di backup di database -->
            <input type="checkbox" name="remote_backup_database"> DB Remote Backup
            <input type="checkbox" name ="local_backup_database"> DB Local Backup
            <input type="checkbox" name="mail_backup_database"> DB Mail Backup
        </p>
        <!-- selezione dei accessi -->
        <p>
        <?php foreach ($permission as $key => $value): ?>
            <input type="checkbox" name="<?php echo $key; ?>_option" ><?php echo $value; ?></input>
        <?php endforeach; ?>
        </p>
        
        <!-- submit button -->
        <?php reexon_get_submit_button("add_site", "btn btn-primary", "Aggiungi Sito", "Aggiungo ...", false, "Aggiungi Sito", "fa fa-plus", true); ?>

    </form>

</div>
                