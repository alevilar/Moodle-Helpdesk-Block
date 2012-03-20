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


    public function get_content() {
	    if ($this->content !== null) {
	      return $this->content;
	    }
	 	
	    $email = 'no-email';
	    if (! empty($this->config->email)) {
		$email = $this->config->email;
	    }

	    $this->content         =  new stdClass;
	    $this->content->text   = 'El mail es: '.$email;
	    $this->content->footer = '-**-- Footer here...';
	 
	    return $this->content;
    }
}   // Here's the closing bracket for the class definition
