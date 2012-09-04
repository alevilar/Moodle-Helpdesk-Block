<?php

/**
 * Form for editing HELPDESK block instances.
 *
 * @package   block_helpdek
 * @copyright 1999 onwards Martin Dougiamas (http://dougiamas.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_helpdesk extends block_base {

    function init() {
        $this->title = get_string('pluginname', 'block_helpdesk');
    }
        
    function applicable_formats() {
        return array('all' => true);
    }
    
    function instance_allow_multiple() {
        return false;
    }


    function get_content() {
        global $USER; 
        $courseid = optional_param('courseid', 0, PARAM_INTEGER);
        
        if ($courseid == SITEID) {
            $courseid = 0;
        }
        if ($courseid) {
            $course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);
            $PAGE->set_course($course);
            $context = $PAGE->context;
        } else {
            $context = get_context_instance(CONTEXT_SYSTEM);            
        }

        $this->content         =  new stdClass;

        $this->content->text = '';
	$this->content->text .= '<h1>Obtener ayuda</h1><ul>';
        
	$urlTicketAdd = new moodle_url('/mod/page/view.php?id=1');
        $urlTicketAdd = html_writer::tag('a', get_string('howtoobtainhelp', 'block_helpdesk'), array('href' => $urlTicketAdd ));        
        $this->content->text .= "<li>".$urlTicketAdd."</li>";

	
	$urlTicketAdd = new moodle_url('/blocks/helpdesk/ticket_index.php');
	$urlTicketAdd = html_writer::tag('a',  get_string('Tickets_Lists', 'block_helpdesk'), array('href' => $urlTicketAdd ));        
	$this->content->text .= '<li>'.$urlTicketAdd."</li>";
	

	$this->content->text .= '</ul>';

	if ( has_capability('block/helpdesk:admin', $context)) {
	        $this->content->text .= '<h1>Administración</h1><ul>';

                $urlTicketAdd = new moodle_url('/blocks/helpdesk/ticket_add.php');
                $urlTicketAdd = html_writer::tag('a',  get_string('nuevoticket', 'block_helpdesk'), array('href' => $urlTicketAdd ));        
                $this->content->text .= '<li>'.$urlTicketAdd."</li>";
        
		$urlTicketAdd = new moodle_url('/blocks/helpdesk/ticket_index.php?owner_id='.$USER->id);
		$urlTicketAdd = html_writer::tag('a',   get_string('myassignedtickets', 'block_helpdesk'), array('href' => $urlTicketAdd ));        
		$this->content->text .= "<li>".$urlTicketAdd."</li>";

		$urlTicketAdd = new moodle_url('/blocks/helpdesk/ticket_index.php');
		$urlTicketAdd = html_writer::tag('a',  get_string('alltickets', 'block_helpdesk'), array('href' => $urlTicketAdd ));        
		$this->content->text .= '<li>'.$urlTicketAdd."</li>";

		$urlTicketAdd = new moodle_url('/blocks/helpdesk/ticket_index.php?unassigned=1');
		$urlTicketAdd = html_writer::tag('a',  get_string('pendingtickets', 'block_helpdesk'), array('href' => $urlTicketAdd ));        
		$this->content->text .= '<li>'.$urlTicketAdd."</li>";

		$this->content->text .= '</ul>';
	}
        
        return $this->content;
    }
    
} 


