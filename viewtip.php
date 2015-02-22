<?php
	
	include 'header.php';
	
	require 'includes/credentials.php';
	
	$connection = mysqli_connect($creds['host'], $creds['username'], $creds['password'], $creds['dbname']);
	
	// Check connection
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

	if ($_GET) {
	
		$tip = $_GET['id'];
		
		$tipsquery = mysqli_query (
			$connection,
			"
				SELECT
					status.id AS status_id,
					status.status,
					taken_by.name AS taken_by_name,
					assigned_to.id AS assigned_to_id,
					assigned_to.name AS assigned_to_name,
					tips.id AS tip_id,
					tips.tipper_name,
					tips.tipper_phone,
					tips.tipper_email,
					tips.title,
					tips.details,
					tips.timestamp AS tip_timestamp,
					tips.tipper_location,
					tips.tipper_report_date,
					GROUP_CONCAT(CONCAT(notes.timestamp,'~',note_written_by.name,'~',notes.note) SEPARATOR ';') AS tips_notes
				FROM tips
				INNER JOIN users taken_by ON tips.taken_by = taken_by.id
				INNER JOIN users assigned_to ON tips.assigned_to = assigned_to.id
				INNER JOIN status ON tips.status = status.id
				LEFT JOIN notes ON notes.tip_id = tips.id
				LEFT JOIN users note_written_by ON notes.written_by = note_written_by.id
				WHERE tips.id = '".$tip."'
				GROUP BY tips.id
			"
		);
		
		$result = mysqli_fetch_assoc($tipsquery);
		
		// build list of users for dropdown menu to change "assigned to"
		$assignedtolist = '<option value="'.$result['assigned_to_id'].'"></option>';
		$assignedtoarray = Array();
		$assignedtoquery = mysqli_query($connection, "SELECT * FROM users ORDER BY name");
		while($row = mysqli_fetch_array($assignedtoquery)) {
			$assignedtoarray[] = $row;
		}
		for ($i=0; $i<count($assignedtoarray); $i++) {
			$assignedtolist .= '<option value="'.$assignedtoarray[$i]['id'].'">'.$assignedtoarray[$i]['name'].'</option>';
		}
		
		// build list of statuses for dropdown menu to change status
		$statuslist = '<option value="'.$result['status_id'].'"></option>';
		$statuslistarray = Array();
		$statusquery = mysqli_query($connection, "SELECT * FROM status");
		while($row = mysqli_fetch_array($statusquery)) {
			$statuslistarray[] = $row;
		}
		for ($i=0; $i<count($statuslistarray); $i++) {
			$statuslist .= '<option value="'.$statuslistarray[$i]['id'].'">'.$statuslistarray[$i]['status'].'</option>';
		}
		
		// create notes list from tips_notes query result string
		$notesarray = explode(";", $result['tips_notes']);
		$notesarray = array_reverse($notesarray);
		if ($notesarray[0] == '') {
			$notes .= 'There are no notes for this tip.';
		} else {
			$notes = '<ul>';
			for ($i=0; $i<count($notesarray); $i++) {
				$notesarrays[$i] = explode("~", $notesarray[$i]);
			}
			for ($i=0; $i<count($notesarray); $i++) {
				
				$posttime = date('m/d/Y, g:i a', strtotime($notesarrays[$i][0]));
			
				$notes .= '<li><div class="notes_details">Posted by '.$notesarrays[$i][1].' on '.$posttime.'</div><div class="notes_note">'.$notesarrays[$i][2].'</div></li>';
			}
		}
		$notes .= '</ul>';
		
		// format timestamp for display
		$timestamp = date('m/d/Y', strtotime($result['tip_timestamp']));
		// format tipper date for display
		if ($result['tipper_report_date'] == '1970-01-01') {
			$tipperdatedisplay = 'N/A';
		} else {
			$tipperdatedisplay = date('m/d/Y', strtotime($result['tipper_report_date']));
		}
		
		// build markup for the page
		$html = '<div id="viewtips_container">';
			$html .= '<form method="post" action="updatetip.php">';
			$html .= '<h1>'.$result['title'].'</h1>';
			$html .= '<p>'.$result['details'].'</p>';
			$html .= '<table><tbody>';
				$html .= '<tr class="odd_row"><td class="viewtips_cell_title">Taken by:</td><td>'.$result['taken_by_name'].'</td></tr>';
				$html .= '<tr><td class="viewtips_cell_title">Status:</td><td>'.$result['status'].' <span>Change to: <select name="status_update">'.$statuslist.'</select></span></td></tr>';
				$html .= '<tr class="odd_row"><td class="viewtips_cell_title">Assigned to:</td><td>'.$result['assigned_to_name'].' <span>Change to: <select name="assigned_to_update">'.$assignedtolist.'</select></span></td></tr>';
				$html .= '<tr><td class="viewtips_cell_title">Tipper:</td><td>'.$result['tipper_name'].'</td></tr>';
				$html .= '<tr class="odd_row"><td class="viewtips_cell_title">Tipper contact:</td><td>'.$result['tipper_phone'].' '.$result['tipper_email'].'</td></tr>';
				$html .= '<tr><td class="viewtips_cell_title">Called in:</td><td>'.$tipperdatedisplay.'</td></tr>';
				$html .= '<tr class="odd_row"><td class="viewtips_cell_title">Inputted:</td><td>'.$timestamp.'</td></tr>';
			$html .= '</tbody></table>';
			$html .= '<h3>NOTES</h3>';
			$html .= $notes;
			$html .= '<h4>Add a note</h4>';
			$html .= '<textarea name="tip_comment" rows="10" cols="50"></textarea>';
			$html .= '<input id="viewtip_carryover_input" name="carryover_id" value="'.$tip.'">';
			$html .= '<br><input type="submit">';
			$html .= '</form>';
		$html .= '</div>';
		
		echo $html;
		
	}

?>

<?php
	include 'footer.php';
?>