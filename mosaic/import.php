

<?php
	require('functions.php');
	
	

			$db = mysql_connect($host, $db_user, $db_password) or die("Abort: Connection to '$host' not possible.");


			$tmpimport = fopen("mosaic_date.txt","r"); 
		
			if($tmpimport)
			{
		
				$id = 0;
				$errid = 0;
				
				while (!feof($tmpimport))
				{
					$rowstr=fgets($tmpimport, 4096);
					$row = explode(";",$rowstr);

					$anz = count($row);
					for ($i=0; $i<$anz; $i++){
						//$row[$i] = str_replace ("###", "&acute;", $row[$i]);
						//$row[$i] = str_replace ("##", chr(34), $row[$i]);
						//$row[$i] = str_replace("<br>", chr(10), $row[$i]);
						//$row[$i] = unhtmlentities ($row[$i]);
					}
					
					
					if($row[0] != "" && $row[0] != 0)
					{	
						$row[1] = str_replace(" 00:00:00","",$row[1]);					
						$mydate = explode("/",$row[1]);
						//$row[1] = $mydate[0] . " " . $mydate[1] . " " . $mydate[2];
						echo $row[0] . ";;" . $row[1] . "<br>";
						$row[1] = strtotime($mydate[2] . "-" . $mydate[1] . "-" . $mydate[0]);
						//$row[6] = date("d.m.Y", $row[6]);
						echo $row[0] . ";;" . $row[1] . "\r\n";
						
						$sqlstr = "UPDATE $mosaictable SET ";
						$sqlstr .= "data='". $row[1] ."' WHERE id = $row[0]";
						$erg = mysql_db_query($dbname, $sqlstr);
						$num = mysql_affected_rows();
						
						
						if($num>0)
							echo " OK!<br>";
						else
							echo " <b>NOT OK!</b><br>";
						
						
						$id = $id + 1;
					}
					else{
						$errid = $errid +1;
					}
				}

			}

			mysql_close($db);	
?>