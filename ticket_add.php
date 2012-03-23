<?php
// en DESARROLLO uso este
$config = '/var/www/moodle22/config.php';

//$config = dirname(__FILE__) . '/../../config.php';

require_once($config);

require_login();



$context = get_context_instance(CONTEXT_SYSTEM);
$PAGE->set_context($context);
 
$PAGE->set_url('/blocks/helpdesk/ticket_add.php');
$PAGE->set_heading($SITE->fullname);
$PAGE->set_pagelayout('course');
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
    
echo "A la sarlaaangaa";

echo "<ul>";
// probando seguridad
        if (has_capability('block/helpdesk:createticket', $context)) {
            echo "<li>PUDO crear ticket</li>";
        } else {
            echo "<li>NOOO PUDO crear ticket</li>";
        }
        
        if (has_capability('block/helpdesk:admin', $context)) {
            echo "<li>PUDO administrar</li>";
        } else {
            echo "<li>NO PUDO administrar</li>";
        }
        
echo "</ul>";

echo $OUTPUT->footer();