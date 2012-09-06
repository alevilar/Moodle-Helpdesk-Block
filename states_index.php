<?php

    require_once(dirname(__FILE__).'/config.php');

    require_login();


    $context = get_context_instance(CONTEXT_SYSTEM);
    $PAGE->set_context($context);


    // verify if user is MANAGER
    $es_admin = false;
    if (has_capability('block/helpdesk:admin', $context)) {
            $es_admin =  true;
    }


    $PAGE->set_url('/blocks/helpdesk/states_add.php');
    $PAGE->set_heading($SITE->fullname);
    $PAGE->set_pagelayout('frontpage');
    $PAGE->set_title(get_string('helpdesk', 'block_helpdesk'));
    $PAGE->navbar->add(get_string('helpdesk', 'block_helpdesk'));


    if (!empty($notificationerror)) {
        echo $OUTPUT->header();
        echo $OUTPUT->heading(get_string('helpdesk', 'block_helpdesk'), 3, 'main');
        echo $OUTPUT->notification($notificationerror);
        echo $OUTPUT->footer();
        die();
    }


    echo $OUTPUT->header();
    echo $OUTPUT->heading(get_string('helpdesk', 'block_helpdesk'), 3, 'main');
    

    // SQL for sprintf the order of each %s is: fields, joins, where conditions, order by's, limit
    $sql_base = "SELECT %s FROM {block_helpdesk_states} s";
        
    $states = $DB->get_records('block_helpdesk_states');
    
    ?>
<h2><?php echo get_string('stateslist', 'block_helpdesk'); ?></h2>
<a href="states_add.php"><?php echo get_string('add').' '.get_string('state', 'block_helpdesk')?></a>
<ul>
    <?php foreach ($states as $s) { ?>
    <li><div class="state state-<?php echo $s->id?>"><?php echo $s->name ?></div> <a href="states_edit?state_id=<?php echo $s->id?>"><?php echo get_string('edit')?></a></li>
    <?php } ?>
</ul>
<?php
    
echo $OUTPUT->footer();
