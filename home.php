<?php
	
	include 'header.php';
	
	require 'includes/credentials.php';
	
	$connection = mysqli_connect($creds['host'], $creds['username'], $creds['password'], $creds['dbname']);
	
	// Check connection
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
	$tipsquery = mysqli_query($connection,
		"SELECT tips.id,
			tips.tipper_report_date,
			tips.title,
			taken_by.name AS taken_by_name,
			assigned_to.name AS assigned_to_name,
			status.status
		FROM tips
		INNER JOIN users taken_by ON taken_by.id = tips.taken_by
		INNER JOIN users assigned_to ON assigned_to.id = tips.assigned_to
		INNER JOIN status ON status.id = tips.status
		ORDER BY tipper_report_date DESC"
	);
	
	$tips = Array();
	
	while($row = mysqli_fetch_array($tipsquery)) {
		$tips[] = $row;
	}
	
	// function to draw a tips table
	function drawTipsTable($category, $looplength, $status, $user) {
	
		global $tips;
		
		$table = '<div class="tips_dashboard_table">';
		
			$table .= '<h2>'.$category.'</h2>';
			
			$table .= '<table><thead><tr><th>Tip date</th><th>Title</th><th>Taken by</th><th>Status</th><th>Assigned to</th></tr></thead><tbody>';
			
			for ($i=0; $i<$looplength; $i++) {
				if ($tips[$i]['tipper_report_date'] == '1970-01-01') {
					$tipperdatedisplay = 'N/A';
				} else {
					$tipperdatedisplay = date('m/d/Y', strtotime($tips[$i]['tipper_report_date']));
				}
				if (
					$status == 'All' && $user == 'All' && $tips[$i]['title'] != ''
					|| $tips[$i]['status'] == $status  && $user == 'All' && $tips[$i]['title'] != ''
					|| $status == 'All' && $user == $tips[$i]['assigned_to_name'] && $tips[$i]['title'] != ''
					|| $tips[$i]['status'] == $status && $user == $tips[$i]['assigned_to_name'] && $tips[$i]['title'] != ''
				) {
					$table .= '<tr><td>'.$tipperdatedisplay.'</td><td><a href="viewtip.php?id='.$tips[$i]['id'].'">'.$tips[$i]['title'].'</a></td><td>'.$tips[$i]['taken_by_name'].'</td><td>'.$tips[$i]['status'].'</td><td>'.$tips[$i]['assigned_to_name'].'</td></tr>';
				}
			}

			$table .= '</tbody></table>';
		
		$table .= '</div>';
			
		if (strpos($table, '<td>') === false) {
			$tablealternate = '<div class="tips_dashboard_table">';
				$tablealternate .= '<h2>'.$category.'</h2>';
				$tablealternate .= '<p>There are no tips to display.</p>';
			$tablealternate .= '</div>';
			echo $tablealternate;
		} else {
			echo $table;
		}
		
	}
	
	drawTipsTable('My tips', count($tips), 'All', $session_name);
	drawTipsTable('Recent', 10, 'All', 'All');
	drawTipsTable('Assigned', count($tips), 'Assigned', 'All');

?>


<?php
	include 'footer.php';
?>