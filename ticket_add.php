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
    
?>

<h3><?php echo get_string('open_new_ticket', 'block_helpdesk');?></h3>

<?php echo get_string('open_new_ticket_form_description', 'block_helpdesk');?>

<br>
<br>
<form method="post" action="ticket_add_process.php">
    <div>
        <label><?php echo get_string('Subject', 'block_helpdesk' ); ?>:</label><br>
        <input name="ticket_subject" maxlength="70"/>
    </div>
    <div>
        <label><?php echo get_string('Question', 'block_helpdesk' ); ?>:</label><br>
    <textarea cols="80" rows="8" name="ticket_question"></textarea>
    </div>
	<br />
    <?php echo get_string('priority_ask', 'block_helpdesk')?>: 
    <select name="priority">
	<?php 
		$first = true;
	
		foreach ( $priorities as $k=>$p ) {
			echo "<option value='$k'>$p</option>";
		}
	?>
    </select>
	<br /><br />
    <input type="submit" value="Enviar"></input>
</form>
<?php

echo $OUTPUT->footer();
