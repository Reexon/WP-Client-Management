/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

jQuery(document).ready(function(){
/*
 * form che gestisce l'aggiornamento tramite AJAX
 * della scheda "schedule"
 * per evitare di fare il refresh della pagina
 */
    jQuery("form").submit(function (e) {
        var dataString = jQuery(this).serialize();

        e.preventDefault();
       
        
        var tr = jQuery(this).parent();
        var td_last = tr.find('td:last-child');
        var submit_button = tr.find(':submit');
        var div_fa = td_last.find('#fontA');
        submit_button.hide();
        
        div_fa.show();
        //td_last.append("LOL");
        
        /*
         * richiesta ajax al server
         */
        jQuery.ajax({
          type: "POST",
          url: "http://reexon.net/wp-content/plugins/rcam/admin/schedule_update.php",
          data: dataString,
          success: function(msg) {
              submit_button.show();
              div_fa.hide();
         
          },
          error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    alert("Status: " + textStatus); alert("Error: " + errorThrown); 
                    alert("XHR: "+XMLHttpRequest.readyState);
                    alert("State: "+XMLHttpRequest.status);
                }
        });
    });
    
});