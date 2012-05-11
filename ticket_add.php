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

<h3>Crear Nueva Consulta</h3>
A continuación puede ingresar su inquietud que ser atendida por un administrador del sistema.

<form method="post" action="ticket_add_process.php">
    <textarea cols="80" rows="8" name="ticket_question"></textarea>
	<br />
    <input type="submit" value="Enviar"></input>
</form>
<?php

echo $OUTPUT->footer();
