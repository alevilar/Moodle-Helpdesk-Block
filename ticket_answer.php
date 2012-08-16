<?php

require_once(dirname(__FILE__).'/config.php');


require_login();



$context = get_context_instance(CONTEXT_SYSTEM);
$PAGE->set_context($context);
 
$PAGE->set_url('/blocks/helpdesk/ticket_add.php');
$PAGE->set_heading($SITE->fullname);
$PAGE->set_pagelayout('frontpage');
$PAGE->set_title(get_string('helpdesk', 'block_helpdesk'));
$PAGE->navbar->add(get_string('helpdesk', 'block_helpdesk'));


if (!empty($notificationerror)) {
    echo $OUTPUT->header();
    echo $OUTPUT->heading(get_string('helpdesk', 'block_helpdesk'), 3, 'main');
    echo $OUTPUT->notification($notificationerror);
    echo $OUTPUT->footer();
    die();
}

echo $OUTPUT->header();
    echo $OUTPUT->heading(get_string('helpdesk', 'block_helpdesk'), 3, 'main');
    
    $tt = optional_param('ticketid', $default=NULL, $type=PARAM_CLEAN);

    if ( !empty($tt) ) {
	$ticket = get_ticket($tt);
    } else {
	die("no se paso el ID del ticket");
    }
       
    $mensajeError = '';
    if ( !empty($_POST['ticketid']) ) {
		$answ = "";
		if ( !empty($_POST['ticket_answer']) ) {
			$answ = $_POST['ticket_answer'];
		}

		// $record is de hepldesk_changes record
		$record = new stdClass();
		$record->userid   = $USER->id;

		$record->text = $answ;
		$record->ticketid = $_POST['ticketid'];
		$record->created  = time();


		// User has make a comment or response
		if ( !empty($answ) ) {
			$record->changetypeid = CHANGE_TYPE_COMMENT;

			$record->text .= $answ ;
			if ( !empty($record->text) ) {
			    $lastinsertid = $DB->insert_record('block_helpdesk_changes', $record, $returnId = true);

			    // si hubo error al guardar...
			    if (!$lastinsertid) {
				echo "Error al guardar, por favor intente nuevamente.";
				echo $OUTPUT->footer();
				die;
			    }
			}
		}

		
		// Owner change logic
		if ( !empty($_POST['ownerid']) &&  $_POST['ownerid'] != $ticket->ownerid) {	

			$newOwnerObj = $DB->get_record('user', array('id'=> $_POST['ownerid']));
			$newOwnerObjName = $newOwnerObj->username;		

			if (!empty($ticket->ownerid)) {
				$oldOwnerObj = $DB->get_record('user', array('id'=> $ticket->ownerid));
				$oldOwnerObjName = $oldOwnerObj->username;									
				$record->text = "Se modificó la asignación de $oldOwnerObjName a $newOwnerObjName" ;
				$record->ownerid = $_POST['ownerid'];
			} else {
				$record->text = "Se asignó a $newOwnerObjName" ;
				$record->ownerid = $_POST['ownerid'];
			}

			$recordTk = new stdClass();
			$recordTk->ownerid =  $_POST['ownerid'];
			$recordTk->id = $ticket->id;
			$record->changetypeid = CHANGE_TYPE_REASSIGNAMENT;
			$DB->update_record('block_helpdesk_tickets', $recordTk);

			if ( !empty($record->text) ) {
			    $lastinsertid = $DB->insert_record('block_helpdesk_changes', $record, $returnId = true);

			    // si hubo error al guardar...
			    if (!$lastinsertid) {
				echo "Error al guardar, por favor intente nuevamente.";
				echo $OUTPUT->footer();
				die;
			    }
			}
		}


		// State change Logic
		if ( !empty($_POST['stateid']) &&  $_POST['stateid'] != $ticket->stateid) {
			$oldStateObj = $DB->get_record('block_helpdesk_states', array('id'=> $ticket->stateid));
			$newStateObj = $DB->get_record('block_helpdesk_states', array('id'=> $_POST['stateid']));
			
			$record->text = "Se modificó el estado de $oldStateObj->name a $newStateObj->name." ;			
			$record->changetypeid = CHANGE_TYPE_STATE_CHANGE;

			$recordTk = new stdClass();
			$recordTk->stateid = $_POST['stateid'];
			$recordTk->id = $ticket->id;
			$DB->update_record('block_helpdesk_tickets', $recordTk);

			if ( !empty($record->text) ) {
			    $lastinsertid = $DB->insert_record('block_helpdesk_changes', $record, $returnId = true);

			    // si hubo error al guardar...
			    if (!$lastinsertid) {
				echo "Error al guardar, por favor intente nuevamente.";
				echo $OUTPUT->footer();
				die;
			    }
			}
		}
			
		// send email or moodle message
		send_msg_on_change($USER->id, $ticket->authorid, $ticket->id);
	    
		// on empty form submit
		if ( empty($record->text) ) {
		    $mensajeError = "Debe ingresar una respuesta";
		}

		$ticket = get_ticket($tt);    
	}
        

	echo "<div class='state-$ticket->stateid'>$ticket->state</div>";
        
        $answers = $DB->get_records('block_helpdesk_changes', array('ticketid'=>$tt));

	$userObj = $DB->get_record('user', array('id'=> $ticket->authorid));
	$url_profile = new moodle_url("/user/profile.php?id=$ticket->authorid");
	$url_profile = html_writer::tag('a',  $userObj->username, array('href' => $url_profile, 'class'=>'username' ));        
	    	
        echo "<p><span class='username'>$url_profile:</span> <span class='ticket-question'>$ticket->question</span></p>";       
	
	if ( count($answers) ) {
		echo "<h4>Respuestas</h4>";
		echo "<ul>";
		foreach ($answers as $a) {
	
			$userObj = $DB->get_record('user', array('id'=> $a->userid));
			$url_profile = new moodle_url("/user/profile.php?id=$a->userid");
			$url_profile = html_writer::tag('a',  $userObj->username, array('href' => $url_profile, 'class'=>'username' ));        
		    
		    echo "<li>$url_profile: <span class='answers'>$a->text<span></li>";
		}
		echo "</ul>";
	}

	$states = $DB->get_records('block_helpdesk_states');        
	
	?>
	
	<h4>Acciones</h4>

	<script type="text/javascript">
		var Helpdesk = {
			flag1 : true, 
			flag2 : true,
			flags : {},

			toggle: function(elementId) {
				if (!this.flags.hasOwnProperty(elementId) ) {
					this.flags[elementId] = true;
				}
				if (this.flags[elementId]) {
					document.getElementById(elementId).style.display='block';
				} else {
					document.getElementById(elementId).style.display='none';
				}
				this.flags[elementId] = !this.flags[elementId];
				return this.flags[elementId];
			},

			changeVisibility1 : function() {
				this.flag1 = this.toggle('mostrar_estado');

			},

			changeVisibility2 : function() {
				this.flag2 = this.toggle('mostrar_tipo');
			}
			
		};
	
		
		
	</script>

        <form method="post" action="ticket_answer.php?ticketid=<?php echo $tt; ?>" name="answerform">            
            <input type="hidden" value="<?php echo $tt; ?>" name="ticketid"></input>
	    <?php if ( $mensajeError ) echo "<div class='error'>$mensajeError</div>"?>

	    <div  style="float: left;width: 34%;">
		<h4><a href="#escribir_respuesta" onclick="Helpdesk.toggle('escribir_respuesta'); return false;">Escribir Respuesta</a></h4>
		<div id="escribir_respuesta" style="display:none; background: #fff;">
	            	<textarea cols="80" rows="8" name="ticket_answer"></textarea>
		</div>
	   </div>
		
	    <div style="float: left;width: 33%;">
		<h4><a href="#mostrar_estado" onclick="Helpdesk.changeVisibility1(); return false;">Modificar Estado</a></h4>
		    <div id="mostrar_estado" style="display: none; background: #fff;">
		    <?php

			if ( has_capability('block/helpdesk:admin', $context) ) {
				foreach ($states as $s) {
					$checked = '';
					if ( $s->id == $ticket->stateid) {
						$checked = 'checked="checked"';					
					}
					echo "		<input type='radio' name='stateid' value='$s->id' $checked>$s->name<br>";
		    	 	}
			 }
		    ?></div>
	    </div>
	    

	    <div style="float: right;width: 33%;">
		<h4><a href="#mostrar_tipo" onclick="Helpdesk.changeVisibility2(); return false;">Modificar Asignación</a></h4>
		<div id="mostrar_tipo" style="display: none;background: #fff;">
	    <?php
		$users = get_users_by_capability($context, 'block/helpdesk:admin');

		echo "<select name='ownerid'>";
			echo "<option value='0'>Sin Cambios</option>";
			foreach ( $users as $u) {
				$selectedActive = '';
				if ($ticket->ownerid == $u->id ) {
					$selectedActive = 'selected="selected"';
				}
				echo "<option value='$u->id' $selectedActive>$u->username</option>";
			}
		echo "</select>";
	    ?>
	    	</div>
	    </div>
	    
	    <div style="display: block; clear: both; padding-bottom: 20px; top: 20px; position: relative;">
		    <input type="submit" value="Enviar" />
		    <?php 

			$urlTicketAdd = new moodle_url('/blocks/helpdesk/ticket_index.php'); 
			?>
		    <input type="button" value="Cancelar y Volver" onclick="window.location='<?php echo $urlTicketAdd?>'" />
	    </div>
        </form>
        <?php	
    
echo $OUTPUT->footer();
