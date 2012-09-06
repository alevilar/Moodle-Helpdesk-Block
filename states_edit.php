<?php

require_once(dirname(__FILE__).'/config.php');

require_login();

$context = get_context_instance(CONTEXT_SYSTEM);
$PAGE->set_context($context);
 
$PAGE->set_url('/blocks/helpdesk/states_edit.php');
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
    

if ( !empty($_POST['state_name']) && !empty($_POST['state_id']) ) {
     $state = new stdClass();
     $state->id = $_POST['state_id'];
     $state->name = $_POST['state_name'];
     $DB->update_record('block_helpdesk_states', $state);
     
     echo "<h4>".get_string('state_saved_msg', 'block_helpdesk') ."</h4>";
} else {
    if (empty($_GET['state_id'])) die('error, not state_id passed in params');
    
    $state = $DB->get_record('block_helpdesk_states', array('id' => $_GET['state_id']));
?>

<h3 style="text-transform: capitalize"><?php echo get_string('edit'). ' ' . get_string('state');?></h3>

<form method="post" action="states_edit.php">
    <input name="state_id" type="hidden" value="<?php echo $_GET['state_id']?>"/>
    <input name="state_name" type="text" value="<?php echo $state->name?>"/>
    <input type="submit" />
</form>


<?php
}

echo $OUTPUT->footer();
