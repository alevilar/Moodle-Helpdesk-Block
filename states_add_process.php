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
    
    if ( !empty($_POST['ticket_question']) && !empty($_POST['ticket_subject']) ) {
        $answ = $_POST['ticket_question'];
        $subjt = $_POST['ticket_subject'];
        
        $record = new stdClass();
        $record->authorid   = $USER->id;
        $record->subject = $subjt;
        $record->priority = $_POST['priority'];
        $record->question = $answ;
        $record->created  = time();
	$record->stateid = STATE_OPEN; // status init
        $lastinsertid = $DB->insert_record('block_helpdesk_tickets', $record, $returnId = true);

        // si hubo error al guardar...
        if (!$lastinsertid) {
            echo get_string("error_save_to_db");
            echo $OUTPUT->footer();
            die;
        }
    }
?>

<p>
<?php echo get_string('ticket_added', 'block_helpdesk');?>
</p>
<?php

echo $OUTPUT->footer();
