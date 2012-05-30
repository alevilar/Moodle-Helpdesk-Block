<?php

require_once(dirname(__FILE__).'/config.php');

require_login();


$context = get_context_instance(CONTEXT_SYSTEM);
$PAGE->set_context($context);


$es_admin = false;
if (has_capability('block/helpdesk:admin', $context)) {
	$es_admin =  true;
}

 
$PAGE->set_url('/blocks/helpdesk/ticket_add.php');
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
    
    $tickets = array();
    $state_init = STATE_OPEN;

    // SQL for sprintf the order of each %s is: fields, joins, where conditions, order by's, limit
    $sql_base = "SELECT %s FROM {block_helpdesk_tickets} t 
			LEFT JOIN {block_helpdesk_states} s ON (s.id = t.stateid) %s
			WHERE 1=1 %s %s %s";
    // this vars populates del sql search conditions, fiels an joins needed
    $fields = array();
    $join = array();
    $where = array();
    $limit = 30; //default limit

    $sql = '';
    if ( !$es_admin && !empty($USER->id) ) {	   
	  $where[] = "t.authorid = $USER->id";	  
    }

    $stateidSelected = 0;
    if ( !empty($_GET) && !empty($_GET['stateid']) ) {
	 $stateidSelected = $_GET['stateid'];
	 $where[] = "t.stateid =  $stateidSelected";	
    }


    $ownerSelected = '';
    if ( !empty($_GET) && !empty($_GET['ownername']) ) {
	 $fields[] = "o.username";
	 $join[] = "{user} o ON (o.id = t.ownerid)"; 
	 $ownerSelected = $_GET['ownername'];
	 $where[] = "o.username LIKE  '?$ownerSelected?'";
    }


    $authorSelected = '';
    if ( !empty($_GET) && !empty($_GET['authorname']) ) {
	 $fields[] = "a.username";
	 $join[] = "{user} a ON (a.id = t.authorid)"; 
	 $authorSelected = $_GET['authorname'];
	 $where[] = "a.username LIKE  '?$authorSelected?'";
    }
    
    $unassignedSelected = 0;
    if ( !empty($_GET) && !empty($_GET['unassigned']) ) {	 
	 $unassignedSelected = 1;
	 $where[] = "t.ownerid IS NULL";
    }


    $prioritySelected = '';
    if ( !empty($_GET) && !empty($_GET['priority']) ) {
	 $prioritySelected = $_GET['priority'];
	 $where[] = "t.priority = $prioritySelected";
    }


    $field_text = 't.*, s.name as status ';
    foreach ($fields as $f){
	$field_text .= ", $f";
    }     

    $join_text = '';
    foreach ($join as $j){
	$join_text .= " LEFT JOIN $j ";
    }

    $where_text = '';
    foreach ($where as $w) {
	$where_text .= " AND $w";
    }

    $order_by = "ORDER by t.created ASC";

    $limit_text = " LIMIT $limit";
        

    // count SQL whitouth no fields, nor order nor limit    
    $sql_count = sprintf($sql_base, 'count(*)', $join_text, $where_text, "", "") ;
    $sql_count = sprintf($sql_count, "", "", "");   
    $sql_count = strtr( $sql_count, '?', '%'); // converts ? to % because you can't use the % befeore the sprintf
    $tickets_count = $DB->get_record_sql($sql_count);



    // SQL query 
    $sql_base = sprintf($sql_base, $field_text, $join_text, $where_text, $order_by, $limit_text) ;
    $sql = sprintf($sql_base, "", "", "");   
    $sql = strtr( $sql, '?', '%');// converts ? to % because you can't use the % befeore the sprintf

    $offset = "0";
    if ( !empty($_GET) && !empty($_GET['offset']) ) {
	$offset = $_GET['offset'];
    } 

    $sql .= " OFFSET $offset";

    $tickets = $DB->get_records_sql($sql);

    if (has_capability('block/helpdesk:admin', $context)) {
	?>	
		<p>
		<style>

			.formu_helpdesk{
				background: #ddb;
				padding: 20px;
			}
			.formu_helpdesk h4{
				border-bottom: 1px solid silver;
			}
			.formu_helpdesk input[type=submit]:hover{
				background: #00446B;
				border-top: 1px solid #002438;
				border-left: 1px solid #002438;
				text-shadow: 1px 1px black;
			}
			.formu_helpdesk label{
				margin: 10px;
			}
		</style>
		
		<form action="ticket_index.php" method='get' class="formu_helpdesk">
			<h4><?php echo get_string('advanced_filters', 'block_helpdesk')?></h4>
			<label style="padding-left: 34px;"><?php echo get_string('Author','block_helpdesk')?></label><input type='text' name='authorname' value='<?php echo $authorSelected?>'/>
			
			<label><?php echo get_string('State','block_helpdesk')?></label>
				<select type='text' name='stateid'/ style="width: 200px;">
					<option value='0'>Todos</option>
					<?php
						$states = $DB->get_records('block_helpdesk_states');

						foreach ($states as $s) {
							$checkeds = '';
							if ($stateidSelected == $s->id) {
								$checkeds = 'selected="selected"';
							}
							echo "    <option value='$s->id' $checkeds>$s->name</option>";
						}
					?>
				</select>
			

			<br />

			<label><?php echo get_string('Owner','block_helpdesk')?></label><input type='text' name='ownername' value='<?php echo $ownerSelected?>' />

			<label><?php echo get_string('Unassigned','block_helpdesk')?></label><input type='checkbox' name='unassigned'  <?php echo $unassignedSelected?'checked':''; ?>/>


			<label><?php echo get_string('priority','block_helpdesk')?></label>
			<select name="priority">
				echo "<option value=''>Todos</option>";
				<?php 				
					foreach ( $priorities as $k=>$p ) {
						$markSelected = '';
						if ( $prioritySelected == $k ) {
							$markSelected =  'selected="selected"';
						}
						echo "<option value='$k' $markSelected>$p</option>";
					}
				?>
			</select>

			

			<br />
			<p style="text-align: center;">
			<input type="submit" style="margin: 20px 0px 0px 0px; padding: 10px;" value="<?php echo get_string('apply_filters', 'block_helpdesk'); ?>"/>
			</p>

		</form>
		</p>
	<?php
    } // EOF capability if can admin

    echo "<h3>Listado de Tickets</h3>";
    
    
    if ( count($tickets) ) {
	echo "<table id='ticket_index'><tr><th>&nbsp;</th><th>".get_string('priority', 'block_helpdesk')."</th><th>".get_string('date')."</th><th>".get_string('Author', 'block_helpdesk')."</th><th>".get_string('Owner', 'block_helpdesk')."</th><th>".get_string('description')."</th><th >&nbsp;</th></tr>";	

        echo "<tr class='tickets-list'>";
        foreach ($tickets as $t) {      
	  echo "<tr>";    
    	    echo "<td><div class='state-$t->stateid'>$t->status</div></td>";  
	    echo "<td>".$priorities[$t->priority]."</td>";
            echo "<td>".date('Y-m-d H:i', $t->created)."</td>";

	    $userObj = $DB->get_record('user', array('id'=>$t->authorid));
      	    $url_profile = new moodle_url("/user/profile.php?id=".$t->authorid);
	    $url_profile = html_writer::tag('a',  $userObj->username, array('href' => $url_profile, 'class'=>'username' ));  
	    echo "<td>$url_profile</td>";
	
	    if ( $t->ownerid ) {
	        $userObj = $DB->get_record('user', array('id'=>$t->ownerid));
      	        $url_profile = new moodle_url("/user/profile.php?id=".$t->authorid);
	        $url_profile = html_writer::tag('a',  $userObj->username, array('href' => $url_profile, 'class'=>'username' )); 
	    } else {
		$url_profile = 'sin asignar';
	    }
	    echo "<td>$url_profile</td>";

	    echo "<td>".substr( $t->question, 0, 30 )."...</td>";

	    echo "<td><a class='responder' href='ticket_answer?ticketid=$t->id'>".get_string('go')."</a></td>";
        }
        echo "</tr>";
    } else {
        echo "<div class='notice'>".get_string('nothing_to_reply', 'block_helpdesk')."</div>";
    }        

    echo "</table>";

?>

<hr />
<p style="text-align: center">
	<?php 
	echo $tickets_count->count?$tickets_count->count." ".get_string('tickets_founded', 'block_helpdesk'):''; 
	?>
</p>

<style>
	a.current{
		font-weight: bolder;
		color: red;
	}
</style>
<p style="text-align: center">
<?php 	
	$url_path_all = 'ticket_index?p=1';
	$cant_pags = ceil($tickets_count->count / $limit);
	$pags = 1; // printed links counter
	$max_links_to_show = 8; //cant of pages links to be showed
	$pag_max = ($cant_pags>$max_links_to_show)?$max_links_to_show:$cant_pags; 


	foreach ($_GET as $k=>$g) {
		if ( !empty($g) && $k != 'p' && $k != 'offset' ) {
			$url_path_all .= "&$k=$g";
		}
	}

	$current_i = $offset / $limit;

	// making this, previus link will be printed
	if ($current_i > 0 ) {
		$current_i--;
	}

	for ($i = $current_i; $i < $cant_pags && $cant_pags > 1; $i++) {

		$offset_pag = ($i * $limit);		
				
		if ($pags-1) {
			echo " - ";
		}
			
		
		$class_put = '';
		if ($offset_pag == $offset) {
			$class_put = 'class="current"';
		}
		$pagName = $i+1;
		echo "<a href='$url_path_all&offset=$offset_pag' $class_put>$pagName</a>";

		if ($pags == $pag_max) {
			break;
		}

		$pags++;

				
	}
	

?>
</p>
<hr />

<?php
echo $OUTPUT->footer();
