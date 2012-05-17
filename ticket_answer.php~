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
	$ticket = $DB->get_record_sql("SELECT t.*, s.name as state from {block_helpdesk_tickets} t LEFT JOIN {block_helpdesk_states} s on (s.id = t.stateid) WHERE t.id = $tt"	);

    } else {
	die("no se paso el ID del ticket");
    }
//    $parname, $default=NULL, $type=PARAM_CLEAN
       
    $mensajeError = '';
    if ( !empty($_POST['ticketid']) ) {
    	if ( !empty($_POST['ticket_answer']) ) {
		$answ = $_POST['ticket_answer'];
		
		$record = new stdClass();
		$record->userid   = $USER->id;
		$record->answer = $answ;
		$record->ticketid = $_POST['ticketid'];
		$record->created  = time();
		$lastinsertid = $DB->insert_record('block_helpdesk_changes', $record, $returnId = true);
	
		if ( !empty($_POST['stateid'])) { 
		    $DB->set_field('block_helpdesk_tickets', 'stateid', $_POST['stateid'], array('id' => $_POST['ticketid']));
		}

		// si hubo error al guardar...
		if (!$lastinsertid) {
		    echo "Error al guardar, por favor intente nuevamente.";
		    echo $OUTPUT->footer();
		    die;
		}
		send_msg_on_solved ($USER->id, $ticket->userid, $ticket->id);
	    } else {
		$mensajeError = "Debe ingresar una respuesta";
	    }	    
	}
        
    if (!empty($tt)) {
        
	echo "<div class='state-$ticket->stateid'>$ticket->state</div>";
        
        $answers = $DB->get_records('block_helpdesk_answers', array('ticketid'=>$tt));

	$userObj = $DB->get_record('user', array('id'=> $ticket->userid));
	$url_profile = new moodle_url("/user/profile.php?id=$ticket->userid");
	$url_profile = html_writer::tag('a',  $userObj->username, array('href' => $url_profile, 'class'=>'username' ));        
	    
	
        echo "<p><span class='username'>$url_profile:</span> <span class='ticket-question'>$ticket->question</span></p>";       
	
	if ( count($answers) ) {
		echo "<h4>Respuestas</h4>";
		echo "<ul>";
		foreach ($answers as $a) {
	
			$userObj = $DB->get_record('user', array('id'=> $a->userid));
			$url_profile = new moodle_url("/user/profile.php?id=$a->userid");
			$url_profile = html_writer::tag('a',  $userObj->username, array('href' => $url_profile, 'class'=>'username' ));        
		    
		    echo "<li>$url_profile: <span class='answers'>$a->answer<span></li>";
		}
		echo "</ul>";
	}

	$states = $DB->get_records_sql('SELECT * FROM {block_helpdesk_states} WHERE id > 1');

        
	if ( $ticket->stateid != STATE_SOLVED ) {
	?>
	
	<h4>Añadir Respuesta</h4>
        <form method="post" action="ticket_answer.php?ticketid=<?php echo $tt; ?>" name="answerform">            
            <input type="hidden" value="<?php echo $tt; ?>" name="ticketid"></input>
	    <?php if ( $mensajeError ) echo "<div class='error'>$mensajeError</div>"?>
            <textarea cols="80" rows="8" name="ticket_answer"></textarea>
		
	    <div>
	    <?php

		if ( has_capability('block/helpdesk:admin', $context) ) {
			foreach ($states as $s) {				
				echo "		<input type='radio' name='stateid' value='$s->id'>$s->name<br>";
	    	 	} ?>
	    <?php }?>
	    </div>	
	    
            <input type="submit" value="Enviar"></input>
	    <?php 

		$urlTicketAdd = new moodle_url('/blocks/helpdesk/ticket_index.php'); 
		?>
            <input type="button" value="Cancelar" onclick="window.location='<?php echo $urlTicketAdd?>'"></input>
        </form>
        <?php
	}
    }
    
echo $OUTPUT->footer();
