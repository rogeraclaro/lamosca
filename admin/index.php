<?php

	require 'functions.php';
	

	$db = mysql_connect($db_host, $db_user, $db_password) or die("Abort: Connection to '$db_host' not possible.");


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
		<title>lamosca.admin</title>
		<meta http-equiv="imagetoolbar" content="no" />
		<link href="css/base.css" rel="stylesheet" type="text/css" />
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	</head>

	<body>
	<div id="head"> 
  		<h1><span><a href="index.php">lamosca.admin</a></span></h1>
	</div>
	<div id="container">
    <div id='leftframe'>
	<?php
		$navItem = "start";
		require 'navigation.php';

		mysql_close($db);
				
	?>
	</div> 
	</div> 

	</div> 
	</div> 
	
	</body>

</html>
