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

		// $record is de hepldesk_changes record
		$record = new stdClass();
		$record->userid   = $USER->id;

		$record->ticketid = $_POST['ticketid'];
		$record->created  = time();
<<<<<<< HEAD


		// User has make a comment or response
		if ( !empty($answ) ) {
			$record->changetypeid = CHANGE_TYPE_COMMENT;

			$record->text .= $answ ;
=======


		// User has make a comment or response
		if ( !empty($_POST['ticket_answer']) ) {
			$record->changetypeid = CHANGE_TYPE_COMMENT;

			$record->text = $_POST['ticket_answer'] ;
>>>>>>> b35c814d1e8891f38780093b26bc9f62d15f1f64
			if ( !empty($record->text) ) {
			    $lastinsertid = $DB->insert_record('block_helpdesk_changes', $record, $returnId = true);

			    // si hubo error al guardar...
			    if (!$lastinsertid) {
<<<<<<< HEAD
				echo "Error al guardar, por favor intente nuevamente.";
=======
				echo get_string('error_save', 'block_helpdesk');
>>>>>>> b35c814d1e8891f38780093b26bc9f62d15f1f64
				echo $OUTPUT->footer();
				die;
			    }
			}
		}

<<<<<<< HEAD
=======


		// User has change the priority
		if ( isset($_POST['priority']) && is_numeric($_POST['priority']) && $ticket->priority != $_POST['priority'] ) {	
			$recordTk = new stdClass();
			$recordTk->priority =  $_POST['priority'];
			$recordTk->id = $ticket->id;
			$DB->update_record('block_helpdesk_tickets', $recordTk);	

			$record->changetypeid = CHANGE_TYPE_PRIORITY;

			$record->text = sprintf(get_string('priority_changed', 'block_helpdesk'), $priorities[$ticket->priority],$priorities[$_POST['priority']]);
			if ( !empty($record->text) ) {
			    $lastinsertid = $DB->insert_record('block_helpdesk_changes', $record, $returnId = true);

			    // si hubo error al guardar...
			    if (!$lastinsertid) {
				echo get_string('error_save', 'block_helpdesk');
				echo $OUTPUT->footer();
				die;
			    }
			}	
		}


>>>>>>> b35c814d1e8891f38780093b26bc9f62d15f1f64
		
		// Owner change logic
		if ( !empty($_POST['ownerid']) &&  $_POST['ownerid'] != $ticket->ownerid) {	

			$newOwnerObj = $DB->get_record('user', array('id'=> $_POST['ownerid']));
			$newOwnerObjName = $newOwnerObj->username;

			if (!empty($ticket->ownerid)) {
				$oldOwnerObj = $DB->get_record('user', array('id'=> $ticket->ownerid));
<<<<<<< HEAD
				$oldOwnerObjName = $oldOwnerObj->username;									
				$record->text = "Se modificó la asignación de $oldOwnerObjName a $newOwnerObjName" ;
				$record->ownerid = $_POST['ownerid'];
			} else {
				$record->text = "Se asignó a $newOwnerObjName" ;
=======
				$oldOwnerObjName = $oldOwnerObj->username;	
				if ( $_POST['ownerid'] != $USER->id) {					
					$record->text = sprintf(get_string('user_assigned_change', 'block_helpdesk') , "<a href='$CFG->wwwroot/user/profile.php?id=$newOwnerObj->id' target='_blank'>$newOwnerObjName</a>");
				} else {
					$record->text = get_string('user_autoassigned_change', 'block_helpdesk') ;
				}								
				
				$record->ownerid = $_POST['ownerid'];
			} else {
				$record->text = sprintf(get_string('user_assigned_change', 'block_helpdesk') , "<a href='$CFG->wwwroot/user/profile.php?id=$newOwnerObj->id' target='_blank'>$newOwnerObjName</a>");

>>>>>>> b35c814d1e8891f38780093b26bc9f62d15f1f64
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
<<<<<<< HEAD
				echo "Error al guardar, por favor intente nuevamente.";
=======
				echo get_string('error_save', 'block_helpdesk');
>>>>>>> b35c814d1e8891f38780093b26bc9f62d15f1f64
				echo $OUTPUT->footer();
				die;
			    }
			}
		}


		// State change Logic
		if ( !empty($_POST['stateid']) &&  $_POST['stateid'] != $ticket->stateid) {
			$oldStateObj = $DB->get_record('block_helpdesk_states', array('id'=> $ticket->stateid));
			$newStateObj = $DB->get_record('block_helpdesk_states', array('id'=> $_POST['stateid']));
			
<<<<<<< HEAD
			$record->text = "Se modificó el estado de $oldStateObj->name a $newStateObj->name." ;			
=======
			$record->text = sprintf(get_string('state_changed', 'block_helpdesk'), $oldStateObj->name, $newStateObj->name );			
>>>>>>> b35c814d1e8891f38780093b26bc9f62d15f1f64
			$record->changetypeid = CHANGE_TYPE_STATE_CHANGE;

			$recordTk = new stdClass();
			$recordTk->stateid = $_POST['stateid'];
			$recordTk->id = $ticket->id;
			$DB->update_record('block_helpdesk_tickets', $recordTk);

			if ( !empty($record->text) ) {
			    $lastinsertid = $DB->insert_record('block_helpdesk_changes', $record, $returnId = true);

			    // si hubo error al guardar...
			    if (!$lastinsertid) {
<<<<<<< HEAD
				echo "Error al guardar, por favor intente nuevamente.";
=======
				echo get_string('error_save', 'block_helpdesk');
>>>>>>> b35c814d1e8891f38780093b26bc9f62d15f1f64
				echo $OUTPUT->footer();
				die;
			    }
			}
		}
			
		// send email or moodle message
		send_msg_on_change($USER->id, $ticket->authorid, $ticket->id);
	    
<<<<<<< HEAD
		// on empty form submit
		if ( empty($record->text) ) {
		    $mensajeError = "Debe ingresar una respuesta";
=======
		// show error message on empty form submit
		if ( empty($record->text) ) {
		    $mensajeError = get_string('write_response', 'block_helpdesk');
>>>>>>> b35c814d1e8891f38780093b26bc9f62d15f1f64
		}

		// get updated $ticket data
		$ticket = get_ticket($tt);    
	}
        

	?>
	<div id="helpdesk-sidebar">
		<div class="priority-block priority-<?php echo $ticket->priority ?>">
			<span class="label"><?php  echo get_string('priority', 'block_helpdesk')?>:</span>
			<span class="priority"><?php echo $priorities[$ticket->priority];?></span>
		</div>

	<?php
		echo "<div class='state-$ticket->stateid'>$ticket->state</div>";	
	?>
		
	</div>

	<?php        

        $answers = $DB->get_records('block_helpdesk_changes', array('ticketid'=>$tt));

	$userObj = $DB->get_record('user', array('id'=> $ticket->authorid));
	$url_profile = new moodle_url("/user/profile.php?id=$ticket->authorid");
	$url_profile = html_writer::tag('a',  $userObj->username, array('href' => $url_profile, 'class'=>'username' ));        
	
	    	
	?>
	<div class="question"><h4><?php echo get_string('question', 'block_helpdesk')?></h4>
			<span class='username'><?php echo $url_profile?>:</span> 
			<span class='ticket-question'><?php echo $ticket->question?></span>	
	</div>

	<div style="margin-left: 94px; margin-top: 20px;">

	<?php
	if ( count($answers) ) {
		echo "<h4>Respuestas</h4>";
		echo "<ul class='changes helpdesk-answers'  style='margin-left: 0px'>";
		foreach ($answers as $a) {
	
			$userObj = $DB->get_record('user', array('id'=> $a->userid));
			$url_profile = new moodle_url("/user/profile.php?id=$a->userid");
			$url_profile = html_writer::tag('a',  $userObj->username, array('href' => $url_profile, 'class'=>'username' ));        
		    	$date =  ' ' . date('d/m/Y H:i',$a->created);

			$responsss = ($a->changetypeid == CHANGE_TYPE_COMMENT)? get_string('responds', 'block_helpdesk').':<br>':': ';
			
		    	echo "<li class='changetype-id-$a->changetypeid'><span class='date'>$date</span><span class='username'> $url_profile $responsss</span><span class='answers'>$a->text</span></li>";
		}
		echo "</ul>";
	}

	$states = $DB->get_records('block_helpdesk_states');        
	
	?>
	
<<<<<<< HEAD
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
=======
	<h4><?php echo get_string('request_action', 'block_helpdesk');?></h4>
>>>>>>> b35c814d1e8891f38780093b26bc9f62d15f1f64

        <form method="post" action="ticket_answer.php?ticketid=<?php echo $tt; ?>" name="answerform">            
            <input type="hidden" value="<?php echo $tt; ?>" name="ticketid"></input>
	    <?php if ( $mensajeError ) echo "<div class='error'>$mensajeError</div>"?>

<<<<<<< HEAD
	    <div  style="float: left;width: 34%;">
		<h4><a href="#escribir_respuesta" onclick="Helpdesk.toggle('escribir_respuesta'); return false;">Escribir Respuesta</a></h4>
		<div id="escribir_respuesta" style="display:none; background: #fff;">
	            	<textarea cols="80" rows="8" name="ticket_answer"></textarea>
		</div>
	   </div>
		
	    <div style="float: left;width: 33%;">
		<h4><a href="#mostrar_estado" onclick="Helpdesk.changeVisibility1(); return false;">Modificar Estado</a></h4>
		    <div id="mostrar_estado" style="display: none; background: #fff;">
=======
	    <div>
		<label>Responder</label><br />
            	<textarea cols="80" rows="8" name="ticket_answer" width="25%"></textarea>
	   </div>
		
	    <?php if (has_capability('block/helpdesk:admin', $context)) { ?>
	    <div style="float: left;width: 34%;">
		<label>Modificar Estado</label><br />
>>>>>>> b35c814d1e8891f38780093b26bc9f62d15f1f64
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
		    ?>
	    </div>
	    <?php } ?>

	
	    <?php if (has_capability('block/helpdesk:admin', $context)) { ?>
	    <div style="float: left;width: 33%;">
		<label>Modificar Prioridad</label>
		    <select name="priority">
		    <?php
			if ( has_capability('block/helpdesk:admin', $context) ) {
				foreach ( $priorities as $k=>$p ) {
					$selected = '';
					if ( $ticket->priority == $k ) {
						$selected = 'selected="selected"';
					}
					echo "<option value='$k' $selected>$p</option>";
				}
			 }
		    ?></select>
		    
	    </div>
	    <?php } ?>

	    

<<<<<<< HEAD
	    <div style="float: right;width: 33%;">
		<h4><a href="#mostrar_tipo" onclick="Helpdesk.changeVisibility2(); return false;">Modificar Asignación</a></h4>
		<div id="mostrar_tipo" style="display: none;background: #fff;">
=======
  	    <?php if (has_capability('block/helpdesk:admin', $context)) { ?>
	    <div style="float: right;width: 33%;">
		<label>Asignar responsable</label>

>>>>>>> b35c814d1e8891f38780093b26bc9f62d15f1f64
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
	    <?php } ?>
	    
	    <div style="display: block; clear: both; padding-bottom: 20px; top: 20px; position: relative;">
		    <input type="submit" value="Enviar" />
		    <?php 

			$urlTicketAdd = new moodle_url('/blocks/helpdesk/ticket_index.php'); 
			?>
		    <input type="button" value="Cancelar y Volver" onclick="window.location='<?php echo $urlTicketAdd?>'" />
	    </div>
        </form>
        <?php
	echo "</div>";	
    
echo $OUTPUT->footer();
