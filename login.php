<?php
require_once("./siteconfig.php");
require_once("./blog.php");
//require_once("./databaseresult.php");

if(isset($_POST["submitted"]))
{
	if($sitemanager->Login())
	{
		session_start();
		$_SESSION['username'] = $_POST['username'];
		$blog_details = $sitemanager->GetBlogAssociatedWithUser($_POST['username']);

		if(isset($blog_details))
		{
			$_SESSION['blog_id'] = $blog_details->GetBlogId();
			$_SESSION['blog_name'] = $blog_details->GetBlogName();
		}
		else
		{
			$_SESSION['blog_id'] = -1;
			$_SESSION['blog_name'] = null;
		}

		$sitemanager->RedirectToURL("userjournal.php");
	}
}

?>

<html>
<head>
	<title>Journal - Login</title>
	<style type="text/css" media="all">
		@import "css/login.css";
	</style>
</head>

<body>

<div id="login_content">
	<div id="site_title">PHP Journal</div>

	<div><span name='errors' id='errors'><?php echo $sitemanager->GetErrors(); ?></span></div>

	<form id="loginForm" action="login.php" method="post">

	<input type='hidden' id='submitted' name='submitted' value='1' />

	<label for="username">Username:</label>
	<input type="text" name="username" id="username" maxlength="20" />

	<br />

	<label for="password">Password:</label>
	<input type="password" name="password" id="password" maxlength="20" />
	<br />
	<input type='submit' name='submit' value='Submit' />

	</form>


	<div id="register_label">New to the site?  Register here</div>
	<form id="regForm" action="register.php" method="get">

	<input type='submit' name='register' value='Register' />

	</form>
</div>

</body>

</html>

<!--<script type="text/javascript">

</script> -->