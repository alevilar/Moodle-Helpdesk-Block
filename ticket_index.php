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

   $sql_base = "SELECT t.*, s.name as status %s FROM {block_helpdesk_tickets} t 
			LEFT JOIN {block_helpdesk_states} s ON (s.id = t.stateid) %s
			WHERE 1=1 %s ORDER by t.created ASC";


    $sql = '';
    if ( !$es_admin && !empty($USER->id) ) {
	   $fields = '%s';
	   $join = '%s';
	   $where = " AND t.authorid = $USER->id %s ";
	  $sql_base = sprintf($sql_base, $fields,  $join, $where) ;
    }

    if ( !empty($_GET) && !empty($_GET['stateid']) ) {
	 //$fields = ", s.name as status %s";
	// $join = "LEFT JOIN {block_helpdesk_states} s ON (s.id = t.stateid) %s "; 
	 $aaaa = $_GET['stateid'];
	 $where = " AND t.stateid =  $aaaa %s ";

	$sql_base = sprintf($sql_base, "", "", $where) ;
    }

    $sql = sprintf($sql_base, "", "", "");
    $tickets = $DB->get_records_sql($sql);

    


    echo "<h4>Filtros para búsqueda avanzada</h4>";
?>	
	<p>
	<form action="ticket_index.php" method='get'>
		<label><?php echo get_string('Author','block_helpdesk')?></label><input type='text' name='authorname' />
		<label><?php echo get_string('Owner','block_helpdesk')?></label><input type='text' name='ownername' />
		<br />

		<label><?php echo get_string('Unassigned','block_helpdesk')?></label><input type='checkbox' name='unassigned' />

		<label><?php echo get_string('State','block_helpdesk')?></label>
			<select type='text' name='stateid'/>
				<option value='0'>Todos</option>
				<?php
					$states = $DB->get_records('block_helpdesk_states');
					
					foreach ($states as $s) {
						echo "    <option value='$s->id'>$s->name</option>";
					}
				?>
			</select>

		<br />
		<input type="submit" value="Filtrar"/>

	</form>
	</p>
	<?php


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

	    echo "<td>$t->status</td>";

	    echo "<td>".substr( $t->question, 0, 30 )."...</td>";

	    echo "<td><a class='responder' href='ticket_answer?ticketid=$t->id'>".get_string('go')."</a></td>";
        }
        echo "</tr>";
    } else {
        echo "<div class='notice'>".get_string('nothing_to_reply', 'block_helpdesk')."</div>";
    }        

    echo "</table>";

echo $OUTPUT->footer();
