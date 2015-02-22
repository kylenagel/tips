<?php
	
	include 'session.php';
	
	require 'includes/credentials.php';
	
	$connection = mysqli_connect($creds['host'], $creds['username'], $creds['password'], $creds['dbname']);
	
	// Check connection
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
	if ($_POST) {
	
		$tip_id = $_POST['carryover_id'];
		$newstatus = $_POST['status_update'];
		$newassigned = $_POST['assigned_to_update'];
		$newcomment = $_POST['tip_comment'];
		$newcommentescaped = addslashes($newcomment);

		mysqli_query($connection, "UPDATE tips SET assigned_to=".$newassigned.", status=".$newstatus." WHERE id=".$tip_id."");
		
		if ($newcommentescaped != '') {
			mysqli_query($connection, 
				"
					INSERT INTO notes (tip_id, timestamp, written_by, note)
					VALUES ('$tip_id', now(), '$session_id', '$newcommentescaped')
				"
			);
		}
		
		header("Location: viewtip.php?id=".$tip_id."");
	
	}

?>