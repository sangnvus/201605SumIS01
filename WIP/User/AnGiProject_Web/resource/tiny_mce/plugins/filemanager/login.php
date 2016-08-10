<?php
/**
 * login.php
 *
 * @author Moxiecode
 * @copyright Copyright © 2005, Moxiecode Systems AB, All rights reserved.
 *
 * This is a page that you should implement your own login logic to.
 */

// Do login
$msg = "";
$username = "demo";
$password = "demo";

if (isset($_REQUEST['submitBtn'])) {
	// If password match, then set login
	if ($_REQUEST['login'] == $username && $_REQUEST['password'] == $password) {
		// Set session
		session_start();
		$_SESSION['isLoggedIn'] = true; // Set the session that MCFileManager verify access against
		$_SESSION['user'] = $_REQUEST['login'];

		// Redirect with params
		header("location: frameset.php?" . $_SERVER['QUERY_STRING']);
		die;
	} else
		$msg = "Wrong username/password";
}
?>

<html>
<head>
<title>Sample login page</title>

<link href="themes/default/css/general.css" rel="stylesheet" type="text/css" />
<link href="themes/default/css/filelist.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/javascript">
<!--
	// Remove frameset
	if (top.location != location)
		top.location.href = document.location.href;
-->
</script>
</head>
<body>
<table border="0" width="100%" height="100%">
	<tr height="100%">
		<td height="100%" valign="middle" width="100%" align="middle">
			<table border="0">
				<tr>
					<td><form method="post">
						<fieldset>
							<legend align="left">File Manager Login</legend>
							<table border="0">
								<tr>
									<td>Username: </td>
									<td><input type="text" name="login" value="" class="inputText" /></td>
								</tr>
								<tr>
									<td>Password: </td>
									<td><input type="password" name="password" value="" class="inputText" /></td>
								</tr>
							</table>
							<br />
							<input type="submit" name="submitBtn" value="Login" class="button" />
						</fieldset>
							<br />
							<?php
							if ($msg != "")
								echo $msg;
							?>
						</form>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</body>
</html>
