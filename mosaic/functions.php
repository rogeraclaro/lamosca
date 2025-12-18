<?php

	// db connection
	$host="localhost"; 
	$db_user="mylamosca";
	$db_password="4XvEvhm1";
	$dbname="weblamosca";
	$mosaictable="mosaic";	
	
	function buildMosaic() {
		global $host;
		global $db_user;
		global $db_password;
		global $dbname;
		global $mosaictable;
		
		$db = mysql_connect($host, $db_user, $db_password) or die("Could not connect: " . mysql_error());
		
		$sqlstr = "SELECT id, color FROM $mosaictable ORDER BY data ASC";
		$erg = mysql_db_query($dbname, $sqlstr);
		
		$columns = 43;
		$counter = 1;
		
		$mytable = "<table cellspacing='0' cellpadding='1'>";
		while($row=mysql_fetch_row($erg)) {
						
			if($counter == 1)
				$mytable .= "<tr>";
				
			$mytable .= "<td><a href='#' onClick='tw(" . $row[0] . ")'><img src='0" . $row[1] . ".gif' border='0'></a></td>";
			
			if($counter == $columns) {
				$mytable .= "</tr>\n\r";
				$counter = 1;
			} else {
				$counter++;
			}
		}
		if($counter != 1) {
			for($i=$counter; $i <= $columns; $i++) {
				$mytable .= "<td></td>";
				if($i == $columns) 
					$mytable .= "</tr>\n\r";
			}
		}
		$mytable .= "</table>";
		
		echo $mytable;
		
		mysql_close($db);
	}
	
?>