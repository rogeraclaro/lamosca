<?php

	require 'functions.php';

	// Database connection is now handled by phpincludes/database.php
	db_connect();

	// Get POST/GET variables (replaces register_globals)
	$ProjectNew = $_POST['ProjectNew'] ?? $_GET['ProjectNew'] ?? null;
	$ProjectChange = $_POST['ProjectChange'] ?? $_GET['ProjectChange'] ?? null;
	$ModOrder = $_POST['ModOrder'] ?? $_GET['ModOrder'] ?? null;
	$addModule = $_POST['addModule'] ?? $_GET['addModule'] ?? null;
	$pid = $_POST['pid'] ?? $_GET['pid'] ?? null;
	$cid = $_POST['cid'] ?? $_GET['cid'] ?? null;
	$active = $_POST['active'] ?? $_GET['active'] ?? null;
	$title = $_POST['title'] ?? '';
	$title_intern = $_POST['title_intern'] ?? '';
	$inturl = $_POST['inturl'] ?? '';
	$strModules = $_POST['strModules'] ?? '';
	$text_1 = $_POST['text_1'] ?? '';
	$text_2 = $_POST['text_2'] ?? '';
	$rss_date = $_POST['rss_date'] ?? '';
	$rss_time = $_POST['rss_time'] ?? '';
	$message = '';
	$hiddenFields = '';
	$inputButton = '';
	$activeChecked = '';
	$strModules = '';

	if($ProjectNew || $ProjectChange) {
		if(!$active)
			$active = 0;
		$inturl = cleanfilename($inturl);

		$rss_date = str_replace("/",".",$rss_date);
		$rss_date = strtotime($rss_date." ".$rss_time);

	}
	if($ProjectNew)
		{
			$sqlstr = "INSERT $projecttable VALUES (0, 
													$active, 
													'".prepTextDB($title)."', 
													'".prepTextDB($title_intern)."', 
													'".prepTextDB($inturl)."', 
													'$strModules', 
													'".prepTextDB($text_1)."', 
													'".prepTextDB($text_2)."', 
													".$rss_date.", 
													".time().")";
			$erg = mysql_db_query($dbname, $sqlstr);
			$num = mysql_affected_rows();
			$pid = mysql_insert_id();
			if($pid>0) {
				FtpMkdir("/web/img/projects", $pid);
				//makefolder("../img/projects/$pid");
				$message = "El projecte s´ha afegit";
			} else
				$message = "El projecte no s´ha afegit!";
		}		
	
	if($ProjectChange || $addModule==1)
		{
			$sqlstr = "UPDATE $projecttable SET title = '".prepTextDB($title)."', 
														title_intern = '".prepTextDB($title_intern)."', 
														inturl = '".prepTextDB($inturl)."', 
														active = '$active', 
														modules = '$strModules', 
														text_1 = '".prepTextDB($text_1)."', 
														text_2 = '".prepTextDB($text_2)."', 
														rss_date = ".$rss_date.", 
														modification_date = ".time()." 
														WHERE id = $pid";
			$erg = mysql_db_query($dbname, $sqlstr);
			$num = mysql_affected_rows();
			if($num>0)
				$message = "El projecte s´ha modificat!";
			else
				$message = "El projecte no s´ha modificat!";
		}
		

	if($ModOrder) {
 		$content_list = implode(";", $_POST['content_list']);
		$sqlstr = "UPDATE $projecttable SET modules = '$content_list' WHERE id = $ModOrder";
		$erg = mysql_db_query($dbname, $sqlstr);
	}

		
	if($pid) {
		$sqlstr = "SELECT id,title,inturl FROM $projecttable WHERE id = $pid";
		$erg = mysql_db_query($dbname, $sqlstr);
		$row=mysql_fetch_row($erg);
		if ($row) {
			$title = $row[1];
			$inturl = $row[2];
		}
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
		<title>lamosca.admin</title>
		<meta http-equiv="imagetoolbar" content="no" />
		<link href="css/base.css" rel="stylesheet" type="text/css" />
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<script type="text/javascript" src="whizzery/whizzywig.js"></script>
		<script src="../js/protoaculous.js" type="text/javascript"></script>
		<script src="../js/ajax.js" type="text/javascript"></script>
		<script src="../js/datepicker/datepicker.js" type="text/javascript"></script>
		<link href="../js/datepicker/datepicker.css" rel="stylesheet" type="text/css" />
	</head>

	<body>
	<div id="head"> 
  		<h1><span><a href="index.php">lamosca.admin</a></span></h1>
	</div>
	<div id="container">
    <div id='leftframe'>
	<?php
		if($pid == 1) {
			$pagetitle = "Editar &quot;$title&quot; <a href='javascript:preview(1,\"$inturl\");'>preview</a>";
		} else if($pid && !$cid) {
			$navItem = "allprojects";
			$pagetitle = "Editar projecte &quot;$title&quot; <a href='javascript:preview(0,\"$inturl\");'>preview</a>";
		} else if($cid) {
			$sqlstr = "SELECT inturl FROM $categorytable WHERE id = $cid";
			$erg = mysql_db_query($dbname, $sqlstr);
			$row=mysql_fetch_row($erg);
			$cinturl =  $row[0];

			$navItem = "project";
			$pagetitle = "Editar projecte &quot;$title&quot; <a href='javascript:preview(\"$cinturl\",\"$inturl\");'>preview</a>";
		} else {
			$navItem = "newproject";
			$pagetitle = "Afegir projecte";
		}
		require 'navigation.php';
	?>
    </div>
	
	<div id="centerframe"> 
	<h2><?php echo $pagetitle; if($message) echo "<div class='zusatz'>$message</div>"; ?></h2>
	<div class="kasten_inhalt_1"> 
	<?php				
					
		if($pid) {
			$sqlstr = "SELECT id,active,title,text_1,text_2,modules,title_intern,inturl,rss_date,modification_date FROM $projecttable WHERE id = $pid";
			$erg = mysql_db_query($dbname, $sqlstr);

			$row=mysql_fetch_array($erg);

			if ($row) {
				$pid = $row[0];
				if($row[1] == 1)
					$activeChecked = "checked";
				$title = stripslashes($row[2]);
				$title_intern = stripslashes($row[6]);
				$inturl = stripslashes($row[7]);
				$text_1 = stripslashes($row[3]);
				$text_2 = stripslashes($row[4]);
				if(!$row['rss_date'])
					$row['rss_date'] = $row['modification_date'];
				$rss_date = date("d/m/Y",$row['rss_date']);
				$rss_time = date("H:i",$row['rss_date']);
				$strModules = $row[5];
				$inputButton = "<input type='submit' name='ProjectChange' value='Guardar projecte' border='0' />\n";
				$hiddenFields = "<input type='hidden' name='cid' value='$cid' /><input type='hidden' name='pid' value='$pid' /><input type='hidden' name='addModule' value='0' />";
			}
		} else {
			$rss_date = date("d/m/Y");
			$rss_time = date("H:i");
			$activeChecked = "checked";
			$inputButton = "<input type='submit' name='ProjectNew' value='Guardar projecte' border='0' />\n";
			$hiddenFields = "<input type='hidden' name='allProjects' value='1' />";
		}
		
		echo "<br /><form action='project.php' method='post' name='ProjectForm' enctype='multipart/form-data' onsubmit='return verifyForm()'>\n";
		
		echo "$hiddenFields\n";

		echo "<label for='title_intern'>Títol intern</label><br />\n";
		echo "<input name=\"title_intern\" id=\"title_intern\" size=\"50\" value=\"$title_intern\" style=\"width:550px\" type=\"text\" onblur=\"if(document.ProjectForm.title.value=='') document.ProjectForm.title.value=this.value;\" /><br /><br />\n";

		echo "<label for=\"title\">Títol públic (desplegable)</label><br />\n";
		echo "<input name=\"title\" id=\"title\" size=\"50\" value=\"$title\" style=\"width:550px\" type=\"text\" /><br /><br />\n";

		
		if($pid==1) {
			// echo "<label for=\"inturl\">Url de la imatge d'inici</label><br />\n";
			echo "<label for=\"inturl\">Projecte destacat</label><br />\n";		
			echo project_select_listing($text_2)."<br /><br />\n";
			echo "<input name=\"inturl\" id=\"inturl\" value=\"$inturl\" type=\"hidden\" />\n";
		} else {
			echo "<label for=\"inturl\">Nom de url (lamosca.info/categoria/<b>projecte</b>.html)</label><br />\n";		
			echo "<input name=\"inturl\" id=\"inturl\" size=\"50\" value=\"$inturl\" style=\"width:550px\" type=\"text\" /><br /><br />\n";
		}
		

		echo "<label for='active'>Publicat </label> \n";
		echo "<input type='checkbox' name='active' value='1' $activeChecked><br /><br />\n";		

		echo "<label for='pub_date'>Data RSS </label> \n";
		echo "<input name=\"rss_date\" id=\"rss_date\" size=\"50\" value=\"$rss_date\" style=\"width:80px\" type=\"text\" /> ";
		echo "<input name=\"rss_time\" id=\"rss_time\" size=\"40\" value=\"$rss_time\" style=\"width:80px\" type=\"text\" /> (hh:mm)<br /><br />\n";

		echo "<script type=\"text/javascript\">\n";
		echo "var dpck_fieldname = new DatePicker({\n";
		echo "        relative:'rss_date',\n";
		echo "        language : 'es',\n";
		echo "        keepFieldEmpty:true,enableCloseOnBlur:false,\n";
		echo "        enableShowEffect:false,\n";
		echo "        enableCloseEffect:false\n";
		echo "});\n";
		echo "</script>\n";
		echo "</td>\n";

		echo "<label for='text_1'>Text (descripció - salt de linia - nom de client -- majuscules!)</label>\n";
		echo "<textarea id='text_1' name='text_1' cols='40' rows='3' value='true' style='width:200px'>$text_1</textarea>\n";
		echo "	<script type=\"text/javascript\">
				buttonPath = 'whizzery/buttons/';
				cssFile='whizzery/whizzery.css';
				makeWhizzyWig('text_1', 'link html');
			</script><br /><br />";

		if($pid==1) {
			echo "<input name=\"strModules\" id=\"strModules\" value=\"$strModules\" type=\"hidden\" />\n";
		} else if($pid) {
			echo "<label>Mòduls</label><br />\n";
			echo "<div id=\"modules\">\n";
			echo module_listing($strModules);
			echo "</div>\n";
		}
		
		echo "<br />$inputButton\n";

		echo "</form>\n";
		
		
		db_close();
				
	?>
	</div> 

	</div> 
	</div> 
	<script type="text/javascript"><!--



		function verifyForm() {
<?php
		if($pid != 1) {
?>		
		  if (document.ProjectForm.inturl.value == "") {
			alert("Por favor, rellena el campo en blanco!");
			document.ProjectForm.inturl.focus();
			return false;
		  }
<?php
		}
?>		
		  if (document.ProjectForm.title_intern.value == "") {
			alert("Por favor, rellena el campo en blanco!");
			document.ProjectForm.title_intern.focus();
			return false;
		  }
		}

		function preview(cid,pid) {
			
			if(cid == 1)
				url = "../index.php";	
			else
				url = "../"+cid+"/"+pid+".html";	
			m = window.open(url, 'Preview', 'width=1000,height=600,top=50,left=50,location=yes,menubar=yes,toolbar=yes,scrollbars=yes,resizable=yes')
			m.focus();
		}
		
		function openModule(myId) {
			
			var mWidth = 700;
			var mHeight = 500;
			var leftPos = (screen.width - mWidth) / 2;
			var topPos = (screen.height - mHeight) / 2;
		
			if(!myId || myId == "new") {
				m = window.open('module.php?pid=<?php echo $pid; ?>', 'Modul', 'width='+mWidth+',height='+mHeight+',top='+topPos+',left='+leftPos+',location=no,menubar=no,toolbar=no,scrollbars=yes,resizable=yes')
			} else {
				m = window.open('module.php?pid=<?php echo $pid; ?>&mid='+myId, 'Modul', 'width='+mWidth+',height='+mHeight+',top='+topPos+',left='+leftPos+',location=no,menubar=no,toolbar=no,scrollbars=yes,resizable=yes')
			}
			m.focus();
		}

		function deleteModule(mid){
			check = confirm('Segur?'); 
			if(check == true) {
				Effect.Fade("m_"+mid);
				setTimeout("delete_li("+mid+")",1000);
			}				
		}
		function delete_li(mid){
			Knoten = document.getElementById("m_"+mid);
			document.getElementById("content_list").removeChild(Knoten)
			updateOrder();
		}
		
		function addItem(newValue, newTitle) {
		
			myString = document.ProjectForm.strModules;
			myString.value = myString.value +";"+ newValue;
			
			document.ProjectForm.addModule.value = 1;
			document.ProjectForm.submit();
			
		}
 	
		function updateOrder() {
		 
			var options = {
							 method : 'post',
							 parameters : Sortable.serialize('content_list')
						   };
	
			new Ajax.Request('project.php?ModOrder=<?php echo $pid; ?>', options);
			
			mymods = Sortable.serialize('content_list');
			mymods = mymods.replace(/content_list\[\]=/g, '');
			mymods = mymods.replace(/&/g, ';');
			document.ProjectForm.strModules.value = mymods;

			 updateNavi(<?php echo "'$cid','$pid','$navItem'";?>);

		}

		Sortable.create('content_list', { handle:'handle', onUpdate: updateOrder });

		
		// -->
	</script>
	
	</body>

</html>	
	
