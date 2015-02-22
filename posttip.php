<?php
	
	include 'header.php';
	
	require 'includes/credentials.php';
	
	$connection = mysqli_connect($creds['host'], $creds['username'], $creds['password'], $creds['dbname']);
	
	// Check connection
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
	if ($_POST) {
	
		$date = $_POST['tip_date'];
		$date = date('Y-m-d', strtotime($date));
		$name = $_POST['tipper_name'];
		$status = $_POST['status'];
		$assigned_to = $_POST['assigned_to'];
		$location = $_POST['tipper_location'];
		$phone = $_POST['tipper_phone'];
		$email = $_POST['tipper_email'];
		$details = $_POST['tip_details'];
		$detailsescaped = addslashes($details);
		$titlearray = explode(" ", $detailsescaped, 5);
		$title = '';
		for ($i=0; $i<4; $i++) {
			if ($i == 3) {
				$title .= $titlearray[$i].' ...';
			} else {
				$title .= $titlearray[$i].' ';
			}
		}
		
		mysqli_query($connection, "INSERT INTO tips (taken_by, status, assigned_to, tipper_name, tipper_phone, tipper_email, title, details, timestamp, tipper_location, tipper_report_date) VALUES ('$session_id', '$status', '$assigned_to', '$name', '$phone', '$email', '$title', '$detailsescaped', now(), '$location', '$date')");
		
		$html = '<h3>Your entry has been submitted.</h3><a href="addtip.php">Click here to add another tip</a>.';
		
		echo $html;
		
	}
	
?>

<?php
	include 'footer.php';
?>