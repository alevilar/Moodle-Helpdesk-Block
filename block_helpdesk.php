<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

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
        if ($courseid) {
            $course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);
            $PAGE->set_course($course);
            $context = $PAGE->context;
        } else {
            $context = get_context_instance(CONTEXT_SYSTEM);            
        }

        $this->content         =  new stdClass;
                
        $urlTicketAdd = new moodle_url('blocks/helpdesk/ticket_add.php');
        $urlTicketAdd = html_writer::tag('a',  get_string('nuevoticket', 'block_helpdesk'), array('href' => $urlTicketAdd ));        
        $this->content->text = '<br />'.$urlTicketAdd;
        
        $this->content->footer = '-- Footer here --';

        return $this->content;
    }
    
} 
