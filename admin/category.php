<?php

	require_once 'functions.php';
	

	$db = mysql_connect($db_host, $db_user, $db_password) or die("Abort: Connection to '$db_host' not possible.");


	if($CatNew || $CatChange) {
		$inturl = cleanfilename($inturl);
	}
	
	if($CatNew)
		{
			$sqlstr = "SELECT position FROM $categorytable ORDER BY position";
			$erg = mysql_db_query($dbname, $sqlstr);
			while ($row=mysql_fetch_row($erg)) {
				$position = $row[0];
			}
			$position++;
			$sqlstr = "INSERT $categorytable VALUES (0, $position, '$title', '$inturl', '', ".time().")";
			$erg = mysql_db_query($dbname, $sqlstr);
			$num = mysql_affected_rows();
			if($num>0)
				$message = "La categoria s´ha afegit";
			else
				$message = "La categoria no s´ha afegit!";
		}		
	
	if($cid)
		{
			$sqlstr = "UPDATE $categorytable SET title = '$title', inturl = '$inturl', modification_date = ".time()." WHERE id = $cid";
			$erg = mysql_db_query($dbname, $sqlstr);
			$num = mysql_affected_rows();
			if($num>0)
				$message = "La categoria s´ha modificat!";
			else
				$message = "La categoria no s´ha modificat!";
		}		

	if($CatDelete)
		{	
			$sqlstr = "DELETE FROM $categorytable WHERE id = $CatDelete";
			$erg = mysql_db_query($dbname, $sqlstr);
			$num = mysql_affected_rows();
			if($num>0)
				$message = "La categoria s´ha esborrat!";
			else
				$message = "La categoria no s´ha esborrat!";

			$sqlstr = "SELECT id FROM $categorytable ORDER BY position";
			$erg = mysql_db_query($dbname, $sqlstr);
 			$pos = 1;
			while ($row=mysql_fetch_row($erg)) {
				$cid = $row[0];
				$sqlstr2 = "UPDATE $categorytable SET position = $pos WHERE id = $cid";
				$erg2 = mysql_db_query($dbname, $sqlstr2);
				$pos++;
			}

		}

if($CatOrder) {

	if (isset($_POST['content_list']) && is_array($_POST['content_list'])) {
		$pos = 1;
		foreach ($_POST['content_list'] as $cid) {
			$sqlstr = "UPDATE $categorytable SET position = $pos WHERE id = $cid";
			$erg = mysql_db_query($dbname, $sqlstr);
			$pos++;
		}
	}
	
} else {

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
		<title>lamosca.admin</title>
		<meta http-equiv="imagetoolbar" content="no" />
		<link href="css/base.css" rel="stylesheet" type="text/css" />
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<script src="../js/prototype.js" type="text/javascript"></script>
		<script src="../js/scriptaculous.js" type="text/javascript"></script>
		<script src="../js/ajax.js" type="text/javascript"></script>
	</head>

	<body>
	<div id="head"> 
  		<h1><span><a href="index.php">lamosca.admin</a></span></h1>
	</div>
	<div id="container">
    <div id='leftframe'>
	<?php
		$navItem = "categories";
		require 'navigation.php';
	?>
    </div>
	
	<div id="centerframe"> 
	<h2>Editar categories<?php if($message) echo "<div class='zusatz'>$message</div>"; ?></h2>
	<div class="kasten_inhalt_1"> 
	<?php				
					
		$sqlstr = "SELECT id,position,title,inturl FROM $categorytable ORDER BY position";
		$erg = mysql_db_query($dbname, $sqlstr);
			
		$total = mysql_num_rows($erg);
		
		echo "	 <div class=\"listing\">\n";
		echo "	  <ul id=\"content_list\">\n";

		while ($row=mysql_fetch_row($erg)) {

			$cid = $row[0];
			$position = $row[1];
			$title = $row[2];
			$inturl = $row[3];
			
			echo "   <li id=\"c_".$cid."\" style=\"margin-bottom:30px\">\n";
			echo "   <form action='category.php' method='post' name='EditCatForm$cid' enctype='multipart/form-data'>\n";

			echo "     <div class=\"handle\">moure</div>\n";
			echo "     <div class=\"action\"><a href=\"javascript:document.EditCatForm$cid.submit()\">guardar</a></div>\n";
			echo "     <div class=\"action\"><a href=\"javascript:deletecat('$cid')\">esborrar</a></div>\n";
			echo "     <div class=\"opener\">";

			echo "<input type='hidden' name='cid' value='$cid' />";
			echo "Títol<br /><input type='text' name='title' style='width:250px' value='$title' /><br />\n";
			echo "Nom de carpeta (lamosca.info/<b>categoria</b>/projecte.html)<br /><input type='text' name='inturl' style='width:250px' value='$inturl' /><br />\n";

			echo "</div>\n";
			echo "     <div class=\"clear\"></div>\n";

			echo "   </form>\n";
			echo "   </li>\n";

		}
		
		echo "	  </ul>\n";
		echo "	 </div>\n";


		echo "   <br /><h4>Afegir categoria</h4>\n";		
		echo "	 <div class=\"listing\">\n";
		echo "	  <ul id=\"add_content_list\">\n";
			
		echo "   <li id=\"c_new\" style=\"margin-bottom:30px\">\n";
		echo "   <form action='category.php' method='post' name='EditCatFormNew' enctype='multipart/form-data'>\n";

		echo "     <div class=\"action\"><a href=\"javascript:document.EditCatFormNew.submit()\">Afegir</a></div>\n";
		echo "     <div class=\"opener\">";

		echo "<input type='hidden' name='CatNew' value='1' />";
		echo "Títol<br /><input type='text' name='title' style='width:250px' value='' /><br />\n";
		echo "Nom de carpeta (lamosca.info/<b>categoria</b>/projecte.html)<br /><input type='text' name='inturl' style='width:250px' value='' /><br />\n";

		echo "</div>\n";
		echo "     <div class=\"clear\"></div>\n";

		echo "   </form>\n";
		echo "   </li>\n";

		echo "	  </ul>\n";
		echo "	 </div>\n";

				
		echo "<script type='text/javascript'>function deletecat(CID){check = confirm('Segur?'); if(check == true) window.location.href = 'category.php?CatDelete='+CID;}</script>";

	?>
	</div> 

	</div> 
	</div> 
	
	<script type="text/javascript">
 	
	 function updateOrder()
	 {
		 var options = {
						 method : 'post',
						 onComplete: setTimeout("updateNavi(<?php echo "'$cid','$pid','$navItem'";?>)",200),
						 parameters : Sortable.serialize('content_list')
					   };
		 new Ajax.Request('category.php?CatOrder=1', options);
		 		 
	 }

	 function updateNavi(cId,pId,navItem) {

		 var options = {
						 method : 'post',
						 onSuccess: function(navigation) {document.getElementById("leftframe").innerHTML = navigation.responseText;}
					   };
		 new Ajax.Request("navigation.php?ajax=1&cid="+cId+"&navItem="+navItem, options);
			 
	 }

	 Sortable.create('content_list', { handle:'handle', onUpdate: updateOrder });

	</script>
	
	</body>

</html>	
	
<?php

}

mysql_close($db);			

?>