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
        $record->name   = 'Comment';
        $lastinsertid = $DB->insert_record('block_helpdesk_change_types', $record, $returnId = true);
	
	$record = new stdClass();	
        $record->name   = 'State Change';
        $lastinsertid = $DB->insert_record('block_helpdesk_change_types', $record, $returnId = true);

	$record = new stdClass();	
        $record->name   = 'Assignament Changed';
        $lastinsertid = $DB->insert_record('block_helpdesk_change_types', $record, $returnId = true);
        
        
        $record = new stdClass();	
        $record->name   = 'Priority Changed';
        $lastinsertid = $DB->insert_record('block_helpdesk_change_types', $record, $returnId = true);


	
}
