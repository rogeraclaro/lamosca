	<?php

	setlocale(LC_CTYPE, "es_ES");
	error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);


	// db connection
	$serv = 1;
	if($serv == 1) {
		$db_host="localhost"; 
		$db_user="mylamosca";
		$db_password="4XvEvhm1"; //";
		$dbname="weblamosca";
	} else {
		$db_host="localhost"; 
		$db_user="root";
		$db_password="";
		$dbname="weblamosca";
	}
	$categorytable="categories";
	$projecttable="projects";	
	$moduletable="modules";
	$mosaictable="mosaic";	

	

	// used in module.php
	function cleanfilename($filename) {
		
		//$filename = strtolower($filename);
		$filename = preg_replace('/[&%!$´ éúóáíèùòàìöüäß]/', '_', $filename);
		
		return $filename;
	}


	// used in project.php
	function module_listing($strCurrent) {
	
		global $dbname, $moduletable;
		 
		if($strCurrent) {
			$Current = explode(";", $strCurrent);
			for($i=0; $i < count($Current); $i++) {			
				if($Current[$i]) {
					$sqlstr = "SELECT id,title_intern FROM $moduletable WHERE id = ".$Current[$i];
					$erg = mysql_db_query($dbname, $sqlstr);
					$row=mysql_fetch_row($erg);
					if($row) {
						$mid = $row[0];
						$title_intern = $row[1];
						$CurrentOptions .= "<option value='" . $Current[$i] . "'>" . $row[1] . "</option>";
						$modules .= "   <li id=\"m_".$mid."\">\n";
			
						$modules .= "     <div class=\"handle\">moure</div>\n";
						$modules .= "     <div class=\"action\"><a href=\"javascript:openModule('$mid');\">modificar</a></div>\n";
						$modules .= "     <div class=\"action\"><a href=\"javascript:deleteModule('$mid');\">esborrar</a></div>\n";
						$modules .= "     <div class=\"opener\"><b>$title_intern</b></div>\n";
						$modules .= "     <div class=\"clear\"></div>\n";
			
						$modules .= "   </li>\n";
					}
				}
			}
			$Current = $CurrentOptions;
		}

		$modlist = "  <div class=\"listing\">\n";
		$modlist .= "  <div class=\"action\" style=\"float:left; margin: 5px 0 0 10px;\"><a href=\"javascript:openModule('new');\">afegir</a></div>\n";
		$modlist .= "  <div class=\"clear\"></div>\n";
		$modlist .= "  <input type='hidden' name='strModules' value='$strCurrent' />\n";
		$modlist .= "  <ul id=\"content_list\">\n";
		
		$modlist .= $modules;

		$modlist .= "  </ul>\n";
		$modlist .= "  </div>\n";

		return($modlist);
		
	}

	function project_select_listing($id) {
	
		global $dbname, $projecttable, $content, $cid;
					
		$categories = "<select name='text_2' style='width:350px'>\n";

		$sqlstr = "SELECT id,title,title_intern FROM $projecttable WHERE id > 1 ORDER BY modification_date DESC";
		$erg = mysql_query($sqlstr);
		
		$categories .= "<option value=''>---</option>\n";
		
		while ($row=mysql_fetch_row($erg)) {
			$pId = $row[0];
			$pTitle = $row[2];
			
			$selected = "";
			if($pId == $id)
				$selected = "selected=\"selected\"";

			$categories .= "<option value='$pId'$selected>$pTitle</option>\n";
		
		}
		
		$categories .= "</select>\n";
		
		return $categories;
					
	}
	

	// used in projectlist.php

	function project_listing() {
		
		global $dbname, $projecttable, $content, $cid;
		
		$sqlstr = "SELECT id,title_intern,active FROM $projecttable WHERE id > 1 ORDER BY title_intern";
		$erg = mysql_db_query($dbname, $sqlstr);
	
		$myProducts = ",".implode(",", $content).",";

		while ($row=mysql_fetch_row($erg)) {
			$pid = $row[0];
			$title = stripslashes($row[1]);
			if($row[2] == 1)
				$activeChecked = "checked";
			else
				$activeChecked = "";
			if(!stristr($myProducts, ",".$pid.",")) {
			
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

	}


	function slideshow_listing($current) {
		
		global $dbname, $projecttable, $content;
		
		$sqlstr = "SELECT id,title_intern FROM $projecttable WHERE active = 0 ORDER BY id DESC, title_intern";
		$erg = mysql_db_query($dbname, $sqlstr);
	
		$projects = "<select name='showPid' style='width:350px'>\n";
		$projects .=  "<option value=''>--- slideshow ---</option>\n";
		

		while ($row=mysql_fetch_row($erg)) {
			$pid = $row[0];
			$title = stripslashes($row[1]);
			if($current == $pid)
				$selected = " selected=\"selected\"";
			else
				$selected = "";
				
			$projects .= "<option value='$pid'$selected>$pid - $title</option>\n";
		
		} 

		$projects .= "</select>\n";
		
		return $projects;
		
	}

	// used in projectlist.php
	function cleanProject($cid) {
		
		global $dbname,$categorytable,$projecttable;

		$sqlstr = "SELECT content FROM $categorytable WHERE id = '$cid'";
		$erg = mysql_db_query($dbname, $sqlstr);
		$row=mysql_fetch_row($erg);
		$content = explode(",", $row[0]);

		for($i=0; $i<count($content); $i++) {
			$sqlstr = "SELECT id FROM $projecttable WHERE id = '$content[$i]'";
			$erg = mysql_db_query($dbname, $sqlstr);
			$rows=mysql_num_rows($erg);
			if($rows<1) {
				array_splice($content, $i, 1);
			}
		}
		$content = implode(",", $content);
		$sqlstr = "UPDATE $categorytable SET content = '$content' WHERE id = $cid";
		$erg = mysql_db_query($dbname, $sqlstr);
			
	}


	
	// used in projectlist.php
	function deleteProject($pid) {
		
		global $content;
		
		$content = explode(",", $content);

		for($i=0; $i<count($content); $i++) {	
			if(!$content[$i] || $pid == $content[$i]) {
				array_splice($content, $i, 1);
			}
		}
		$content = implode(",", $content);
		
	}


	// used in project.php
	function makefolder($folder) {
		if(!file_exists($folder)){
			umask(0);
			mkdir($folder,0777);
		}
	}

	function FtpMkdir($path, $newDir) { 
	   
		$ftpserver='www.lamosca.com'; 
		$ftpuser = "lamosca"; 
		$ftppass = "u3l5t1r1"; //da3e2uma"; 
		
		$connection = ftp_connect($ftpserver); // connection 
	
		   // login to ftp server 
		   $result = ftp_login($connection, $ftpuser, $ftppass); 
	
		// check if connection was made 
		  if ((!$connection) || (!$result)) { 
			return false; 
			exit(); 
		   } else { 
			  ftp_chdir($connection, $path); // go to destination dir 
			if(ftp_mkdir($connection,$newDir)) { // create directory 
				ftp_chmod($connection, 0777, $newDir);
				return $newDir; 
			} else { 
				return false;        
			} 
		ftp_close($connection); // close connection 
		} 
	} 
	
	function deletefolder($dirname) {

		if (!file_exists($dirname)) {
			return false;
		}
		// Simple delete for a file
		if (is_file($dirname)) {
			return unlink($dirname);
		}
		// Loop through the folder
		$dir = dir($dirname);
		while (false !== $entry = $dir->read()) {
			// Skip pointers
			if ($entry == "." || $entry == "..") {
				continue;
			}
			// Recurse
			deletefolder("$dirname/$entry");
		}	
		// Clean up
		$dir->close();
		return rmdir($dirname);
	}
	
	
	
	
	function arrayformat($data) {
		$data = explode(chr(10), $data);
		for($i=0; $i<count($data);$i++) {
			$output .= "<b>" . prephtml($data[$i]) . " </b>";
			$i++;
			$output .= prephtml($data[$i]);
		}
		return $output;
	}
	
	
	
	function prephtml($text) {
		$text = trim($text);
		$text = stripslashes($text);
		//$text = htmlentities($text);
		$text = nl2br($text);
		return $text;
	}
	
	function prepFlash($text) {
		$text = trim($text);
		$text = stripslashes($text);
		$text = nl2br($text);
		$text = str_replace(chr(10), "", $text);
		$text = str_replace(chr(13), "", $text);
		$text = str_replace(chr(133), "...", $text);
		$text = str_replace(chr(146), chr(39), $text);
		$text = str_replace(chr(147), chr(34), $text);
		$text = str_replace(chr(148), chr(34), $text);
		$text = str_replace("<br />", "<br>", $text);
		$text = urlencode($text);
		return $text;
	}
	
	function unhtmlentities($string) 
	 {
		$trans_tbl = get_html_translation_table(HTML_ENTITIES);
		$trans_tbl = array_flip($trans_tbl);
		return strtr($string, $trans_tbl);
	 }
	
	function prepTextDB($text) {

		$text = stripslashes($text);
		$text = str_replace('<div style="border: 1px dashed grey; padding: 2px; position: absolute; display: inline; visibility: hidden; font-family: arial,sans-serif; font-size: 9px;"><img style="display: inline;" src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAA8AAAAWCAYAAAAfD8YZAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAgY0hSTQAAeiYAAICEAAD6AAAAgOgAAHUwAADqYAAAOpgAABdwnLpRPAAAABd0RVh0U29mdHdhcmUAUGFpbnQuTkVUIHYyLjW8h8n+AAADKUlEQVQ4T5WT/U+aVxTHm/pb/4Jlf8L+if05S5Z0WbqtWbPauFotrR1aRVQQEORNivUFtYoFBSpqi0pB8KXV4gsyK4qKqIDw2X1YIjbYJTvJfe597j2fc77n3Oe5ceMaA74X46rdvM6vYq9YLJJOn95NHaX5e3efvWRq4fw8R1VV1bf/GUACUwdHGA02/qx+RFurCr3OjM0+RCi8TD5/wbUBJDC6tEZdfSNtSu3I9vbOd9JePp/Hanph+O33WoILYaS9LwJIxWUyZww5XqHTWUoOkklOYroprd0uL+rOLpLJg8uzUhDpLbaxg7Klg/39VIU0cfxNLpdHJmsiEAhVwlP+eYf8WQvn2ey1dUmEUqkrmMy9lfBrt9/R1KQgm8t9FTZ09xbaVfpKeOSVN9yqUCHJ+8rdYzC+LDxvVn8JSw3yemboUKrICtkX+dytYuHiclAs3JJ8jEYbjY2KzGXHpTAhj2X9w1Q3u5EBjj+OkYnPkD2MkE0GySRmOY5Pk1hxEvXb8Pa3YFHe/ze79Fj0mteXfF0kQnbSa6OcbnjI7wU42/aRWh1lJ/iC6KSaqb6/GNbX0t34axkOe4zrK2/0wsnG8YcRMpsesiLjqZiTEQdbAQthVzteewNDuhr08l/KcNDdtRWZ1LA9Z+FwdZj0JzdncX9p/hzpJ/bWxPtxJZO2Jzi0NXTJ71yBXRpK8LyFg2WHgFycJ6ZF5kk+L/ay+c5McEwhYJmAH6Br+LkML7zWsOTVEhfw4aqDk9iEkD0t5E+wJ5q48c5E0NmKu6eeAc0DtFfhOWcHSz4dO+97RM3DnG2JmndnhHQfe9FBNgMis1OJ2/pYwNVonl7JHBhVsuzTEA9aOVgZJB0TNYvMJyLz7mIfsVkDC0K2y1JHf2c1nU9+Kst+O9JCxKNma95MSoJFo0qyRZBEuJdPs93MjzUzbq6lT30ftex2GZ4alBOa6GBzzkQy2sfR2riQ/oaTjQmhxs5Hv5YZ4TNmfIi9/R5tj34sw/26+rxD/5hRkwyXTY6r5zlOayvjPc0MaOt4qarBqvgD7dM7KB7epuHeD+Xvu7T6nyb9PP8AgwuHCQc/gYgAAAAASUVORK5CYII="></div>','',$text);
		$text = addslashes($text);
		return $text;
		
	}
	

	// returns directory listing
	function ls_a($wh)
	{
		$files_array = array();
		if ($handle = opendir($wh)) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != ".." ) {
					array_push($files_array, $file);
				}
			}
			closedir($handle);
		}
		return $files_array;
	} 
	
?>