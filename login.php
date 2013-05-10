<?php
require_once("./siteconfig.php");
//require_once("./databaseresult.php");

if(isset($_POST["submitted"]))
{
	if($sitemanager->Login())
	{
		$sitemanager->RedirectToURL("userjournal.php");
	}
}

?>

<html>
<head>
	<title>Journal - Login</title>
</head>

<body>

<h3>Login here</h3>

<div><span name='errors' id='errors'><?php echo $sitemanager->GetErrors(); ?></span></div>

<form id="loginForm" action="login.php" method="post">

<input type='hidden' id='submitted' name='submitted' value='1' />

<label for="username">Username:</label>
<input type="text" name="username" id="username" maxlength="20" />

<br />

<label for="password">Password:</label>
<input type="password" name="password" id="password" maxlength="20" />

<input type='submit' name='submit' value='Submit' />

</form>


<h3>New to the site?  Register here</h3>
<form id="regForm" action="register.php" method="get">

<input type='submit' name='register' value='Register' />

</form>

</body>

</html>

<!--<script type="text/javascript">

</script> -->