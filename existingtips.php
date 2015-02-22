<?php

	$connection = mysqli_connect('localhost', 'root', '', 'kylernag_iteamtips');
	
	// Check connection
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

	$existingtips = file_get_contents('existingtips.csv');
	
	$existingtipsarray = explode("\n", $existingtips);
	
	$tipsdetails = array();
	
	for ($i=0; $i<count($existingtipsarray); $i++) {
		$tipsdetails[] = str_getcsv($existingtipsarray[$i], ',', '"');
	}
	
	for ($i=0; $i<count($tipsdetails); $i++) {
		
		$date = $tipsdetails[$i][0];
		$date = date('Y-m-d', strtotime($date));
		$name = $tipsdetails[$i][1];
		$location = $tipsdetails[$i][2];
		$phone = $tipsdetails[$i][3];
		$details = $tipsdetails[$i][4];
		$details = addslashes($details);
		$titlearray = explode(" ", $details, 5);
		$title = '';
		if (count($titlearray) > 4) {
	
			for ($t=0; $t<4; $t++) {
				if ($t == 3) {
					$title .= $titlearray[$t].' ...';
				} else {
					$title .= $titlearray[$t].' ';
				}
			}
			
		} else {
		
			for ($t=0; $t<count($titlearray); $t++) {
				if ($t == count($titlearray)) {
					$title .= $titlearray[$t].' ...';
				} else {
					$title .= $titlearray[$t].' ';
				}
			}
			
		
		}
		
		echo 'Tip '.$i.':<br>';
		echo $date.'<br>';
		echo $name.'<br>';
		echo $location.'<br>';
		echo $phone.'<br>';
		echo $details.'<br>';
		echo $title.'<br>';
		echo '<br>';
		echo '<br>';
		
		/*
		mysqli_query($connection, "INSERT INTO tips (taken_by, assigned_to, timestamp, title, status, tipper_phone, details, tipper_location, tipper_report_date, tipper_name) VALUES ('7', '6', now(), '$title', '1', '$phone', '$details', '$location', '$date', '$name')");
		*/
		
	}
	
?>