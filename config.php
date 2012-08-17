<?php

<<<<<<< HEAD
// HACK TEMPORAL PARA DESARROLLAR YO: AleVilar
$config = '/var/www/moodle22/config.php';

=======
>>>>>>> b35c814d1e8891f38780093b26bc9f62d15f1f64
// este es el correcto
//$config = dirname(__FILE__) . '/../../config.php';
require_once($config);



/*         Statics Vars Definition         */

// State table ID's
define('STATE_OPEN',1);
define('STATE_CLOSED_RESOLVED',2);
define('STATE_CLOSED_DUPLICATED',3);
define('STATE_CLOSED_INVALID',4);

// Changes Type table ID's
define('CHANGE_TYPE_COMMENT',1);
define('CHANGE_TYPE_STATE_CHANGE',2);
define('CHANGE_TYPE_REASSIGNAMENT',3);
define('CHANGE_TYPE_PRIORITY',4);


/**
*	Priority Table
*
* 	List of availiable priorities
**/
// first is default
$priorities = array(
    1 => 'Normal',
    0 => 'Low',   
    2 => 'High',
);
/*         EOF: Statics Vars Definition     */




/**
* Send a Moodle-message to the user alerting when someomge responds
*
* @user_from_id ID of sender
* @user_to_id ID of received user
* @ticketid ID of the ticket
*/
function send_msg_on_change ( $user_from_id, $user_to_id, $ticketid ) {
	global $DB;
	$userFrom = $DB->get_record('user', array('id'=>$user_from_id));
	$userTo = $DB->get_record('user', array('id'=>$user_to_id));

	$urlTicketAdd = new moodle_url("/blocks/helpdesk/ticket_answer?ticketid=$ticketid");
	$urlTicketAdd = html_writer::tag('a',  'Consultas Pendientes', array('href' => $urlTicketAdd ));   
	$messageid = message_post_message($userFrom, $userTo, "Se respondio a tu consulta en el sistema de tickets, para ver la respusta [cliquear aca](/blocks/helpdesk/ticket_answer?ticketid=".$ticketid.")", FORMAT_MARKDOWN);

}

function __db($coso){
	echo "<pre>";
	print_r($coso);
	echo "</pre>";

}


function get_ticket($ticketid) {
	global $DB;
	return $DB->get_record_sql("SELECT t.*, s.name as state from {block_helpdesk_tickets} t LEFT JOIN {block_helpdesk_states} s on (s.id = t.stateid) WHERE t.id = $ticketid"	);
}

