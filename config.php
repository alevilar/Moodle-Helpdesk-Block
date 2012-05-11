<?php

// en DESARROLLO uso este
$config = '/var/www/moodle22/config.php';
//$config = dirname(__FILE__) . '/../../config.php';
require_once($config);


define('STATE_INIT',1);
define('STATE_PROCESS',2);
define('STATE_SOLVED',3);


function send_msg_on_solved ($user_from, $user_to, $ticketid) {
	global $DB;
	$userFrom = $DB->get_record('user', array('id'=>$user_from));
	$userTo = $DB->get_record('user', array('id'=>$user_to));

	$urlTicketAdd = new moodle_url("/blocks/helpdesk/ticket_answer?ticketid=$ticketid");
	$urlTicketAdd = html_writer::tag('a',  'Consultas Pendientes', array('href' => $urlTicketAdd ));   
	$messageid = message_post_message($userFrom, $userTo, "Te respondieron de la Mesa de Ayuda. Debes ir a Ayuda->Soporte Técnico->Mis Consultas", FORMAT_MOODLE);

}
