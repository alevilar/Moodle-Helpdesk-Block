<?php

defined('MOODLE_INTERNAL') || die();


function xmldb_block_helpdesk_install () {
	global $DB;

	$record = new stdClass();
        $record->name   = 'Pendiente';
        $lastinsertid = $DB->insert_record('block_helpdesk_states', $record, $returnId = true);

	$record = new stdClass();	
        $record->name   = 'Asignado';
        $lastinsertid = $DB->insert_record('block_helpdesk_states', $record, $returnId = true);

	$record = new stdClass();	
        $record->name   = 'Resuelto';
        $lastinsertid = $DB->insert_record('block_helpdesk_states', $record, $returnId = true);

	
}
