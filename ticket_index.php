<?php
// en DESARROLLO uso este
$config = '/var/www/moodle22/config.php';
//$config = dirname(__FILE__) . '/../../config.php';

//require_once("$CFG->libdir/formslib.php");

require_once(dirname(__FILE__).'/config.php');
require_once($config);

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
    $state_init = STATE_INIT;
    if ( !$es_admin && !empty($USER->id)) {
	   $tickets = $DB->get_records_sql("SELECT t.*, s.name as status FROM {block_helpdesk_tickets} t LEFT JOIN {block_helpdesk_states} s ON (s.id = t.stateid) WHERE t.stateid = $state_init AND t.userid = $USER->id ORDER by t.created ASC");
    } else {
	   $tickets = $DB->get_records_sql("SELECT t.*, s.name as status FROM {block_helpdesk_tickets} t LEFT JOIN {block_helpdesk_states} s on (s.id = t.stateid) WHERE t.stateid = $state_init ORDER BY t.created ASC");
    }

    
    echo "<h3>Consultas Pendientes</h3>";
    
    if (count($tickets)) {
        echo "<ul class='tickets-list'>";
        foreach ($tickets as $t) {
            $userObj = $DB->get_record('user', array('id'=>$t->userid));
      	    $url_profile = new moodle_url("/user/profile.php?id=".$t->userid);
	    $url_profile = html_writer::tag('a',  $userObj->username, array('href' => $url_profile, 'class'=>'username' ));  
            echo "<li><span class='created'>[".date('Y-m-d H:i', $t->created)."]</span> <span class='username'>$url_profile</span>: <span class='question'>$t->question</span><br /><a class='responder' href='ticket_answer?ticketid=$t->id'>responder</a></li>";
        }
        echo "</ul>";
    } else {
        echo "<div class='notice'>".get_string('nothing_to_reply', 'block_helpdesk')."</div>";
    }        

echo $OUTPUT->footer();
