<?php

defined('MOODLE_INTERNAL') || die();


function xmldb_block_helpdesk_install () {
	global $DB;


	// Insert Default STATES
	$record = new stdClass();
        $record->name   = 'Open';
        $lastinsertid = $DB->insert_record('block_helpdesk_states', $record, $returnId = true);
	
	$record = new stdClass();	
        $record->name   = 'Closed Resolved';
        $lastinsertid = $DB->insert_record('block_helpdesk_states', $record, $returnId = true);

	$record = new stdClass();	
        $record->name   = 'Closed Duplicated';
        $lastinsertid = $DB->insert_record('block_helpdesk_states', $record, $returnId = true);

	$record = new stdClass();	
        $record->name   = 'Closed Invalid';
        $lastinsertid = $DB->insert_record('block_helpdesk_states', $record, $returnId = true);



	// Insert DEFAULT Change Types
	$record = new stdClass();
        $record->name   = 'comment';
        $lastinsertid = $DB->insert_record('block_helpdesk_change_types', $record, $returnId = true);
	
	$record = new stdClass();	
        $record->name   = 'state_change';
        $lastinsertid = $DB->insert_record('block_helpdesk_change_types', $record, $returnId = true);

	$record = new stdClass();	
        $record->name   = 'assignament_changed';
        $lastinsertid = $DB->insert_record('block_helpdesk_change_types', $record, $returnId = true);
        
        
        $record = new stdClass();	
        $record->name   = 'priority_changed';
        $lastinsertid = $DB->insert_record('block_helpdesk_change_types', $record, $returnId = true);


	
}
