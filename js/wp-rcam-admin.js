jQuery(document).ready(function(){
    
    jQuery("#wprcam-tabs").find("a").click(function() {
        /*
         * rimuovo la classe css che indica lo stato attivo/selezionato del tab
         */
        jQuery("#wprcam-tabs").find("a").removeClass("nav-tab-active");
        
        /*
         * nascondo il div della tab , rimuovendo l'active ( in questo modo diventerà invisbile)
         */
        jQuery(".wprcamtab").removeClass("active");
        
        /*
         * prelevo l'id della tab apena cliccata , e elimino la parte -tab:
         * 
         * tab id="generale-tab"  il suo div ha id="generale"
         * tab id="opzioni-tab" il suo div ha id="opzioni"
         */
        var id = jQuery(this).attr("id").replace("-tab", "");
        
        /*
         * aggiungo la classe attiva sull'id del div che vogliamo rendere visible
         */
        jQuery("#" + id).addClass("active");
        //jQuery("#" + id).removeClass("wprcamtab");
        
        /*
         * aggiungo la classe nav-tab-active, sul tab appena cliccato (che risulterà attivo/selezionato)
         */
        jQuery(this).addClass("nav-tab-active");
    });
    
    var active_tab = window.location.hash.replace("#top#", "");
    if (active_tab == "" || active_tab == "#_=_") {
        active_tab = jQuery(".wprcamtab").attr("id");
    }
    jQuery("#" + active_tab).addClass("active");
    jQuery("#" + active_tab + "-tab").addClass("nav-tab-active");


    /*
     * visualizzo tutti i backup del sito cliccato
     */
    jQuery('.backup_site_info').click(function(){
        /*
         * prelevo il testo (sito completo)
         * presente nel <li> appena cliccato
         */
        var site = jQuery(this).text();
        
        /*
         * salgo di uno e rimuovo lo stato attivo dal sito selezionato
         */
        jQuery(this).parent().find('li').removeClass('active');
        /*
         * seleziono come attivo l'elemento appena cliccato
         */
        jQuery(this).addClass('active');
             site = site.replace('www','');
             site = site.replace('.','_');
             site = site.replace('http','');
             site = site.replace(/[&\/\\#,+()$~%'":*?<>{}]/g,'');
        jQuery(this).parent().parent().parent().find('table[name='+site+']').slideToggle(0);
    });
 
    /*
     * cancella il backup selezionato
     */
    jQuery('button[type="submit"][name="delete-backup"]').click(function (e) {
        e.preventDefault();
        var backup_id = jQuery(this).parent().find("input[type=hidden]").val();
        var riga_tr = jQuery(this).parent().parent().parent();
        
        var btn = jQuery(this);
        btn.button('loading');
        
        /*
         * richiesta ajax al server
         */
        jQuery.ajax({
          type: "POST",
          url: "http://reexon.net/wp-content/plugins/rcam/admin/backup_actions.php",
          data: {backup_id : backup_id,
                action : "delete"},
          success: function(msg) {
              if(msg=="cancellato"){
                  riga_tr.hide("slow");
              }
          },
          error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    alert("Status: " + textStatus); alert("Error: " + errorThrown); 
                    alert("XHR: "+XMLHttpRequest.readyState);
                    alert("State: "+XMLHttpRequest.status);
                }
        }).always(function () {
            btn.button('reset');
          }); 
    });
    
    /*
     * cancella tutti i backup di tutti i siti
     */
    jQuery('button[name="delete-all-backup"]').click(function (e) {
        e.preventDefault();
        var all_rows = jQuery(this).parent().parent().parent().parent().find('tbody tr');
        var btn = jQuery(this);
        btn.button('loading');
        console.log(all_rows);
        
        /*
         * richiesta ajax al server
         */
        jQuery.ajax({
          type: "POST",
          url: "http://reexon.net/wp-content/plugins/rcam/admin/backup_actions.php",
          data: {
                action : "delete-all-backup"},
          success: function(msg) {
              if(msg=="cancellato"){
                  all_rows.hide("slow");
              }
          },
          error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    alert("Status: " + textStatus); alert("Error: " + errorThrown); 
                    alert("XHR: "+XMLHttpRequest.readyState);
                    alert("State: "+XMLHttpRequest.status);
                }
        }).always(function () {
            btn.button('reset');
          }); 
    });
    
    /*
     * Azione di blocco/sblocco di un sito
     */
    jQuery('button[type="submit"][name="action_site"]').click(function (e) {
        e.preventDefault();
        
        var site_id = jQuery(this).val();
        
        //variabile temporanea
        var azione = jQuery(this).text();
        
        //variabile azione finale
        var action ;
        /*
         * all'interno del tag <button> è contenuta l'immagine fontAwesome <i>
         * perciò non posso fare un controllo con l'if, perchè nella variabile action è incluso il codice html del fontAwesome
         * Quindi ricerco la stringa Blocca all'interno del text del bottone,se l'index è diverso da -1, allora ha trovato la stringa
         */
        if(azione.indexOf("Blocca") >= 0)
            action = "disable";
        else
            action ="enable";
        
        var btn = jQuery(this);
        
        btn.button('loading');
        
        var button_enable = jQuery(this).parent().find('.btn-success');
        var button_disable = jQuery(this).parent().find('.btn-danger');
        var label_status = jQuery(this).parent().parent().find('#status');
        
        /*
         * richiesta ajax al server
         */
        jQuery.ajax({
          type: "POST",
          url: "http://reexon.net/wp-content/plugins/rcam/admin/site_actions.php",
          data: {site_id : site_id,
                action : action},
          success: function(msg) {
              msg = msg.trim();
                  //btn.hide("fast");
                  btn.fadeOut(500,function(){
                      
                    if(msg == "bloccato"){
                        
                        label_status.fadeOut(400,function(){
                            label_status.attr('color','red');
                            label_status.text("Bloccato");
                            label_status.fadeIn(400);
                        });
                       
                        button_enable.fadeIn(500);
                      
                    }else{
                        label_status.fadeOut(400,function(){
                                label_status.attr('color','green');
                                label_status.text("Attivo");
                                label_status.fadeIn(400);
                        });
                        
                        button_disable.fadeIn(500);
                    }
                  
                  });


                  btn.button('reset');
                  
          },
          error: function(XMLHttpRequest, textStatus, errorThrown) { 
              btn.button('reset');
                    alert("Status: " + textStatus); alert("Error: " + errorThrown); 
                    alert("XHR: "+XMLHttpRequest.readyState);
                    alert("State: "+XMLHttpRequest.status);
                }
        });
    });
    
    /*
     * Salvataggio impostazioni Schedule
     */
    jQuery('button[type="submit"][name="save-schedule-options"]').click(function (e) {
        e.preventDefault();
        
        var schedule_id = jQuery(this).val();
        var form = jQuery(this).parent().parent().find('form');
        
        var dataString = form.serialize();
       
        var btn = jQuery(this);
        
        btn.button('loading');
        
        
        /*
         * richiesta ajax al server
         */
        jQuery.ajax({
          type: "POST",
          url: "http://reexon.net/wp-content/plugins/rcam/admin/schedule_update.php",
          data: dataString,
          success: function(msg) {
              msg = msg.trim(); 
              btn.button('reset');
                  
          },
          error: function(XMLHttpRequest, textStatus, errorThrown) { 
              btn.button('reset');
                    alert("Status: " + textStatus); alert("Error: " + errorThrown); 
                    alert("XHR: "+XMLHttpRequest.readyState);
                    alert("State: "+XMLHttpRequest.status);
                }
        });
    });
    
    /*
     * Salvataggio impostazioni PING
     */
    jQuery('button[type="submit"][name="save-uptime-options"]').click(function (e) {
        e.preventDefault();
        
        var btn = jQuery(this);
        
        var site_id = btn.val();
       
        var azione = btn.text().trim();
        var action;
        var label_status = btn.parent().parent().find('#uptime_status');
        /*
         * prelevo l'azione da effettuare
         */
        if(azione === "Blocca")
            action = "disable";
        else
            action = "enable";

        btn.button('loading');

        
        /*
         * richiesta ajax al server
         */
        jQuery.ajax({
          type: "POST",
          url: "http://reexon.net/wp-content/plugins/rcam/admin/uptime_update.php",
          data: {site_id : site_id,
                action : action},
          success: function(msg) {
            btn.fadeOut(500,function(){ 
                if(action === "disable"){

                        label_status.fadeOut(400,function(){
                              label_status.attr('color','red');
                              label_status.text('Disattivato');
                              
                        });
                        label_status.fadeIn(500);
                        jQuery(this).parent().find('.btn-success').fadeIn(500);

                }else{

                        label_status.fadeOut(400,function(){
                              label_status.attr('color','green');
                              label_status.text('Attivo');
                        });
                        label_status.fadeIn(500);
                        jQuery(this).parent().find('.btn-danger').fadeIn(500);
                       
                }
            });
              btn.button('reset');
             //label_status.fadeIn(400);

          },
          error: function(XMLHttpRequest, textStatus, errorThrown) { 
              btn.button('reset');
                    alert("Status: " + textStatus); alert("Error: " + errorThrown); 
                    alert("XHR: "+XMLHttpRequest.readyState);
                    alert("State: "+XMLHttpRequest.status);
                }
        });
    });
    
    jQuery('#backup_selection :checkbox').change(function(){
        // false - true
        var status = this.checked;
        
        //file_backup / database_backup
        var type = this.name.replace('_backup','');
        
        
        var menu_site = jQuery('li.active a').text();
        menu_site = menu_site.replace('.','_');
        
        jQuery('table[name='+menu_site+'] > tbody  > tr > td:nth-child(3)').each(function(){

            if(jQuery(this).text().trim().toLowerCase() == type){
                if(status==true)
                    jQuery(this).parent().show();
                else
                     jQuery(this).parent().hide();
            }
        });
        
    });
    
    jQuery('button[name="answer_ticket"]').click(function(){
        /*
         * prelevo l'oggetto textarea, da dove possiamo prelevare facilmente il testo
         */
        var answer = jQuery(this).parent().parent().find('textarea'); 
        // prelevo l'input box nascosta, una contiene l'id del ticket attuale, e una l'id dell'utente staff
       var ticket_id = jQuery(this).parent().find('input[type="hidden"][name="ticket_id"]');
       var staff_id = jQuery(this).parent().find('input[type="hidden"][name="staff_id"]');

       /*
        * prelevo il div dell'ultima risposta , per accodare la nuova risposta.
        */
       var last_div_answer = jQuery(this).parent().parent().find('.modal-body:last');
       
       /*
        * mando il tasto "rispondi" in attesa
        */
       var btn = jQuery(this);
       
        /*
         * richiesta ajax al server
         */
        jQuery.ajax({
          type: "POST",
          url: "http://reexon.net/wp-content/plugins/rcam/endpoint/ticket_update.php",
          data: {ticket_id : ticket_id.val(),
                action : "add_answer",
                text : answer.val(),
                staff_id : staff_id.val()
                },
          success: function(msg) {
              btn.button('reset');
                var response = parseJSON(msg);
              last_div_answer.after('<div class="modal-body answer">'
              +'<h4>'+reponse.display_name+':</h4><p style="display:inline;float:right;">'+response.date+'</p>'+answer.val()
              +  '</div>');

          },
          error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    btn.button('reset');
                    alert("Status: " + textStatus); alert("Error: " + errorThrown); 
                    alert("XHR: "+XMLHttpRequest.readyState);
                    alert("State: "+XMLHttpRequest.status);
          }
        });
       
    });

    jQuery('button[name="ticket_close"]').click(function(){

       /*
        * prelevo l'id del ticket che devo chiudere
        */
       var ticket_id = jQuery(this).parent().parent().find('td:first').text();
       
       /*
        * mando il tasto "chiusura" in attesa
        */
       var btn = jQuery(this);
       
        /*
         * richiesta ajax al server
         */
        jQuery.ajax({
          type: "POST",
          url: "http://reexon.net/wp-content/plugins/rcam/endpoint/ticket_update.php",
          data: {ticket_id : ticket_id.trim(),
                action : "close_ticket"
                },
          success: function(msg) {
              btn.button('reset');

          },
          error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    btn.button('reset');
                    alert("Status: " + textStatus); alert("Error: " + errorThrown); 
                    alert("XHR: "+XMLHttpRequest.readyState);
                    alert("State: "+XMLHttpRequest.status);
          }
        });
       
    });
    
    jQuery('button[name="add_site"]').click(function(e){
        e.preventDefault();
       /*
        * Punto al form e serializzo i dati da inviare
        */
       var dataString = jQuery('#form_addsite').serialize();
       
       alert(dataString);
       /*
        * mando il tasto "chiusura" in attesa
        */
       var btn = jQuery(this);
       
       btn.button('loading');
       
        /*
         * richiesta ajax al server
         */
        jQuery.ajax({
          type: "POST",
          url: "http://reexon.net/wp-content/plugins/rcam/endpoint/addsite_update.php",
          data: dataString,
          success: function(msg) {
              btn.button('reset');

          },
          error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    btn.button('reset');
                    alert("Status: " + textStatus); alert("Error: " + errorThrown); 
                    alert("XHR: "+XMLHttpRequest.readyState);
                    alert("State: "+XMLHttpRequest.status);
          }
        });
       
    });
});