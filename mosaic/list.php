<SCRIPT LANGUAGE="JavaScript">

<!--

var browser = navigator.appName;

if (navigator.appVersion.indexOf('Mac') != -1)

	{


			document.write('<link rel="stylesheet" href="lamosca.css" type="text/css">');


	}

else

	{

		if (browser=='Netscape')

			{

			document.write('<link rel="stylesheet" href="../css/ntscp.css" type="text/css">');

			}

			else

			{

			document.write('<link rel="stylesheet" href="lamosca.css" type="text/css">');

			}

	}

//-->



</SCRIPT>


<?php
	require('functions.php');
	db_connect();

		$sqlstr = "SELECT id, nom, mail, web, comentari, color, data FROM $mosaictable ORDER BY data DESC";
		$erg = db_query($dbname, $sqlstr);

		$columns = 43;
		$counter = 1;

		$mytable = "<table cellspacing='0' cellpadding='1'>";
		while($row = db_fetch_row($erg)) {
			$nom = stripslashes(htmlentities($row[1]));
			$mail = stripslashes(htmlentities($row[2]));
			$row[3] = str_replace("http://http://", "http://", $row[3]);
			$web = stripslashes(htmlentities($row[3]));
			$comentari = stripslashes(htmlentities($row[4]));
			$color = $row[5];
			$data = $row[6];
			?>
				<table width="300" border="0" cellpadding="0" cellspacing="0">
				
				<?php
				
					if($color == 0)
						echo "<tr valign='top' bgcolor='#D58137'>";
					else if($color == 1)
						echo "<tr valign='top' bgcolor='#938156'>";
					else if($color == 2)
						echo "<tr valign='top' bgcolor='#763D12'>";
					else if($color == 3)
						echo "<tr valign='top' bgcolor='#F1BB00'>";
					else if($color == 4)
						echo "<tr valign='top' bgcolor='#E2E0CD'>";
				?>
					
				
				
				
				  <td colspan="3" height="44"><img src="img/icona.gif" width="26" height="23"></td>
				
				  </tr>
				
				  <tr> 
				
				    <td width="3%" height="15"><img src="img/pt.gif" width="10" height="40"></td>
				
				    <td width="50%" height="15"><b><?php echo $nom; ?></b></td>
				
				    <td width="47%" height="15">&nbsp;</td>
				
				  </tr>
				
				  <tr> 
				
				    <td width="3%" valign="top" height="20"><img src="img/pt.gif" width="8" height="30"></td>
				
				    <td width="50%" height="20" valign="top"><?php echo $comentari; ?></td>
				
				    <td width="47%" height="20" valign="top"> 
				
				      <p>&nbsp;</p>
				
				    </td>
				
				  </tr>
				
				  <tr> 
				
				    <td width="3%"><img src="img/pt.gif" width="10" height="20"></td>
				
					
						<?php 
						
						if($web != "" && $web != "http://")
				    		echo "<td width='50%'><b><a href='$web' target='_blank'>$web</a></td>";
						else
							echo "<td width='50%'></td>";
						
						?>
				
				    	<td width="47%">&nbsp;</td>
				
				
				  </tr>
				
				</table><br>

		<?php
		}
		db_close();
?>
