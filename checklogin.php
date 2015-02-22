<?php 
	
	require 'includes/credentials.php';
	
	$connection = mysqli_connect($creds['host'], $creds['username'], $creds['password'], $creds['dbname']);
	
	// Check connection
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
	if ($_POST) {
		
		$username = $_POST['username'];
		$password = $_POST['password'];
		
		$logincheck = mysqli_query($connection, "SELECT * FROM users WHERE username='".$username."' AND password='".$password."'");
		
		$count = mysqli_num_rows($logincheck);

		$result = mysqli_fetch_assoc($logincheck);
		
		if ($count == 1) {
			session_start();
			$_SESSION['username'] = $username;
			$_SESSION['password'] = $password;
			$_SESSION['name'] = $result['name'];
			$_SESSION['session_id'] = $result['id'];
			header("Location: home.php");
		} else {
			header("Location: index.php");
		}
		
	}
	
?>