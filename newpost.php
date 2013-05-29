<?php
	session_start();
?>

<html>
<head>
	<title>New post</title>
	<style type="text/css" media="all">
		@import "css/template.css";
	</style>
</head>
<body>
	<div id="title_bar_top">
	<?php echo $_SESSION['username'] . 's' ?> blog. - <a href="newpost.php">new post</a>
	<div id="logOutLink"><a href="logout.php">logout</a></div>
</div>
</body>
</html>
