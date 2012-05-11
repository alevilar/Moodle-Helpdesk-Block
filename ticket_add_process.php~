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
    
    if ( !empty($_POST['ticket_question']) ) {
        $answ = $_POST['ticket_question'];
        
        $record = new stdClass();
        $record->userid   = $USER->id;
        $record->question = $answ;
        $record->created  = time();
	$record->stateid = STATE_INIT; // status init
        $lastinsertid = $DB->insert_record('block_helpdesk_tickets', $record, $returnId = true);

        // si hubo error al guardar...
        if (!$lastinsertid) {
            echo "Error al guardar, por favor intente nuevamente.";
            echo $OUTPUT->footer();
            die;
        }
    }
?>

Gracias! responderemos a la brevedad
<?php

echo $OUTPUT->footer();
