<?php

	// Get variables
	$ajax = $_POST['ajax'] ?? $_GET['ajax'] ?? null;
	$pid = $_POST['pid'] ?? $_GET['pid'] ?? $pid ?? null;
	$cid = $_POST['cid'] ?? $_GET['cid'] ?? $cid ?? null;
	$navItem = $navItem ?? null;

	if($ajax) {
		require_once 'functions.php';
		db_connect();
	}

    // Bei den verschachtelten Listen muss die untergeordnete Liste innerhalb des vorangehenden ¸bergeordneten li-Tags stehen. //-->
 	
	echo "     <ul>\n";
	
	if($pid == 1) {
		echo "       <li>\n";
		echo "         <div class='rub0active'><a href='project.php?pid=1'>Inici</a></div>\n";
		echo "       </li>\n";
	} else {
		echo "       <li>\n";
		echo "         <div class='rub0'><a href='project.php?pid=1'>Inici</a></div>\n";
		echo "       </li>\n";
	}
	
	if($navItem == "categories") {
		echo "       <li>\n";
		echo "         <div class='rub0active'><a href='category.php'>Editar categories</a></div>\n";
		echo "       </li>\n";
	} else {
		echo "       <li>\n";
		echo "         <div class='rub0'><a href='category.php'>Editar categories</a></div>\n";
		echo "       </li>\n";
	}
	
	categoryTree();

	if($navItem == "allprojects") {
		echo "       <li>\n";
		echo "         <div class='rub0active'><a href='projectlist.php?allProjects=1'>Tots els projectes</a></div>\n";
		echo "       </li>\n";
	} else {
		echo "       <li>\n";
		echo "         <div class='rub0'><a href='projectlist.php?allProjects=1'>Tots els projectes</a></div>\n";
		echo "       </li>\n";
	}
	
	if($navItem == "newproject") {
		echo "       <li>\n";
		echo "         <div class='rub0active'><a href='project.php'>Afegir projecte</a></div>\n";
		echo "       </li>\n";
	} else {
		echo "       <li>\n";
		echo "         <div class='rub0'><a href='project.php'>Afegir projecte</a></div>\n";
		echo "       </li>\n";
	}	
	
	echo "     </ul>\n";


	if($ajax) {
		db_close();			
	}


function categoryTree() {

	global $cid, $pid, $newProd, $content, $dbname, $categorytable, $projecttable, $navItem;
	
		
		$sqlstr = "SELECT id,title,content FROM $categorytable ORDER BY position";
		$erg = mysql_db_query($dbname, $sqlstr);
		while ($row=mysql_fetch_row($erg)) {
			$myId = $row[0];
			$myTitle =  $row[1];
			$myContent = explode(",", $row[2]);
			
			echo "       <li>\n";
			if($cid == $myId && ($navItem == "projectlist" || $navItem == "project")) {
			
				if($pid && $navItem == "project")
					echo "         <div class='rub0'><a href='projectlist.php?cid=$myId'>&gt; $myTitle</a></div>\n";
				else
					echo "         <div class='rub0active'><a href='projectlist.php?cid=$myId'>&gt; $myTitle</a></div>\n";
				
				echo "           <ul>\n";
				for($i=0; $i<count($myContent); $i++) {	
					if($myContent[$i]) {
						$sqlstr2 = "SELECT title_intern,active FROM $projecttable WHERE id = " . $myContent[$i];
						$erg2 = mysql_db_query($dbname, $sqlstr2);
						$row2=mysql_fetch_row($erg2);
						$myTitle = $row2[0];
						$isActive = $row2[1];
						$myPid = $myContent[$i];
						echo "            <li>\n";
						if($pid == $myPid && $navItem == "project" && $isActive) 
							echo "             <div class='rub1active'><a href='project.php?cid=$myId&pid=$myPid'>$myTitle</a></div>\n";
						else if($isActive)
							echo "             <div class='rub1'><a href='project.php?cid=$myId&pid=$myPid'>$myTitle</a></div>\n";
						echo "            </li>\n";
						}
					}
					echo "           </ul>\n";
	
			} else {
				
				echo "         <div class='rub0'><a href='projectlist.php?cid=$myId'>&gt; $myTitle</a></div>\n";
					
			}
				
			echo "       </li>\n";
		}
				
		
}




	
?>