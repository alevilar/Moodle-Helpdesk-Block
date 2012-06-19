<?php
require_once(dirname(__FILE__).'/config.php');

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

        $courseid = optional_param('courseid', 0, PARAM_INTEGER);
        
        if ($courseid == SITEID) {
            $courseid = 0;
        }

	if (empty($DB) || empty($PAGE)) {
		return;
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
	$this->content->text .= '<h1>Soporte Técnico</h1><ul>';
        
	$urlTicketAdd = new moodle_url('/mod/page/view.php?id=1');
        $urlTicketAdd = html_writer::tag('a',  'Obtener ayuda', array('href' => $urlTicketAdd ));        
        $this->content->text .= "<li>".$urlTicketAdd."</li>";

	
	$urlTicketAdd = new moodle_url('/blocks/helpdesk/ticket_index.php');
	$urlTicketAdd = html_writer::tag('a',  'Listado de Tickets', array('href' => $urlTicketAdd ));        
	$this->content->text .= '<li>'.$urlTicketAdd."</li>";
	

	$this->content->text .= '</ul>';

	if ( has_capability('block/helpdesk:admin', $context)) {
	        $this->content->text .= '<h1>Administración</h1><ul>';

                $urlTicketAdd = new moodle_url('/blocks/helpdesk/ticket_add.php');
                $urlTicketAdd = html_writer::tag('a',  get_string('nuevoticket', 'block_helpdesk'), array('href' => $urlTicketAdd ));        
                $this->content->text .= '<li>'.$urlTicketAdd."</li>";
        
		$urlTicketAdd = new moodle_url('/blocks/helpdesk/ticket_index.php?unassigned=on&stateid='.STATE_OPEN);
		$urlTicketAdd = html_writer::tag('a',  'Ver pendientes sin asignar', array('href' => $urlTicketAdd ));        
		$this->content->text .= "<li>".$urlTicketAdd."</li>";

		$urlTicketAdd = new moodle_url('/blocks/helpdesk/ticket_index.php');
		$urlTicketAdd = html_writer::tag('a',  'Ver mis tickets', array('href' => $urlTicketAdd ));        
		$this->content->text .= "<li>".$urlTicketAdd."</li>";

		$urlTicketAdd = new moodle_url('/blocks/helpdesk/ticket_index.php');
		$urlTicketAdd = html_writer::tag('a',  'Ver todos los tickets', array('href' => $urlTicketAdd ));        
		$this->content->text .= '<li>'.$urlTicketAdd."</li>";		

		$this->content->text .= '</ul>';
	}
        
        return $this->content;
    }
    
} 


