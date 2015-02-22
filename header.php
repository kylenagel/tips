<?php

	include 'session.php';
	
	if (!$_SESSION) {
		header("Location: index.php");
	}

?>

<html>

<head>

<title>I-Team Tips</title>

<?php echo '<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>'; ?>
<?php echo '<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>'; ?>
<?php echo '<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />'; ?>
<?php echo '<link rel="stylesheet" stype="text/css" href="css/tipsmaster.css">'; ?>
<?php echo '<link rel="stylesheet" stype="text/css" href="css/tipsfonts.css">'; ?>

<script type="text/javascript">

// function to check for both title and details on "Add Tip" form
function checkTipFields() {
	var title = $("#title").val();
	var details = $("#tip_details").val();
	var date = $("#tip_date").val();
	if (title == '' || details == '' | date == '') {
		$("#title, #tip_details, #tip_date").css("border-color", "red");
		$("#tips_addtip_required_text").show();
		return false;
	} else {
		return true;
	}
}
	
</script>

</head>

<body>

	<div id="tips_container">
	
		<div id="tips_content_container">
		
			<div id="tips_header">
			
				<h1>I-Team Tips</h1>
				
				<div id="tips_signedin_id">Signed in as: <span><?php echo $session_name ?></span></div>
				
				<div id="tips_header_banner">
					<ul>
						<li><a href="home.php">Home</a></li>
						<li>|</li>
						<li><a href="addtip.php">Add tip</a></li>
						<li>|</li>
						<li><a href="searchtips.php">Search</a></li>
						<li>|</li>
						<li><a href="signout.php">Sign out</a></li>
					</ul>
				</div>
			
			</div>