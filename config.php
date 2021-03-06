<?php

$config = dirname(__FILE__) . '/../../config.php';
require_once($config);


/**
 * Ticket's limit in list, for pagination porpouse
 */
define('TICKET_INDEX_LIMIT',20);



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
// first is default, that's because array should begin with "NORMAL" priority
$priorities = array(
    1 => 'normal',
    0 => 'low',   
    2 => 'hight',
);

/*         EOF: Statics Vars Definition     */




/**
* Send a Moodle-message to the user alerting when someomge responds
*
* @user_from_id ID of sender
* @user_to_id ID of received user
* @ticketid ID of the ticket
*/
function send_msg_on_change ( $user_from_id, $user_to_id, $ticketid, $msg ) {
	global $DB;
	$userFrom = $DB->get_record('user', array('id'=>$user_from_id));
	$userTo = $DB->get_record('user', array('id'=>$user_to_id));

	$urlTicketAdd = new moodle_url("/blocks/helpdesk/ticket_answer?ticketid=".$ticketid);
//	$urlTicketAdd = html_writer::tag('a',  'Consultas Pendientes', array('href' => $urlTicketAdd ));   
//	$messageid = message_post_message($userFrom, $userTo, "Te respondieron de la Mesa de Ayuda. Debes ir a Ayuda->Soporte Técnico->Mis Consultas", FORMAT_MOODLE);
        $messageid = message_post_message($userFrom, $userTo, 
        get_string('ticket_responde_msg', 'block_helpdesk') . "($urlTicketAdd)".'
            
        '.get_string('answer', 'block_helpdesk').': 
            '.$msg, FORMAT_MARKDOWN);


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

