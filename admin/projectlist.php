<?php

	require 'functions.php';

	$db = mysql_connect($db_host, $db_user, $db_password) or die("Abort: Connection to '$db_host' not possible.");
		
	if(!$cid && !$allProjects) {
		echo "<script type='text/javascript'>window.location.href = 'index.php';</script>";
	} else if($cid) {
		$sqlstr = "SELECT id,position,title,content FROM $categorytable WHERE id = $cid";
		$erg = mysql_db_query($dbname, $sqlstr);
		$row=mysql_fetch_row($erg);
		$title = "Editar categoria \"".$row[2]."\"";
		$content = $row[3];
	} else if($allProjects) {
		$title = "Tots els projectes";
	}

	if($projectActivate) {
		$sqlstr = "SELECT active FROM $projecttable WHERE id = '$projectActivate'";
		$erg = mysql_db_query($dbname, $sqlstr);
		$row=mysql_fetch_row($erg);
		if($row[0])
			$active = 0;
		else
			$active = 1;
		$sqlstr = "UPDATE $projecttable SET active = $active WHERE id = '$projectActivate'";
		$erg = mysql_db_query($dbname, $sqlstr);
	}		

	if($addProject)
		{
			$content = $newProject . "," . $content;
			deleteProject('xxxx');
			$sqlstr = "UPDATE $categorytable SET content = '$content' WHERE id = $cid";
			$erg = mysql_db_query($dbname, $sqlstr);
			$num = mysql_affected_rows();
			if($num>0)
				$message = "Projecte ha estat afegit!";
			else
				$message = "Projecte no ha estat afegit!";
		}		
	
	if($delFromSystem)
		{
			$sqlstr = "DELETE FROM $projecttable WHERE id = $pid";
			$erg = mysql_db_query($dbname, $sqlstr);
			$num = mysql_affected_rows();

			if($num>0) {
				$message = "Projecte ha estat esborrat!";

				deletefolder("../img/projects/$pid");
		
				$sqlstr = "SELECT id FROM $categorytable";
				$erg = mysql_db_query($dbname, $sqlstr);
				while ($row=mysql_fetch_row($erg)) {
					cleanProject($row[0]);
				}
			} else {
				$message = "Proyecto no ha estat esborrat!";
			}
		}	
			
	if($delProject)
		{
			deleteProject($pid);
			$sqlstr = "UPDATE $categorytable SET content = '$content' WHERE id = $cid";
			$erg = mysql_db_query($dbname, $sqlstr);
			$num = mysql_affected_rows();
			if($num>0) {
				$message = "Projecte ha estat esborrat!";
			} else
				$message = "Projecte no ha estat esborrat!";
		}		
		
	if($ProjectOrder) {
 			$content_list = implode(",", $_POST['content_list']);
			$sqlstr = "UPDATE $categorytable SET content = '$content_list' WHERE id = $ProjectOrder";
			$erg = mysql_db_query($dbname, $sqlstr);
	}
	
	$content = explode(",", $content);

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
		if($allProjects == 1) 
			$navItem = "allprojects";
		else
			$navItem = "projectlist";
		require 'navigation.php';
	?>
    </div>
	
	<div id="centerframe"> 
	<h2><?php echo $title; if($message) echo "<div class='zusatz'>$message</div>"; ?></h2>
	<div class="kasten_inhalt_1"> 
	<?php				
					

		if($allProjects) {

			$sqlstr = "SELECT id,title_intern,active FROM $projecttable ORDER BY title_intern";
			$erg = mysql_db_query($dbname, $sqlstr);
			
			echo "	 <div class=\"listing\">\n";
			echo "	  <ul id=\"content_list\">\n";

			while ($row=mysql_fetch_row($erg)) {
				$pid = $row[0];
				$title = $row[1];
				if(!$title)
					$title="&nbsp;&nbsp;&nbsp;";
				if($row[2] == 1)
					$activeChecked = "checked";
				else
					$activeChecked = "";
				
					echo "   <li id=\"c_".$pid."\">\n";
					
					echo "     <div class=\"action\"><a href=\"javascript:deleteFromSystem('$pid');\">esborrar del sistema</a></div>\n";
					echo "     <div class=\"opener\">";
					echo "<div class=\"inputfield\"><input type='checkbox' name='active' value='1'  onchange='document.location.href=\"projectlist.php?cid=$cid&projectActivate=$pid\";' $activeChecked /></div>";
					echo "<div class=\"titlelink\"><a href=\"project.php?cid=$cid&pid=$pid\">$title</a></div>";
					echo "</div>\n";
					echo "     <div class=\"clear\"></div>\n";

					echo "   </li>\n";

			}
			echo "	  </ul>\n";
			echo "	 </div>\n";


			echo "<script type='text/javascript'>function deleteFromSystem(PID){check = confirm('Segur?'); if(check == true) window.location.href = 'projectlist.php?allProjects=1&delFromSystem=1&pid='+PID;}</script>\n";

		} else {
			
			echo "	 <div class=\"listing\">\n";
	
			echo "	  <ul id=\"content_list\">\n";
  
			for($i=0; $i<count($content); $i++) {	
				if($content[$i]) {
					$sqlstr = "SELECT id,title_intern,active FROM $projecttable WHERE id = " . $content[$i];
					$erg = mysql_db_query($dbname, $sqlstr);
					$row=mysql_fetch_row($erg);
					$pid = $row[0];
					$title = $row[1];
					if(!$title)
						$title="&nbsp;&nbsp;&nbsp;";
					if($row[2] == 1)
						$activeChecked = "checked";
					else
						$activeChecked = "";

					echo "   <li id=\"c_".$pid."\">\n";
					
					echo "     <div class=\"handle\">moure</div>\n";
					echo "     <div class=\"action\"><a href=\"project.php?cid=$cid&pid=$pid\">modificar</a></div>\n";
					echo "     <div class=\"opener\">";
					echo "<div class=\"inputfield\"><input type='checkbox' name='active' value='1'  onchange='document.location.href=\"projectlist.php?cid=$cid&projectActivate=$pid\";' $activeChecked /></div>";
					echo "<div class=\"titlelink\"><a href=\"project.php?cid=$cid&pid=$pid\">$title</a></div>";
					echo "</div>\n";
					echo "     <div class=\"clear\"></div>\n";
					
					echo "   </li>\n";



				}
			}
			
			echo "	  </ul>\n";
			echo "	 </div>\n";

			echo "	</div>\n"; 
			echo "	<h2>Projectes per afegir</h2>\n"; 
			echo "	<div class='kasten_inhalt_1'>\n"; 
			echo "	 <div class=\"listing\">\n";
			echo "	  <ul id=\"new_content_list\">\n";
			project_listing();	
			echo "	  </ul>\n";
			echo "	 </div>\n";
	


			echo "<script type='text/javascript'>function deleteProject(CID, PID){check = confirm('Segur?'); if(check == true) window.location.href = 'projectlist.php?delProject=1&cid='+CID+'&pid='+PID;}</script>\n";
		
		}
		mysql_close($db);

	?>
	</div> 

	</div> 
	</div> 
	
<?php
	if(!$allProjects) {
?>
	<script type="text/javascript">
 	
	 function updateOrder()
	 {
		 var options = {
						 method : 'post',
						 onComplete: setTimeout("updateNavi(<?php echo "'$cid','$pid','$navItem'";?>)",200),
						 parameters : Sortable.serialize('content_list')
					   };

		 new Ajax.Request('projectlist.php?ProjectOrder=<?php echo $cid; ?>', options);

	 }

	 function updateNavi(cId,pId,navItem) {

		 var options = {
						 method : 'post',
						 onSuccess: function(navigation) {document.getElementById("leftframe").innerHTML = navigation.responseText;}
					   };
		 new Ajax.Request("navigation.php?ajax=1&cid="+cId+"&navItem="+navItem, options);
			 
	 }

	 Sortable.create('content_list', { handle:'handle', containment:["content_list","new_content_list"], dropOnEmpty:true, onUpdate: updateOrder });
	 Sortable.create('new_content_list', { handle:'handle', containment:["content_list","new_content_list"], dropOnEmpty:true });

 </script>
<?php
	}
?>

 </body>
</html>	
	
