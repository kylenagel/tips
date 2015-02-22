<?php
	
	include 'header.php';
	
	require 'includes/credentials.php';
	
	$connection = mysqli_connect($creds['host'], $creds['username'], $creds['password'], $creds['dbname']);
	
	// Check connection
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
	// build dropdown menu of users
	$usersquery = mysqli_query($connection, "SELECT * FROM users ORDER BY name");
	$users = Array();
	while ($user = mysqli_fetch_array($usersquery)) {
		$users[] = $user;
	}
	$usersdropdown = '<option value="All">-- All --</option>';
	for ($i=0; $i<count($users); $i++) {
		$usersdropdown .= '<option value="'.$users[$i]['name'].'">'.$users[$i]['name'].'</option>';
	}
	
	// build dropdown menu for status
	$statusquery = mysqli_query($connection, "SELECT * FROM status ORDER BY status");
	$status = Array();
	while ($user = mysqli_fetch_array($statusquery)) {
		$status[] = $user;
	}
	$statusdropdown = '<option value="All">-- All --</option>';
	for ($i=0; $i<count($status); $i++) {
		$statusdropdown .= '<option value="'.$status[$i]['status'].'">'.$status[$i]['status'].'</option>';
	}
	
?>

<form id="search_tips" action="searchtipsresults.php" method="post">
	<h1>Search tips</h1>
	<table>
		<tbody>
			<tr class="odd_row">
				<td class="tips_title_cell">Taken by:</td>
				<td><select type="text" id="tips_search_taken_by" name="tips_search_taken_by"><?php echo $usersdropdown ?></select></td>
			</tr>
			<tr>
				<td class="tips_title_cell">Assigned to:</td>
				<td><select type="text" id="tips_search_assigned_to" name="tips_search_assigned_to"><?php echo $usersdropdown ?></select></td>
			</tr>
			<tr class="odd_row">
				<td class="tips_title_cell">Status:</td>
				<td><select type="text" id="tips_search_status" name="tips_search_status"><?php echo $statusdropdown ?></select></td>
			</tr>
			<tr>
				<td class="tips_title_cell">Between dates:</td>
				<td><input type="text" id="tips_search_start_date" name="tips_search_start_date"> and <input type="text" id="tips_search_end_date" name="tips_search_end_date"></td>
			</tr>
			<tr class="odd_row">
				<td class="tips_title_cell">Keyword:</td>
				<td><input type="text" id="tips_search_description" name="tips_search_description"></td>
			</tr>
		</tbody>
	</table>
	<input type="submit" id="tips_search_submit" name="tips_search_submit" value="Submit">
</form>

<script type="text/javascript">
$(document).ready(function() { $("#tips_search_start_date").datepicker(); $("#tips_search_end_date").datepicker(); });
</script>


<?php
	include 'footer.php';
?>