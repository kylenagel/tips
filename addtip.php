<?php
	
	include 'header.php';
	
	require 'includes/credentials.php';
	
	$connection = mysqli_connect($creds['host'], $creds['username'], $creds['password'], $creds['dbname']);
	
	// Check connection
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
	// build list of users for dropdown menu to change "assigned to"
	$assignedtolist = '';
	$assignedtoarray = Array();
	$assignedtoquery = mysqli_query($connection, "SELECT * FROM users ORDER BY name");
	while($row = mysqli_fetch_array($assignedtoquery)) {
		$assignedtoarray[] = $row;
	}
	for ($i=0; $i<count($assignedtoarray); $i++) {
		if ($assignedtoarray[$i]['name'] == 'Not assigned') {
			$assignedtolist .= '<option value="'.$assignedtoarray[$i]['id'].'" selected>'.$assignedtoarray[$i]['name'].'</option>';
		} else {
			$assignedtolist .= '<option value="'.$assignedtoarray[$i]['id'].'">'.$assignedtoarray[$i]['name'].'</option>';
		}
	}
	
	// build list of statuses for dropdown menu to change status
	$statuslist = '';
	$statuslistarray = Array();
	$statusquery = mysqli_query($connection, "SELECT * FROM status");
	while($row = mysqli_fetch_array($statusquery)) {
		$statuslistarray[] = $row;
	}
	for ($i=0; $i<count($statuslistarray); $i++) {
		if ($statuslistarray[$i]['status'] == 'Submitted') {
			$statuslist .= '<option value="'.$statuslistarray[$i]['id'].'" selected>'.$statuslistarray[$i]['status'].'</option>';
		} else {
			$statuslist .= '<option value="'.$statuslistarray[$i]['id'].'">'.$statuslistarray[$i]['status'].'</option>';
		}
	}

?>

<form id="tips_addtip_form" onsubmit="return checkTipFields();" action="posttip.php" method="post">
	<h1>Add a tip</h1>
	<div id="tips_addtip_required_text">Please enter required fields.</div>
	<table>
		<tbody>
			<!-- <tr class="odd_row"><td class="tips_title_cell">Tip title:</td><td class="tips_input_cell"><input size="40" name="title" id="title" type="text"><span id="title_span">*</span></td></tr> -->
			<tr class="odd_row"><td class="tips_title_cell">Tip date:</td><td class="tips_input_cell"><input size="40" name="tip_date" id="tip_date" type="text"><span id="details_span">*</span></td></tr>
			<tr class="even_row"><td class="tips_title_cell">Status:</td><td class="tips_input_cell"><select name="status" id="status"><?php echo $statuslist ?></select></td></tr>
			<tr class="odd_row"><td class="tips_title_cell">Assigned to:</td><td class="tips_input_cell"><select name="assigned_to" id="assigned_to"><?php echo $assignedtolist ?></select></td></tr>
			<tr class="even_row"><td class="tips_title_cell">Name:</td><td class="tips_input_cell"><input size="40" name="tipper_name" id="tipper_name" type="text"></td></tr>
			<tr class="odd_row"><td class="tips_title_cell">Location:</td><td class="tips_input_cell"><input size="40" name="tipper_location" id="tipper_location" type="text"></td></tr>
			<tr class="even_row"><td class="tips_title_cell">Phone:</td><td class="tips_input_cell"><input size="40" name="tipper_phone" id="tipper_phone" type="text"></td></tr>
			<tr class="odd_row"><td class="tips_title_cell">E-mail:</td><td class="tips_input_cell"><input size="40" name="tipper_email" id="tipper_email" type="text"></td></tr>
			<tr class="even_row"><td class="tips_title_cell details_cell">Details:</td><td class="tips_input_cell"><textarea rows="12" cols="60" name="tip_details" id="tip_details"></textarea><span id="details_span">*</span></td></tr>
			<tr><td></td><td><input type="submit" id="add_tip_submit"></td></tr>
		</tbody>
	</table>
</form>

<script type="text/javascript">
$(document).ready(function() { 

	$("#tip_date").datepicker();
	
});
</script>

<?php
	include 'footer.php';
?>