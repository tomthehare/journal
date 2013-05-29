<?php
require_once('./siteconfig.php');

	if(isset($_POST["register_submitted"]))
	{
		if($sitemanager->Register())
		{
			$sitemanager->RedirectToURL("userjournal.php");
		}
	}
?>

<html>
<head>
	<title>PHP Journal - Registration</title>
	<style type="text/css" media="all">
		@import "css/template.css";
	</style>
</head>

<body>

<H3>Please register here</h3>
<form id='regForm' action='register.php' method='post'/>
	<div id='regErrorsDiv'>
		<span id='errors'>
			<?php echo $sitemanager->GetErrors(); ?>
		</span>
	</div>
	Username: <input type='text' id='newUsernameTxt' name='newUsernameTxt' />
	<br />
	Password: <input type='password' name='newPasswordTxt1' id='newPasswordTxt1'/>
	<br />
	Password again: <input type='password' name='newPasswordTxt2' id='newPasswordTxt2'/>
	<p />

	Blog title: <input type='text' name='blogTitle' id='blogTitle' />
	<br />
	<input type='hidden' name='register_submitted' value='1' />

	<input type='submit' id='registerSubmitBtn' value='Submit' />
</form>

</body>
</html>