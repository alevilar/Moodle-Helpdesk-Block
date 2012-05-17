<?php

require_once(dirname(__FILE__).'/config.php');

require_login();


$context = get_context_instance(CONTEXT_SYSTEM);
$PAGE->set_context($context);


$es_admin = false;
if (has_capability('block/helpdesk:admin', $context)) {
	$es_admin =  true;
}

 
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
    
    $tickets = array();
    $state_init = STATE_OPEN;
    if ( !$es_admin && !empty($USER->id)) {
	   $tickets = $DB->get_records_sql("SELECT t.*, s.name as status FROM {block_helpdesk_tickets} t LEFT JOIN {block_helpdesk_states} s ON (s.id = t.stateid) WHERE t.stateid = $state_init AND t.authorid = $USER->id ORDER by t.created ASC");
    } else {
	   $tickets = $DB->get_records_sql("SELECT t.*, s.name as status FROM {block_helpdesk_tickets} t LEFT JOIN {block_helpdesk_states} s on (s.id = t.stateid) WHERE t.stateid = $state_init ORDER BY t.created ASC");
    }
    
    echo "<h3>Lista de Tickets</h3>";

    echo "<table><tr><th>Fecha</th><th>Autor</th><th>Owner</th><th>Estado</th><th>descripción</th><th>&nbsp;</th></tr>";	
    
    if (count($tickets)) {
        echo "<tr class='tickets-list'>";
        foreach ($tickets as $t) {      
	  echo "<tr>";      

            echo "<td>".date('Y-m-d H:i', $t->created)."</td>";

	    $userObj = $DB->get_record('user', array('id'=>$t->authorid));
      	    $url_profile = new moodle_url("/user/profile.php?id=".$t->authorid);
	    $url_profile = html_writer::tag('a',  $userObj->username, array('href' => $url_profile, 'class'=>'username' ));  
	    echo "<td>$url_profile</td>";
	
	    if ( $t->ownerid ) {
	        $userObj = $DB->get_record('user', array('id'=>$t->ownerid));
      	        $url_profile = new moodle_url("/user/profile.php?id=".$t->authorid);
	        $url_profile = html_writer::tag('a',  $userObj->username, array('href' => $url_profile, 'class'=>'username' )); 
	    } else {
		$url_profile = 'sin asignar';
	    }
	    echo "<td>$url_profile</td>";

	    echo "<td>".get_string($t->status, 'block_helpdesk')."</td>";

	    echo "<td>".substr( $t->question, 0, 30 )."...</td>";

	    echo "<td><a class='responder' href='ticket_answer?ticketid=$t->id'>".get_string('go')."</a></td>";
        }
        echo "</tr>";
    } else {
        echo "<div class='notice'>".get_string('nothing_to_reply', 'block_helpdesk')."</div>";
    }        

    echo "</table>";

echo $OUTPUT->footer();
