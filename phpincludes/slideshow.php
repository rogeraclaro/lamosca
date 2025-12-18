<?php

	require 'functions.php';

	// Get GET variable (replaces register_globals)
	$pid = $_GET['pid'] ?? null;

	if($pid) {
		$pid = (int)$pid; // Ensure it's an integer for security
		db_connect();

		echo " <slideshow>\n";
		echo "  <settings>\n";
		echo "    <image_folder>".$imgroot."projects/$pid/</image_folder>\n";
		echo "    <time>1</time>\n";
		echo "    <fade>0.5</fade>\n";
		echo "    <repeat>true</repeat>\n";
		echo "    <captions>true</captions>\n";
		echo "  </settings>\n\n";
		echo "  <images>\n";
	
	
		$sqlstr = "SELECT id,modules FROM $projecttable WHERE id = $pid";
		$erg = db_query($dbname, $sqlstr);
		$row = db_fetch_row($erg);
		$pid = $row[0];
		$modules = explode(";",$row[1]);
		
		for($i=0; $i<count($modules); $i++) {	
			if($modules[$i]) {
			
				$sqlstr = "SELECT id,title,image,imagetype,width,height,text_1,text_2,text_3,text_4 FROM $moduletable WHERE id = " . $modules[$i];
				$erg = db_query($dbname, $sqlstr);
				$row = db_fetch_row($erg);
				$title = $row[1];
				$image = $row[2];
				$imgWidth = $row[4];
				$imgHeight = $row[5];
				
				$imagepath = $imgroot."projects/".$pid."/";
				$imagepathsrv = $imgrootsrv."projects/".$pid."/";
				
				//echo "--$imagepathsrv.$image--";
				
				if(file_exists($imagepathsrv.$image) && stristr($image, ".jpg")) {
	
					echo "   <image>\n";
					echo "    <file>".$image."</file>\n";
					echo "    <caption>\n";
					echo "     <![CDATA[".prepFlash($title)."]]>\n";
					echo "    </caption>\n";
					echo "   </image>\n\n";
					
				}
			}
		}

		echo "  </images>\n";
		echo " </slideshow>\n";
	
		db_close();

	}

?>