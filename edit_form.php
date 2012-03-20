<?php
 
class block_helpdesk_edit_form extends block_edit_form {
 
    protected function specific_definition($mform) {
 
        // Section header title according to language file.
        $mform->addElement('email', 'config_mail', get_string('helpdesk_mail', 'block'));        
 
    }
}
