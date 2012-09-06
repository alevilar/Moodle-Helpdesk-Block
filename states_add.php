<?php

require_once(dirname(__FILE__).'/config.php');

require_login();

$context = get_context_instance(CONTEXT_SYSTEM);
$PAGE->set_context($context);
 
$PAGE->set_url('/blocks/helpdesk/states_add.php');
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
    

if ( !empty($_POST['state_name']) ) {
     $state = new stdClass();
     $state->name = $_POST['state_name'];
     $DB->insert_record('block_helpdesk_states', $state);
     
     echo "<h4>".get_string('state_saved_msg', 'block_helpdesk') ."</h4>";
} else {
?>

<h3 style="text-transform: capitalize"><?php echo get_string('add'). ' ' . get_string('state', 'block_helpdesk');?></h3>

<form method="post" action="states_add.php">    
    <input name="state_name" type="text" />
    <input type="submit" />
</form>


<?php
}

echo $OUTPUT->footer();
