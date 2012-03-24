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
    
    $tt = optional_param('ticketid', $default=NULL, $type=PARAM_CLEAN);
//    $parname, $default=NULL, $type=PARAM_CLEAN
    
    if ( !empty($_POST['ticket_answer']) ) {
        $answ = $_POST['ticket_answer'];
        
        $record = new stdClass();
        $record->userid   = $USER->id;
        $record->answer = $answ;
        $record->ticketid = $_POST['ticketid'];
        $record->created  = time();
        $lastinsertid = $DB->insert_record('block_helpdesk_answers', $record, $returnId = true);

        // si hubo error al guardar...
        if (!$lastinsertid) {
            echo "Error al guardar, por favor intente nuevamente.";
            echo $OUTPUT->footer();
            die;
        }
    }
    
    
    if (!empty($tt)) {
        $ticket = $DB->get_record('block_helpdesk_tickets', array('id'=>$tt));
        
        $answers = $DB->get_records('block_helpdesk_answers', array('ticketid'=>$tt));
        echo "<div class='ticket-question'>$ticket->question</div>";       
        echo "<ul>";
        foreach ($answers as $a) {
            echo "<li>$a->answer</li>";
        }
        echo "</ul>";
        
        ?>
        <form method="post" action="ticket_answer.php?ticketid=<?php echo $tt?>" name="answerform">            
            <input type="hidden" value="<?php echo $tt?>" name="ticketid"></input>
            <textarea cols="80" rows="8" name="ticket_answer"></textarea>
            <input type="submit" value="Enviar"></input>
        </form>
        <?php
    }
    
echo $OUTPUT->footer();