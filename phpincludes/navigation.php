<?php

$sqlstr = "SELECT id,title,inturl,content FROM $categorytable ORDER BY position";
$erg = mysql_db_query($dbname, $sqlstr);
$htmlnav = "    <ul>\n";
while ($row = mysql_fetch_row($erg)) {
	$myCid = $row[0];
	$title = prepHtml($row[1]);
	$cinturl = $row[2];
	$content = $row[3];

	if ($cinturl) {

		$categories .= "    <option value='$cinturl'";
		if ($cinturl == $curl)
			$categories .= ' selected';
		$categories .= ">..$title</option>\n";
		$htmlnav .= "     <li><a href='" . $myServer . $cinturl . "/'>$title</a>\n"; //</li>\n";

		if ($content) {

			$content = explode(",", $content);

			$htmlnav .= "      <ul class='subnavi'>\n";
			for ($i = 0; $i < count($content); $i++) {
				if ($content[$i]) {
					$sqlstr2 = "SELECT id,title,active,inturl FROM $projecttable WHERE id = " . $content[$i];
					$erg2 = mysql_db_query($dbname, $sqlstr2);
					$row2 = mysql_fetch_row($erg2);
					$myPid = $row2[0];
					$title = prepHtml($row2[1]);
					$pinturl = $row2[3];
					$active = $row2[2];
					if ($active == 1 && $pinturl) {
						if ($cinturl == $curl) {
							$projects .= "    <option value='$pinturl'";
							if ($pinturl == $purl)
								$projects .= ' selected=\"selected\"';
							$projects .= ">$title</option>\n";
						}
						$htmlnav .= "       <li><a href='" . $myServer . $cinturl . "/" . $pinturl . ".html'>$title</a></li>\n";
					}
				}
			}
			$htmlnav .= "      </ul>\n";
			$htmlnav .= "     </li>\n";
		}
	}
}
$htmlnav .= "    </ul>\n";

if ($curl && !$pagenotfound) {
	$subnav = "  <div id='js_nav_2'>\n";
	$subnav .= "   <select name='js_project' onchange='navigate(this);' class='js_nav' id='js_project'>\n";
	$subnav .= $projects;
	$subnav .= "   </select>\n";
	$subnav .= "  </div>\n\n";
}

/*
	if($cid) {
	
		$sqlstr = "SELECT id,content FROM $categorytable WHERE id = '$cid'";
		$erg = mysql_db_query($dbname, $sqlstr);
		$row=mysql_fetch_row($erg);
		$content = $row[1];

		if($content) {

			$content = explode(",", $content);
			
			for($i=0; $i<count($content); $i++) {	
				if($content[$i]) {
					$sqlstr = "SELECT id,title,active,inturl FROM $projecttable WHERE id = " . $content[$i];
					$erg = mysql_db_query($dbname, $sqlstr);
					$row=mysql_fetch_row($erg);
					$myPid = $row[0];
					$title = prepHtml($row[1]);
					$pinturl = $row[3];
					$active = $row[2];
					if($active == 1 && $pinturl) {
						$projects .= "    <option value='$myPid'";
						if($pid == $myPid) 
							$projects .= ' selected';
						$projects .= ">$title</option>\n";
						$htmlnav .= "     <li><a href='".$myServer.$cid."/".$myPid."/'>$title</a></li>\n";
					}
				}
			}

			$subnav = "  <div id='js_nav_2'>\n";
			$subnav .= "   <select name='js_project' onChange='navigate(this);' class='js_nav' id='js_project'>\n";
			$subnav .= $projects;
			$subnav .= "   </select>\n";
			$subnav .= "  </div>\n\n";
		}
	}
	*/

?> <div id="navigation">
	<form name="navForm" action="#">
		<div id='js_nav_1'>
			<select name="js_cat" onchange="navigate(this);" class="js_nav">
				<option value="index" <?php if ($_SERVER['REQUEST_URI'] == '/') echo " selected=\"selected\""; ?>>Portfolio</option>
				<?php
				echo $categories;
				?>
				<!-- <option value="info">Visualization</option> -->
				<option value="mosaic" <?php if ($pageTitle == 'Mosaic') echo " selected=\"selected\""; ?>>Mosaic</option>
				<!--   <option value="tshirt">T.shirts</option> -->
			</select>
		</div>
		<?php
		echo $subnav;
		?>
	</form>
</div>