<?php
	
	error_reporting(E_ALL);

	include 'header.php';
	
	require 'includes/credentials.php';
	
	$connection = mysqli_connect($creds['host'], $creds['username'], $creds['password'], $creds['dbname']);
	
	// Check connection
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

	if ($_POST) {
		// echo "<pre>";print_r($_POST);echo "</pre>";

		$takenby = $_POST['tips_search_taken_by'];
		$assignedto = $_POST['tips_search_assigned_to'];
		$status = $_POST['tips_search_status'];
		$startdate = $_POST['tips_search_start_date'];
		$startdate = date('Y-m-d', strtotime($startdate));
		$enddate = $_POST['tips_search_end_date'];
		$enddate = date('Y-m-d', strtotime($enddate));
		$keyword = $_POST['tips_search_description'];
		$keyword = strtolower($keyword);

		$alltips = mysqli_query
			(
				$connection, 
				"
					SELECT
						tips.id,
						taken_by.name AS taken_by_name,
						assigned_to.name AS assigned_to_name,
						status.status,
						REPLACE(REPLACE(tips.tipper_name,CHAR(133),'...'),CHAR(146),'\'') AS tipper_name,
						REPLACE(REPLACE(tips.title,CHAR(133),'...'),CHAR(146),'\'') AS title,
						REPLACE(REPLACE(tips.details,CHAR(133),'...'),CHAR(146),'\'') AS details,
						tips.tipper_report_date,
						GROUP_CONCAT(CONCAT(notes.note) SEPARATOR ';') AS tips_notes
					FROM tips
					INNER JOIN users taken_by ON taken_by.id = tips.taken_by
					INNER JOIN users assigned_to ON assigned_to.id = tips.assigned_to
					INNER JOIN status ON status.id = tips.status
					LEFT JOIN notes ON notes.tip_id = tips.id
					GROUP BY tips.id
					ORDER BY tipper_report_date DESC
				"
			);
			
		$tipsarray = Array();
		
		while($row = mysqli_fetch_array($alltips)) {
			$tipsarray[] = $row;
		}
		
		$result = '<table id="search_results_table"><thead><tr><th>Title</th><th>Date</th><th>Taken by</th><th>Assigned to</th><th>Status</th><th>Tipper name</th><th>Details</th></tr></thead><tbody>';

		for ($i=0; $i<count($tipsarray); $i++) {
		
			$descriptionlower = strtolower($tipsarray[$i]['details']);
			$noteslower = strtolower($tipsarray[$i]['tips_notes']);
			$inputdate = $tipsarray[$i]['tipper_report_date'];
			$tipperlower = strtolower($tipsarray[$i]['tipper_name']);
			if ($inputdate == '1969-12-31') {
				$tipperdatedisplay = 'N/A';
			} else {
				$tipperdatedisplay = date('m/d/Y', strtotime($tipsarray[$i]['tipper_report_date']));
			}

			if
			(
				$takenby == 'All' && $assignedto == 'All' && $status == 'All' && $startdate == '1969-12-31' && $enddate == '1969-12-31' && $keyword == ''
				|| $takenby == 'All' && $assignedto == 'All' && $status == 'All' && $startdate == '1969-12-31' && $enddate == '1969-12-31' && strpos($descriptionlower, $keyword) !== false
				|| $takenby == 'All' && $assignedto == 'All' && $status == 'All' && $startdate == '1969-12-31' && $enddate == '1969-12-31' && strpos($noteslower, $keyword) !== false
				|| $takenby == 'All' && $assignedto == 'All' && $status == 'All' && $startdate == '1969-12-31' && $enddate == '1969-12-31' && strpos($tipperlower, $keyword) !== false
				|| $takenby == 'All' && $assignedto == 'All' && $status == 'All' && $startdate <= $inputdate && $enddate >= $inputdate && $keyword == ''
				|| $takenby == 'All' && $assignedto == 'All' && $status == 'All' && $startdate <= $inputdate && $enddate >= $inputdate && strpos($descriptionlower, $keyword) !== false
				|| $takenby == 'All' && $assignedto == 'All' && $status == 'All' && $startdate <= $inputdate && $enddate >= $inputdate && strpos($noteslower, $keyword) !== false
				|| $takenby == 'All' && $assignedto == 'All' && $status == 'All' && $startdate <= $inputdate && $enddate >= $inputdate && strpos($tipperlower, $keyword) !== false
				|| $takenby == 'All' && $assignedto == 'All' && $status == $tipsarray[$i]['status'] && $startdate == '1969-12-31' && $enddate == '1969-12-31' && $keyword == ''
				|| $takenby == 'All' && $assignedto == 'All' && $status == $tipsarray[$i]['status'] && $startdate == '1969-12-31' && $enddate == '1969-12-31' && strpos($descriptionlower, $keyword) !== false
				|| $takenby == 'All' && $assignedto == 'All' && $status == $tipsarray[$i]['status'] && $startdate == '1969-12-31' && $enddate == '1969-12-31' && strpos($noteslower, $keyword) !== false
				|| $takenby == 'All' && $assignedto == 'All' && $status == $tipsarray[$i]['status'] && $startdate == '1969-12-31' && $enddate == '1969-12-31' && strpos($tipperlower, $keyword) !== false
				|| $takenby == 'All' && $assignedto == 'All' && $status == $tipsarray[$i]['status'] && $startdate <= $inputdate && $enddate >= $inputdate && $keyword == ''
				|| $takenby == 'All' && $assignedto == 'All' && $status == $tipsarray[$i]['status'] && $startdate <= $inputdate && $enddate >= $inputdate && strpos($descriptionlower, $keyword) !== false
				|| $takenby == 'All' && $assignedto == 'All' && $status == $tipsarray[$i]['status'] && $startdate <= $inputdate && $enddate >= $inputdate && strpos($noteslower, $keyword) !== false
				|| $takenby == 'All' && $assignedto == 'All' && $status == $tipsarray[$i]['status'] && $startdate <= $inputdate && $enddate >= $inputdate && strpos($tipperlower, $keyword) !== false
				|| $takenby == 'All' && $assignedto == $tipsarray[$i]['assigned_to_name'] && $status == 'All' && $startdate == '1969-12-31' && $enddate == '1969-12-31' && $keyword == ''
				|| $takenby == 'All' && $assignedto == $tipsarray[$i]['assigned_to_name'] && $status == 'All' && $startdate == '1969-12-31' && $enddate == '1969-12-31' && strpos($descriptionlower, $keyword) !== false
				|| $takenby == 'All' && $assignedto == $tipsarray[$i]['assigned_to_name'] && $status == 'All' && $startdate == '1969-12-31' && $enddate == '1969-12-31' && strpos($noteslower, $keyword) !== false
				|| $takenby == 'All' && $assignedto == $tipsarray[$i]['assigned_to_name'] && $status == 'All' && $startdate == '1969-12-31' && $enddate == '1969-12-31' && strpos($tipperlower, $keyword) !== false
				|| $takenby == 'All' && $assignedto == $tipsarray[$i]['assigned_to_name'] && $status == 'All' && $startdate <= $inputdate && $enddate >= $inputdate && $keyword == ''
				|| $takenby == 'All' && $assignedto == $tipsarray[$i]['assigned_to_name'] && $status == 'All' && $startdate <= $inputdate && $enddate >= $inputdate && strpos($descriptionlower, $keyword) !== false
				|| $takenby == 'All' && $assignedto == $tipsarray[$i]['assigned_to_name'] && $status == 'All' && $startdate <= $inputdate && $enddate >= $inputdate && strpos($noteslower, $keyword) !== false
				|| $takenby == 'All' && $assignedto == $tipsarray[$i]['assigned_to_name'] && $status == 'All' && $startdate <= $inputdate && $enddate >= $inputdate && strpos($tipperlower, $keyword) !== false
				|| $takenby == 'All' && $assignedto == $tipsarray[$i]['assigned_to_name'] && $status == $tipsarray[$i]['status'] && $startdate == '1969-12-31' && $enddate == '1969-12-31' && $keyword == ''
				|| $takenby == 'All' && $assignedto == $tipsarray[$i]['assigned_to_name'] && $status == $tipsarray[$i]['status'] && $startdate == '1969-12-31' && $enddate == '1969-12-31' && strpos($descriptionlower, $keyword) !== false
				|| $takenby == 'All' && $assignedto == $tipsarray[$i]['assigned_to_name'] && $status == $tipsarray[$i]['status'] && $startdate == '1969-12-31' && $enddate == '1969-12-31' && strpos($noteslower, $keyword) !== false
				|| $takenby == 'All' && $assignedto == $tipsarray[$i]['assigned_to_name'] && $status == $tipsarray[$i]['status'] && $startdate == '1969-12-31' && $enddate == '1969-12-31' && strpos($tipperlower, $keyword) !== false
				|| $takenby == 'All' && $assignedto == $tipsarray[$i]['assigned_to_name'] && $status == $tipsarray[$i]['status'] && $startdate <= $inputdate && $enddate >= $inputdate && $keyword == ''
				|| $takenby == 'All' && $assignedto == $tipsarray[$i]['assigned_to_name'] && $status == $tipsarray[$i]['status'] && $startdate <= $inputdate && $enddate >= $inputdate && strpos($descriptionlower, $keyword) !== false
				|| $takenby == 'All' && $assignedto == $tipsarray[$i]['assigned_to_name'] && $status == $tipsarray[$i]['status'] && $startdate <= $inputdate && $enddate >= $inputdate && strpos($noteslower, $keyword) !== false
				|| $takenby == 'All' && $assignedto == $tipsarray[$i]['assigned_to_name'] && $status == $tipsarray[$i]['status'] && $startdate <= $inputdate && $enddate >= $inputdate && strpos($tipperlower, $keyword) !== false
				|| $takenby == $tipsarray[$i]['taken_by_name'] && $assignedto == 'All' && $status == 'All' && $startdate == '1969-12-31' && $enddate == '1969-12-31' && $keyword == ''
				|| $takenby == $tipsarray[$i]['taken_by_name'] && $assignedto == 'All' && $status == 'All' && $startdate == '1969-12-31' && $enddate == '1969-12-31' && strpos($descriptionlower, $keyword) !== false
				|| $takenby == $tipsarray[$i]['taken_by_name'] && $assignedto == 'All' && $status == 'All' && $startdate == '1969-12-31' && $enddate == '1969-12-31' && strpos($noteslower, $keyword) !== false
				|| $takenby == $tipsarray[$i]['taken_by_name'] && $assignedto == 'All' && $status == 'All' && $startdate == '1969-12-31' && $enddate == '1969-12-31' && strpos($tipperlower, $keyword) !== false
				|| $takenby == $tipsarray[$i]['taken_by_name'] && $assignedto == 'All' && $status == 'All' && $startdate <= $inputdate && $enddate >= $inputdate && $keyword == ''
				|| $takenby == $tipsarray[$i]['taken_by_name'] && $assignedto == 'All' && $status == 'All' && $startdate <= $inputdate && $enddate >= $inputdate && strpos($descriptionlower, $keyword) !== false
				|| $takenby == $tipsarray[$i]['taken_by_name'] && $assignedto == 'All' && $status == 'All' && $startdate <= $inputdate && $enddate >= $inputdate && strpos($noteslower, $keyword) !== false
				|| $takenby == $tipsarray[$i]['taken_by_name'] && $assignedto == 'All' && $status == 'All' && $startdate <= $inputdate && $enddate >= $inputdate && strpos($tipperlower, $keyword) !== false
				|| $takenby == $tipsarray[$i]['taken_by_name'] && $assignedto == 'All' && $status == $tipsarray[$i]['status'] && $startdate == '1969-12-31' && $enddate == '1969-12-31' && $keyword == ''
				|| $takenby == $tipsarray[$i]['taken_by_name'] && $assignedto == 'All' && $status == $tipsarray[$i]['status'] && $startdate == '1969-12-31' && $enddate == '1969-12-31' && strpos($descriptionlower, $keyword) !== false
				|| $takenby == $tipsarray[$i]['taken_by_name'] && $assignedto == 'All' && $status == $tipsarray[$i]['status'] && $startdate == '1969-12-31' && $enddate == '1969-12-31' && strpos($noteslower, $keyword) !== false
				|| $takenby == $tipsarray[$i]['taken_by_name'] && $assignedto == 'All' && $status == $tipsarray[$i]['status'] && $startdate == '1969-12-31' && $enddate == '1969-12-31' && strpos($tipperlower, $keyword) !== false
				|| $takenby == $tipsarray[$i]['taken_by_name'] && $assignedto == 'All' && $status == $tipsarray[$i]['status'] && $startdate <= $inputdate && $enddate >= $inputdate && $keyword == ''
				|| $takenby == $tipsarray[$i]['taken_by_name'] && $assignedto == 'All' && $status == $tipsarray[$i]['status'] && $startdate <= $inputdate && $enddate >= $inputdate && strpos($descriptionlower, $keyword) !== false
				|| $takenby == $tipsarray[$i]['taken_by_name'] && $assignedto == 'All' && $status == $tipsarray[$i]['status'] && $startdate <= $inputdate && $enddate >= $inputdate && strpos($noteslower, $keyword) !== false
				|| $takenby == $tipsarray[$i]['taken_by_name'] && $assignedto == 'All' && $status == $tipsarray[$i]['status'] && $startdate <= $inputdate && $enddate >= $inputdate && strpos($tipperlower, $keyword) !== false
				|| $takenby == $tipsarray[$i]['taken_by_name'] && $assignedto == $tipsarray[$i]['assigned_to_name'] && $status == 'All' && $startdate == '1969-12-31' && $enddate == '1969-12-31' && $keyword == ''
				|| $takenby == $tipsarray[$i]['taken_by_name'] && $assignedto == $tipsarray[$i]['assigned_to_name'] && $status == 'All' && $startdate == '1969-12-31' && $enddate == '1969-12-31' && strpos($descriptionlower, $keyword) !== false
				|| $takenby == $tipsarray[$i]['taken_by_name'] && $assignedto == $tipsarray[$i]['assigned_to_name'] && $status == 'All' && $startdate == '1969-12-31' && $enddate == '1969-12-31' && strpos($noteslower, $keyword) !== false
				|| $takenby == $tipsarray[$i]['taken_by_name'] && $assignedto == $tipsarray[$i]['assigned_to_name'] && $status == 'All' && $startdate == '1969-12-31' && $enddate == '1969-12-31' && strpos($tipperlower, $keyword) !== false
				|| $takenby == $tipsarray[$i]['taken_by_name'] && $assignedto == $tipsarray[$i]['assigned_to_name'] && $status == 'All' && $startdate <= $inputdate && $enddate >= $inputdate && $keyword == ''
				|| $takenby == $tipsarray[$i]['taken_by_name'] && $assignedto == $tipsarray[$i]['assigned_to_name'] && $status == 'All' && $startdate <= $inputdate && $enddate >= $inputdate && strpos($descriptionlower, $keyword) !== false
				|| $takenby == $tipsarray[$i]['taken_by_name'] && $assignedto == $tipsarray[$i]['assigned_to_name'] && $status == 'All' && $startdate <= $inputdate && $enddate >= $inputdate && strpos($noteslower, $keyword) !== false
				|| $takenby == $tipsarray[$i]['taken_by_name'] && $assignedto == $tipsarray[$i]['assigned_to_name'] && $status == 'All' && $startdate <= $inputdate && $enddate >= $inputdate && strpos($tipperlower, $keyword) !== false
				|| $takenby == $tipsarray[$i]['taken_by_name'] && $assignedto == $tipsarray[$i]['assigned_to_name'] && $status == $tipsarray[$i]['status'] && $startdate == '1969-12-31' && $enddate == '1969-12-31' && $keyword == ''
				|| $takenby == $tipsarray[$i]['taken_by_name'] && $assignedto == $tipsarray[$i]['assigned_to_name'] && $status == $tipsarray[$i]['status'] && $startdate == '1969-12-31' && $enddate == '1969-12-31' && strpos($descriptionlower, $keyword) !== false
				|| $takenby == $tipsarray[$i]['taken_by_name'] && $assignedto == $tipsarray[$i]['assigned_to_name'] && $status == $tipsarray[$i]['status'] && $startdate == '1969-12-31' && $enddate == '1969-12-31' && strpos($noteslower, $keyword) !== false
				|| $takenby == $tipsarray[$i]['taken_by_name'] && $assignedto == $tipsarray[$i]['assigned_to_name'] && $status == $tipsarray[$i]['status'] && $startdate == '1969-12-31' && $enddate == '1969-12-31' && strpos($tipperlower, $keyword) !== false
				|| $takenby == $tipsarray[$i]['taken_by_name'] && $assignedto == $tipsarray[$i]['assigned_to_name'] && $status == $tipsarray[$i]['status'] && $startdate <= $inputdate && $enddate >= $inputdate && $keyword == ''
				|| $takenby == $tipsarray[$i]['taken_by_name'] && $assignedto == $tipsarray[$i]['assigned_to_name'] && $status == $tipsarray[$i]['status'] && $startdate <= $inputdate && $enddate >= $inputdate && strpos($descriptionlower, $keyword) !== false
				|| $takenby == $tipsarray[$i]['taken_by_name'] && $assignedto == $tipsarray[$i]['assigned_to_name'] && $status == $tipsarray[$i]['status'] && $startdate <= $inputdate && $enddate >= $inputdate && strpos($noteslower, $keyword) !== false
				|| $takenby == $tipsarray[$i]['taken_by_name'] && $assignedto == $tipsarray[$i]['assigned_to_name'] && $status == $tipsarray[$i]['status'] && $startdate <= $inputdate && $enddate >= $inputdate && strpos($tipperlower, $keyword) !== false
			)
			{
				$result .= '<tr><td class="search_results_short_cell"><a href="viewtip.php?id='.$tipsarray[$i]['id'].'">'.$tipsarray[$i]['title'].'</a></td><td class="search_results_short_cell">'.$tipperdatedisplay.'</td><td class="search_results_short_cell">'.$tipsarray[$i]['taken_by_name'].'</td><td class="search_results_short_cell">'.$tipsarray[$i]['assigned_to_name'].'</td><td class="search_results_short_cell">'.$tipsarray[$i]['status'].'</td><td class="search_results_short_cell">'.$tipsarray[$i]['tipper_name'].'</td><td class="search_results_long_cell">'.$tipsarray[$i]['details'].'</td></tr>';
			}
		
		}
		
		$result .= '</tbody></table>';
		
		if (strpos($result, 'td') === false) {
			echo '<div class="searchresults_noresultt">Your search produced no results</div><div class="searchresult_searchagain"><a href="searchtips.php">Click here to search again</a>.</div>';
		} else {
			echo $result;
		}
		
	}
?>

<?php
	include 'footer.php';
?>