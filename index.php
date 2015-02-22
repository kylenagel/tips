<?php 
	
	include 'session.php';
	
	if ($_SESSION) {
		header("Location: home.php");
	}
	
	echo '<link rel="stylesheet" stype="text/css" href="css/tipsmaster.css">';
	echo '<link rel="stylesheet" stype="text/css" href="css/tipsfonts.css">';
	
?>
<html>
<head>
	<title>I-Team Tips</title>
</head>
<body>
<div id="login_page_container">
	<form name="login" id="login_form" method="post" action="checklogin.php">
		<div class="login_form_hed">Login</div>
		<table>
			<tbody>
				<tr>
					<td class="login_form_text_cell">Username</td>
					<td><input type="text" id="username" name="username"></td>
				</tr>
				<tr>
					<td class="login_form_text_cell">Password</td>
					<td><input type="password" id="password" name="password"></td>
				</tr>
			</tbody>
		</table>
		<div id="login_form_submit_container">
			<input type="submit" id="password_submit" name="loginsubmit">
		</div>
	</form>
</div>
</body>
</html>