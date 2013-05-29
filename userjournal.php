<?php
	require_once("./siteconfig.php");

	session_start();
	if(!isset($_SESSION['username']))
	{
		$sitemanager->RedirectToURL("login.php");
	}
 ?>

<html>
<head>
	<title>Journal - Your Journal</title>
	<style type="text/css" media="all">
		@import "css/template.css";
	</style>
</head>
<body>

<div id="title_bar_top">
	<?php echo $_SESSION['blog_name'] ?> - <a href="newpost.php">new post</a>
	<div id="logOutLink"><a href="logout.php">logout</a></div>
</div>

<div id="blog_navbar_right"></div>

<div id="main_content">
	<span id='errors'><?php echo $sitemanager->GetErrors(); ?></span>
	<?php 
		echo $sitemanager->GetPosts();
	?>
</div>

</body>

</html>