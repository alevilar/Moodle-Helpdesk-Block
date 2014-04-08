<?php

require_once(dirname(__FILE__).'/config.php');


require_login();


$p = $DB->get_record('block_instances', array('blockname' => 'helpdesk'), $fields='*', IGNORE_MULTIPLE);
    
    $context = get_context_instance(CONTEXT_BLOCK, $p->id );
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
    
    if ( !empty($_GET['ticket_id']) ) {
       
    
        // si hubo error al guardar...
        if (!$DB->delete_records('block_helpdesk_tickets', array('id' => $_GET['ticket_id'])) ) {
            echo get_string("error_save_to_db");
            echo $OUTPUT->footer();
            die;
        }
    }
?>

<p>
<?php echo get_string('ticket_deleted', 'block_helpdesk');?>
</p>
<?php

echo $OUTPUT->footer();
