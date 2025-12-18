<?php
	require('functions.php');
	
	$db = mysql_connect($host, $db_user, $db_password) or die("Could not connect: " . mysql_error());
	
	$sqlstr = "SELECT id, nom, mail, web, comentari, color, data FROM $mosaictable WHERE id = $id";
	$erg = mysql_db_query($dbname, $sqlstr);
	
	$row=mysql_fetch_row($erg);
	
	$nom = stripslashes(htmlentities($row[1]));
	$mail = stripslashes(htmlentities($row[2]));
	$row[3] = str_replace("http://http://", "http://", $row[3]);
	$web = stripslashes(htmlentities($row[3]));
	$comentari = stripslashes(htmlentities($row[4]));
	$color = $row[5];
	$data = $row[6];
	
	mysql_close($db);

?>




<html>

<head>

<title>Mosaic</title>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<script language="JavaScript">

var num,cont=0,n;

function tmt_winLaunch(theURL,winName,targetName,features) { 



eval(winName+"=window.open('"+theURL+"','"+targetName+"','"+features+"')");



}

</script>



</head>



<body text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<SCRIPT LANGUAGE="JavaScript">

<!--

var browser = navigator.appName;

if (navigator.appVersion.indexOf('Mac') != -1)

	{


			document.write('<link rel="stylesheet" href="../css/lamosca.css" type="text/css">');


	}

else

	{

		if (browser=='Netscape')

			{

			document.write('<link rel="stylesheet" href="../css/ntscp.css" type="text/css">');

			}

			else

			{

			document.write('<link rel="stylesheet" href="../css/lamosca.css" type="text/css">');

			}

	}

//-->



</SCRIPT>



<table width="100%" border="0" cellpadding="0" cellspacing="0">

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

</table>




</body>

</html>

