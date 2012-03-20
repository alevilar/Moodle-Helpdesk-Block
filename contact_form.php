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

/** Configurable Reports
  * A Moodle block for creating Configurable Reports
  * @package blocks
  * @author: Juan leyva <http://www.twitter.com/jleyvadelgado>
  * @date: 2009
  */

    require_once("../../config.php");	
	
	$courseid = optional_param('courseid',SITEID,PARAM_INT);
	
	if (! $course = $DB->get_record("course",array( "id" =>  $courseid)) ) {
		print_error("No such course id");
	}

	// Force user login in course (SITE or Course)
    if ($course->id == SITEID){
		require_login();
		$context = get_context_instance(CONTEXT_SYSTEM);
	}	
	else{
		require_login($course->id);		
		$context = get_context_instance(CONTEXT_COURSE, $course->id);
	}
			
	$PAGE->set_url('/blocks/helpdesk/sisisi.php', array('courseid'=>$course->id));
	$PAGE->set_context($context);
	$PAGE->set_pagelayout('incourse');	
	
	$title = "Ingrese su consulta";
	$PAGE->set_title($title);
	$PAGE->set_heading( $title);
	$PAGE->set_cacheable( true);
	echo $OUTPUT->header();
			

	
	echo $OUTPUT->heading('<form><input type="textarea" name="mensaje" /><input type="submit" value="enviar" /></form>');
	
				
    echo $OUTPUT->footer();

?>
