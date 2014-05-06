<div id="support" class="wprcamtab">
    <h2>Supporto</h2>
    
    
    <table class="widefat">
        <thead>
            <tr>
                <th>ID</th>
                <th>Category</th>
                <th>Titolo</th>
                <th>Open Time</th>
                <th>id_site</th>
                <th>Stato</th>
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody>

            <?php
            global $wpdb;

            $result = $wpdb->get_results("SELECT * FROM " . TABLE_TICKET ." JOIN ".TABLE_TICKET_CATEGORY . " ON category_id = id_category",ARRAY_A);
            
            foreach ($result as $ticket):?>
            <tr>
                <td><?php echo $ticket['id_ticket']; ?></td>
                <td><?php echo $ticket['category_name']; ?></td>
                <td><?php echo $ticket['title']; ?></td>
                <td><?php echo $ticket['open_time']; ?></td>
                <td><?php echo $ticket['site_id']; ?></td>
                <td>
                    <h5>
                         <span class="label label-success" <?php if ($ticket['status_id']==0) echo "style='display:none;'"; ?>>Aperto</span>
                         <span class="label label-danger"  <?php if ($ticket['status_id']==1) echo "style='display:none;'"; ?>>Chiuso</span>
                    </h5>
                </td>
                <td>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#ticket_<?php echo $ticket['id_ticket'];?>">
                        <i class="fa fa-eye"></i> Visualizza
                   </button>
                    
                    <!-- CHIUDI TICKET - bottone di chiusura ticket -->
                    <button class="btn btn-danger" name="ticket_close"
                            <?php if($ticket['status_id'] == 0) echo "style='display:none'"; ?>
                    >
                        <i class="icon-remove"></i> Chiudi
                   </button>
                    
                    <!-- APRI TICKET - bottone di ri-apertura del ticket -->
                    <button class="btn btn-success" name="ticket_open"
                            <?php if($ticket['status_id'] == 1) echo "style='display:none'"; ?>
                    >
                        <i class="icon-circle-blank"></i> Apri
                   </button>
                    
                </td>
            </tr>
            
            <?php
            endforeach;
            ?>

        </tbody>
    </table>
    
    <?php foreach ($result as $ticket): ?>
        
    <div class="modal fade" id="ticket_<?php echo $ticket['id_ticket']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel">Titolo: <?php echo $ticket['title']; ?></h4>
          </div>
          <div class="modal-body">
            <?php echo $ticket['text']; ?>
          </div>
          <?php
            $results = $wpdb->get_results('SELECT * FROM '.TABLE_TICKET . ' JOIN '.TABLE_TICKET_ANSWER .' ON ticket_id = id_ticket JOIN '. TABLE_USERS ." ON ID = staff_id",ARRAY_A);
            foreach($results as $ticket_answer):
          ?>
          <div class="modal-body answer">
              <h4><?php echo $ticket_answer['display_name'];?></h4><p style="display:inline;float:right;"><?php echo $ticket_answer['answer_date'];?></p>
            <?php echo $ticket_answer['answer_text']; ?>
          </div>
            <?php endforeach; ?>
          <div class="modal-answer">
              <h4>Rispondi</h4>
              <textarea class="form-control" rows="10"></textarea>
          </div>
          <div class="modal-footer">
            <input type="hidden" name ="ticket_id" value='<?php echo $ticket['id_ticket'];?>'>
            <input type="hidden" name="staff_id" value='<?php echo get_current_user_id(); ?>'>
            <button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
            <button name="answer_ticket" type="button" class="btn btn-primary" data-loading-text="Attendi...">Rispondi</button>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
</div>