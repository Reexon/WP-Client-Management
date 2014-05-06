<div id="authorization" class="wprcamtab">
    <?php 
    
global $wpdb;

$table_column = array ('id_site','active','url','article','media','pages','comments','portfolio',
                        'themes','plugins','users','tools','settings','updates');

//echo dirname(__FILE__);

/*
 * se il flag update è impostato a 1 , allora si è cliccato sul tasto per il salvataggio(submit)
 */
if( $_POST['update'] == 1){
    $permessi = $_POST['permission'];

    /*
     * creazione dell'array associativo dei permessi
     * nome_potere => 0/1 (no/si)
     * 
     * article => 0
     * media => 1 
     * ...
     */
    $new_perm = array();
    
    for($i=3 ; $i < count($table_column) ; $i++):
        /*
         * passando i checkbox con il post, i box non selezionati, non vengono trasmessi,
         * in questo modo identifichiamo quelli trasmessi (1)
         * e quelli non trasmessi , gli associamo in automatico un 0.
         */
        if (!$permessi[$table_column[$i]]) {
            $int = 0;
        } else {
            $int = 1;
        }
        /*
         * aggiunge all'array il nuovo potere con il suo valore
         */
        $new_perm[$table_column[$i]] = $int;
        
    endfor;
    
    /*
     * aggiorno i permessi nel database
     */
    
    $wpdb->update( 
	TABLE_ACCESS, 
	$new_perm, 
	array( 'site_id' => $_POST['site_id'] ), 
	array( 
		'%d',   // update
                '%d',	// article
		'%d',	// media
		'%d',	// pages
		'%d',	// comments
		'%d',	// portfolio
		'%d',	// themes
		'%d',	// plugins
		'%d',	// users
		'%d',	// tools
		'%d'	// settings
	), 
	array( '%d' ) 
    );
}

/*
 * genero la mia query, in base ai cambi che mi interessano (elencati all'inioz della pagina)
 */
for($i=0 ; $i < count($table_column); $i++){
    if ($i==0)
        $query = "SELECT ";
    else
        $query .=",";
    
    $query .= $table_column[$i];
}
$query .= " FROM ". TABLE_ACCESS ." JOIN ". TABLE_SITE ." ON id_site = site_id";

echo $query;
$result = $wpdb->get_results( 
	$query,ARRAY_N
        );

?>
    
    <h2> Accesso Clienti </h2>
        <table class="widefat">
          <tbody>
             
                 <?php
                 /*
                  * verrà eseguito tante volte, quanti sono i siti nel database
                  */
                 foreach($result as $permission): 
                     /* [0] - ID
                      * [1] - Active
                      * [2] - Sito
                      * [3] - permessi (true/false)
                      */
                     ?>
                  <tr>
                     <form method="POST" >
                     <input type="hidden" name="update" value="1" />
                     <input type="hidden" name="site_id" value="<?php echo $permission[0]; ?>" />
                     <!-- creo un campo hidden, x poter inviare il sito su cui si vogliono salvare le modifiche
                     altrimenti non sapremmo riconoscere su quale sito sono state richieste le modifiche ai permessi -->
                     
                     <input type='hidden' name='site_name' value='<?php echo $permission[2]; ?>'/>
                     <td colspan="5" align="center"> 
                        <?php echo "<h2>$permission[2] - "; ?> 
                        <font color='<?php echo $permission[1]==1 ? "green" : "red"; ?>'>
                        <?php echo $permission[1]==1 ? "Attivo" : "Disabilitato"; ?>
                        </h2></font>
                     </td>
                     
                    <?php
                    
                    /*
                     * visualizzazione dei checkbox dei permessi
                     * 
                     * $i=3 perchè dobbiamo saltare le prime tre colonne iD,active,Nome sito, dall'indice 3 in poi iniziano i veri permessi
                     */
                    for($i = 3 ; $i < count($table_column) ; $i++): ?>
                        <?php if( (($i-3) % 5) == 0 ){
                            echo "</tr><tr>";
                        }?>
                        <td>
                            <input type='checkbox' name='permission[<?php echo $table_column[$i]; ?>]' <?php if($permission[$i]==1) echo "checked"; ?>/>
                            <?php echo $table_column[$i]; ?>
                        </td>
                        
                        <?php 
                    endfor;
                    
                    echo "</tr><tr><td colspan='5' align='center'>";
                         submit_button( 'Salva', 'primary','',false);
                    echo "</td></form></tr>";
                    
                endforeach;
                ?>
          </tbody>
      </table>
    
</div>