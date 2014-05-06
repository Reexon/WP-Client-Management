<?php


/*
 * lista dei relativi ( slug => nome_visivo )
 */
$tabs = array( 'authorization' => 'Accessi',
                'schedule' => 'Schedule',
                'backup'    => 'Backup',
                'uptime'    => 'Up Time',
                'support'   => 'Supporto',
                'info'      =>'Info'
            );

    $links = array();
    
    /*
     * tramite un foreach fatto sull'array di tab che devo creare
     * genero il codice html da stampare per i TAB.
     * 
     * il codice html di ogni tab andrà all'interno dell'array $links, ogni posizione un codice html per un tab
     */
    foreach( $tabs as $tab => $name ) :

            $links[] = "<a class='nav-tab' href='?page=menu-tab#top#$tab' id='$tab"."-tab'>$name</a>";

    endforeach;
    
    /*
     * stampo i tab generati tramite il foreach
     */
    echo '<h2 class="nav-tab-wrapper" id="wprcam-tabs">';
    foreach ( $links as $link )
        echo $link;
    echo '</h2>';
    ?>

<div class="tabwrapper">
    <?php foreach ($tabs as $key => $value):
        include sprintf('%s_page.php',$key);
    endforeach;
    ?>
</div>