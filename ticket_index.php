<?php
// en DESARROLLO uso este
$config = '/var/www/moodle22/config.php';
//$config = dirname(__FILE__) . '/../../config.php';

//require_once("$CFG->libdir/formslib.php");

require_once($config);

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
    
    $subSc = "SELECT ticketid FROM {block_helpdesk_answers} GROUP BY ticketid";
    $tickets = $DB->get_records_sql("SELECT * FROM {block_helpdesk_tickets} WHERE id NOT IN ($subSc) ORDER by created DESC");
    
    echo "<ul class='tickets-list'>";
    foreach ($tickets as $t) {
        $userObj = $DB->get_record('user', array('id'=>$t->userid));
        echo "<li><div class='header'><span class='username'>$userObj->username</span><a href='ticket_answer?ticketid=$t->id'>responder</a></div>$t->question</li>";
    }
    echo "</ul>";

echo $OUTPUT->footer();