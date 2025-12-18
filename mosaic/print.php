<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

	<head>
		<meta http-equiv="content-type" content="text/html;charset=ISO-8859-1">
		<title>Mosaico</title>
		<link rel="stylesheet" href="lamosca.css" type="text/css">
	</head>

	<body bgcolor="#ffffff">


<table width="" border="0" cellpadding="2" cellspacing="0">

<?php
	require('functions.php');
	db_connect();

		$sqlstr = "SELECT id, nom, mail, web, comentari, color, data FROM $mosaictable ORDER BY data ASC";
		$erg = db_query($dbname, $sqlstr);

		$columns = 43;
		$counter = 1;

		$mytable = "<table cellspacing='0' cellpadding='1'>";
		while($row = db_fetch_row($erg)) {
			$nom = stripslashes(htmlentities($row[1]));
			$mail = stripslashes(htmlentities($row[2]));
			$row[3] = str_replace("http://http://", "http://", $row[3]);
			if($row[3] == "http://")
				$web = "";
			else
				$web = stripslashes(htmlentities($row[3]));
			$comentari = stripslashes(htmlentities($row[4]));
			$color = $row[5];
			$data = date("d.m.y", $row[6]);
			?>
				
				<tr>
				    <td align="right" valign="middle"><?php echo $counter; ?></td>
					<td valign="middle">
					<?php
					
						if($color == 0)
							echo "<img src='00.gif' />";
						else if($color == 1)
							echo "<img src='01.gif' />";
						else if($color == 2)
							echo "<img src='02.gif' />";
						else if($color == 3)
							echo "<img src='03.gif' />";
						else if($color == 4)
							echo "<img src='04.gif' />";
					?>
					</td>
				    <td align="left" valign="middle"><?php echo $data; ?></td>
				    <td align="left" valign="middle"><b><?php echo $nom; ?></b></td>
				    <td align="left" valign="middle"><?php echo $comentari; ?></td>
				    <td align="left" valign="middle"><?php echo $web; ?></td>
				
				  </tr>
				

					<?php
					$counter++;
					}
					db_close();
			?>
				</table><br>
	</body>

</html>