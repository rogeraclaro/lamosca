<?php

	// Include database abstraction layer (PHP 8.x compatible)
	require_once(__DIR__ . '/../phpincludes/database.php');

	function buildMosaic() {
		global $dbname;
		global $mosaictable;

		db_connect();
		
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
		
		db_close();
	}
	
?>