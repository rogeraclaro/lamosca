<?php

	require 'functions.php';

	// Database connection is now handled by phpincludes/database.php
	db_connect();


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

		db_close();
				
	?>
	</div> 
	</div> 

	</div> 
	</div> 
	
	</body>

</html>
