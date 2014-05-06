<?php 
global $wpdb;
$table_column = array ('site','article','media','pages','comments','portfolio','faq',
                        'themes','plugins','users','tools','settings','update');

//echo dirname(__FILE__);
/*
 * se il flag update è impostato a 1 , allora si è cliccato sul tasto per il salvataggio(submit)
 */
if( $_POST['update'] == 1){
    
    $permessi = $_POST['permission'];

    /*
     * creazione dell'array associativo dei permessi
     * nome_potete => 0/1 (no/si)
     * 
     * article => 0
     * media => 1 
     * ...
     */
    $new_perm = array();
    
    for($i=1 ; $i < count($table_column) ; $i++):
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
	'rcam_access', 
	$new_perm, 
	array( 'site' => $_POST['site_name'] ), 
	array( 
		'%d',   //update
                '%d',	// article
		'%d',	// media
		'%d',	// pages
		'%d',	// comments
		'%d',	// portfolio
		'%d',	// faq
		'%d',	// themes
		'%d',	// plugins
		'%d',	// users
		'%d',	// tools
		'%d'	// settings
	), 
	array( '%s' ) 
    );
}

//prelevo Permessi dal DB di tutti i siti

$result = $wpdb->get_results( 
	"SELECT * FROM rcam_access",ARRAY_N
        );

?>

<div class="wrap">
    
    <h2> Accesso Clienti </h2>
        <table class="widefat">
          <!--<thead>
              <tr>
                  <?php 
                      /*foreach ($table_column as $column):
                          echo "<th>$column</th>";
                      endforeach;*/
                  ?>
                  <th>Action</th>
              </tr>
          </thead>-->
          <!--<tfoot>
              <tr>
                  <?php 
                      /*foreach ($table_column as $column):
                          echo "<th>$column</th>";
                      endforeach;*/
                  ?>
                  <th>Action</th>
              </tr>
          </tfoot>-->
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
                    <form method="POST">
                     <input type="hidden" name="update" value="1" />
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
                     * il conteggio parte da $c=2 per far in modo che il for duri (n-2),(visto che uno dei campi è il nome del sito)
                     * NOTA: avremmo potuto fare count($table_column) -2  e mettere $c=0
                     * 
                     * $i=3 perchè dobbiamo saltare le prime tre colonne iD,active,Nome sito, dall'indice 3 in poi iniziano i veri permessi
                     */
                    $i=3; 
                    for($c = 1 ; $c < count($table_column) ; $c++): ?>
                        <?php if( (($c-1) % 5) == 0 ){
                            echo "</tr><tr>";
                        }?>
                        <td>
                            <input type='checkbox' name='permission[<?php echo $table_column[$c]; ?>]' <?php if($permission[$i]==1) echo "checked"; ?>/>
                            <?php echo $table_column[$c]; ?>
                        </td>
                        
                        <?php $i++;
                    endfor;
                    
                    echo "</tr><tr><td colspan='5' align='center'>";
                         submit_button( 'Salva', 'primary', 'save-rcam',false );
                    echo "</td></form></tr>";
                    
                endforeach;
                ?>
          </tbody>
      </table>
       
    </form>
    
</div>