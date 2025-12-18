<?php

	error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);

	//error_reporting(0);

	setlocale(LC_CTYPE, "es_ES");

	// db connection
	$serv = 1;
	if($serv == 1) {
		$db_host="localhost"; 
		$db_user="mylamosca";
		$db_password="4XvEvhm1";
		$dbname="weblamosca";
		$imgroot = "/img/";
		$imgrootsrv = "/usr/home/lamosca.com/web/img/";
		$myServer = "http://www.lamosca.com/";
	} else {
		$db_host="localhost"; 
		$db_user="root";
		$db_password="";
		$dbname="weblamosca";
		$imgroot = "/~thomas/lamosca/img/";
		$imgrootsrv = "/Users/thomas/Sites/lamosca/img/";
		$myServer = "http://192.168.1.219/~thomas/lamosca/";
	}
	$categorytable="categories";
	$projecttable="projects";	
	$moduletable="modules";
	$mosaictable="mosaic";	


	function prepXml($text) {


    	$h2t = new html2text ($text);
    	$text = $h2t->get_text();
	
		$text = html_entity_decode($text);
		$text = str_replace("&","&amp;",$text);
		$text = str_replace("<","&lt;",$text);
		$text = str_replace(">","&gt;",$text);
		$text = str_replace("'","&apos;",$text);
		$text = str_replace("\"","&quot;",$text);
		$text = trim($text);
		//$text = nl2br($text);
		$text = str_replace("\n\n","<br /><br />\n",$text);
		$text = stripslashes($text);
		//$text = utf8_encode($text);
		
		return $text;
		
	}

	function buildModules($curl,$purl,$id=0) {

		global $categorytable, $projecttable, $moduletable, $dbname, $imgroot, $imgrootsrv, $flashJS, $rss_img, $rss_img_srv;

		// first we ensure, that we choose the project from the right category
		$sqlstr = "SELECT id,position,content FROM $categorytable WHERE inturl = '$curl'";
		$erg = mysql_db_query($dbname, $sqlstr);
		$row=mysql_fetch_row($erg);
		$catContent = explode(",", $row[2]);

		if($id) {
			$sqlstr = "SELECT id,modules,title FROM $projecttable WHERE id = '$id'";
		} else {
			if($purl=="index")
				$sqlstr = "SELECT id,modules FROM $projecttable WHERE id = '1'";
			else
				$sqlstr = "SELECT id,modules FROM $projecttable WHERE inturl = '$purl'";
		}
		$erg = mysql_db_query($dbname, $sqlstr);
		while($row=mysql_fetch_row($erg)) {
			$tempid = $row[0];
			$project_title = $row[2];
			for($i=0; $i<count($catContent); $i++) {	
				if($tempid == $catContent[$i] || $purl=="index") {
					$pid = $row[0];
					$modules = explode(";",$row[1]);
				}
			}
		}
		$imgList = "";
		
		$firstdrawn = false;

		for($i=0; $i<count($modules); $i++) {	
			
			if($modules[$i]) { // && !($purl=="index" && $firstdrawn) ) {
			
				$sqlstr = "SELECT id,title,image,imagetype,width,height,text_1,text_2,text_3,text_4,thumb,link FROM $moduletable WHERE id = " . $modules[$i];
				$erg = mysql_db_query($dbname, $sqlstr);
				$row=mysql_fetch_array($erg);
				$title = $row[1];
				$image = $row[2];
				$imagetype = $row[3];
				$imgWidth = $row[4];
				$imgHeight = $row[5];
				$text_1 = trim($row[6]);
				$text_2 = trim($row[7]);
				$text_3 = trim($row[8]);
				$text_4 = trim($row[9]);
				$thumb = $row['thumb'];
				$link = $row['link'];
				
				$imagepath = $imgroot."projects/".$pid."/";
				$imagepathsrv = $imgrootsrv."projects/".$pid."/";
				$imagepathflv = "../projects/".$pid."/";
				
				if($purl=="index" && $project_title && !$firstdrawn) {
					
					/*
					$home_title = $title;
					
					if($link)
						$home_title = "<a href='".$link."'>".$home_title."</a>";
						*/

					$imgList .= "     <div id='indexTitle_".$modules[$i]."' class='indexTitleText'>$project_title</div>\n";
					
					$flashJS .= "  var so = new SWFObject('".$imgroot."flash/indexTitle.swf', 'indexTitleFlash', '590', '12', '8', '#FFFFFF');\n";
					$flashJS .= "  so.addVariable('text_1', '".prepFlash(strtoupper($project_title))."');\n";
					$flashJS .= "  so.addParam('scale', 'noscale');\n";
					$flashJS .= "  so.write('indexTitle_".$modules[$i]."');\n\n";
					
				}

				if(file_exists($imagepathsrv.$image) || ($image && $imagetype == "slideshow")) {
				
					$firstdrawn = true;

					if($imagetype == "image") {
						
						$img_size = getimagesize($imagepathsrv.$image);
						$img_src = $imagepath.rawurlencode($image);
						// $img_src = "<img src='$img_src' width='".$img_size[0]."' height='".$img_size[1]."' alt='".prepHtml($title)."' border='0' />";
						$img_src = "<img src='$img_src' alt='".prepHtml($title)."' border='0' />";
						if($link) {
							$target = "";
							if($purl!="index")
								$target = "target='_blank'";
							$img_src = "<a href='".$link."' ".$target.">".$img_src."</a>";
						}
						$imgList .= "<p>".$img_src."</p>";
						if(!$rss_img) {
							$rss_img = $imagepath.rawurlencode($image);
							$rss_img_srv = $imagepathsrv.rawurlencode($image);
						}
							
					} else if ($imagetype == "flashvideo") {
						
						$img_src = $imagepathflv.rawurlencode($image);
						$img_src = $imagepath.rawurlencode($image);
						/*
						$imgList .= "   <div id='flashvideo_".$modules[$i]."' class='flashModule'>\n";
						$imgList .= "   </div>\n";
						*/
						$imgList .= "   <div class='flashModule'>\n";
						$imgList .= "   <video \n";
						$imgList .= "       src=\"DFS_450kbs1.mp4\" \n";
						$imgList .= "       height=\"".$imgHeight."\" \n";
						$imgList .= "       id=\"flashvideo_".$modules[$i]."\" \n";
						$imgList .= "       width=\"".$imgWidth."\" \n";
						$imgList .= "       >\n";
						$imgList .= "   </video>\n";
						$imgList .= "   </div>\n";

						$flashJS .= "  jwplayer(\"flashvideo_".$modules[$i]."\").setup({\n";
						$flashJS .= "  	file: \"".$img_src."\",\n";
						if(file_exists($imagepathsrv.$thumb) && $thumb) {
							$thumb_src = $imagepath.rawurlencode($thumb);
							$flashJS .= "  	image: \"".$thumb_src."\",\n";
							if(!$rss_img) {
								$rss_img = $imagepath.rawurlencode($thumb);
								$rss_img_srv = $imagepathsrv.rawurlencode($thumb);
							}
						}
						$flashJS .= "  	flashplayer: \"".$imgroot."flash/player.swf\",\n";
						$flashJS .= "  	skin: \"".$imgroot."flash/lamosca_skin.swf\",\n";
						$flashJS .= "  	icons: false,\n";
						$flashJS .= "  	showicons: false,\n";
						$flashJS .= "  	volume: 80,\n";
						$flashJS .= "  	stretching: \"fill\",\n";
						$flashJS .= "  	controlbar: \"over\"\n";
						$flashJS .= "  	});\n";
					
						/*
						$flashJS .= "  var so = new SWFObject('".$imgroot."flash/flvPlayer.swf', 'flashModule_".$modules[$i]."', '$imgWidth', '$imgHeight', '8', '#FFFFFF');\n";
						$flashJS .= "  so.addVariable('video', '$img_src');\n";
						$flashJS .= "  so.addParam('scale', 'noscale');\n";
						$flashJS .= "  so.write('flashvideo_".$modules[$i]."');\n\n";
						*/
						
					} else if ($imagetype == "slideshow") {
						$img_src = $imagepathflv.rawurlencode($image);
						$imgList .= "   <div id='slideshow_".$modules[$i]."' class='flashModule'>\n";
						$imgList .= "   </div>\n";
					
						$flashJS .= "  var so = new SWFObject('".$imgroot."flash/slideshow.swf', 'flashModule_".$modules[$i]."', '$imgWidth', '$imgHeight', '8', '#FFFFFF');\n";
						$flashJS .= "  so.addVariable('pid', '$image');\n";
						$flashJS .= "  so.addVariable('swidth', '$imgWidth');\n";
						$flashJS .= "  so.addVariable('sheight', '$imgHeight');\n";
						$flashJS .= "  so.addParam('scale', 'noscale');\n";
						$flashJS .= "  so.write('slideshow_".$modules[$i]."');\n\n";
					} else {
						$img_src = $imagepath.rawurlencode($image);
						$imgList .= "   <div id='flash_".$modules[$i]."' class='flashModule'>\n";
						$imgList .= "   </div>\n";
					
						$flashJS .= "  var so = new SWFObject('$img_src', 'flashModule_".$modules[$i]."', '$imgWidth', '$imgHeight', '8', '#FFFFFF');\n";
						$flashJS .= "  so.addParam('scale', 'noscale');\n";
						$flashJS .= "  so.write('flash_".$modules[$i]."');\n\n";
					}

					if($text_1 || $text_2 || $text_3 || $text_4) {
						$imgList .= "   <div id='text_".$modules[$i]."' class='textModule'>\n";
						$imgList .= "    <div class='pageInfo'>".prepHtml($text_1)."</div>\n";
						$imgList .= "    <div class='pageInfo'>".prepHtml($text_2)."</div>\n";
						$imgList .= "    <div class='pageInfo'>".prepHtml($text_3)."</div>\n";
						$imgList .= "    <div class='pageInfo'>".prepHtml($text_4)."</div>\n";
						$imgList .= "    <div class='break'>&nbsp;</div>\n";
						$imgList .= "   </div>\n";
						
						$flashJS .= "  var so = new SWFObject('".$imgroot."flash/moduleText.swf', 'textModule_".$modules[$i]."', '590', '40', '8', '#FFFFFF');\n";
						$flashJS .= "  so.addVariable('text_1', '".prepFlash($text_1)."');\n";
						$flashJS .= "  so.addVariable('text_2', '".prepFlash($text_2)."');\n";
						$flashJS .= "  so.addVariable('text_3', '".prepFlash($text_3)."');\n";
						$flashJS .= "  so.addVariable('text_4', '".prepFlash($text_4)."');\n";
						$flashJS .= "  so.addParam('scale', 'noscale');\n";
						$flashJS .= "  so.write('text_".$modules[$i]."');\n\n";
					}
					
				}
			}
		}
		
		return "$imgList <br /><br />\n";
	}

	function buildIndex() {
	
	   global $projecttable, $categorytable, $moduletable, $dbname, $imgroot, $imgrootsrv, $flashJS, $htmlnav;
	   
		// first we ensure, that we choose the project from the right category
		$sqlstr = "SELECT id,position,content FROM $categorytable WHERE inturl = '$curl'";
		$erg = mysql_db_query($dbname, $sqlstr);
		$row=mysql_fetch_row($erg);
		$catContent = explode(",", $row[2]);

		$sqlstr = "SELECT id,modules,inturl FROM $projecttable WHERE id = 1";
		$erg = mysql_db_query($dbname, $sqlstr);
		$row=mysql_fetch_row($erg);
		$pid = 1;
		$modules = explode(";",$row[1]);
		$startimglink = $row[2];
		$imgList = "";
		
		if($modules[0]) {
			$modid = $modules[0];
		} else if ($modules[1]) {
			$modid = $modules[1];
		}
		
		if($modid) {
		
			$sqlstr = "SELECT id,title,image,imagetype,width,height,text_1,text_2,text_3,text_4 FROM $moduletable WHERE id = " . $modid;
			$erg = mysql_db_query($dbname, $sqlstr);
			$row=mysql_fetch_row($erg);
			$title = $row[1];
			$image = $row[2];
			$imagetype = $row[3];
			$imgWidth = $row[4];
			$imgHeight = $row[5];
			$text_1 = trim($row[6]);
			$text_2 = trim($row[7]);
			$text_3 = trim($row[8]);
			$text_4 = trim($row[9]);

			$imagepath = $imgroot."projects/1/";
			$imagepathsrv = $imgrootsrv."projects/1/";
			$imagepathflv = "../projects/1/";
			
			if(file_exists($imagepathsrv.$image)) {

				if($text_1) {
					
					$imgList .= "     <div id='indexTitle' class='indexTitleText'>$text_1</div>\n";
					
					$flashJS .= "  var so = new SWFObject('".$imgroot."flash/indexTitle.swf', 'indexTitleFlash', '590', '12', '8', '#FFFFFF');\n";
					$flashJS .= "  so.addVariable('text_1', '".prepFlash($text_1)."');\n";
					$flashJS .= "  so.addParam('scale', 'noscale');\n";
					$flashJS .= "  so.write('indexTitle');\n\n";
					
				}

				if($imagetype == "image") {
					$img_size = getimagesize($imagepathsrv.$image);
					$img_src = $imagepath.rawurlencode($image);
					if($startimglink)
						$img_src = "   <a href='$startimglink'><img src='$img_src' width='".$img_size[0]."' height='".$img_size[1]."' alt='".prepHtml($title)."' border='0' /></a>\n";
					else
						$img_src = "   <img src='$img_src' width='".$img_size[0]."' height='".$img_size[1]."' alt='".prepHtml($title)."' border='0' />\n";
					$imgList .= $img_src;
				} else if ($imagetype == "flashvideo") {
					$img_src = $imagepathflv.rawurlencode($image);
					$imgList .= "   <div id='flashvideo_".$modules[$i]."' class='flashModule'>\n";
					$imgList .= "   </div>\n";
				
					$flashJS .= "  var so = new SWFObject('".$imgroot."flash/flvPlayer.swf', 'flashModule_".$modules[$i]."', '$imgWidth', '$imgHeight', '8', '#FFFFFF');\n";
					$flashJS .= "  so.addVariable('video', '$img_src');\n";
					$flashJS .= "  so.addParam('scale', 'noscale');\n";
					$flashJS .= "  so.write('flashvideo_".$modules[$i]."');\n\n";
				} else if ($imagetype == "slideshow") {
					$img_src = $imagepathflv.rawurlencode($image);
					$imgList .= "   <div id='slideshow_".$modules[$i]."' class='flashModule'>\n";
					$imgList .= "   </div>\n";
				
					$flashJS .= "  var so = new SWFObject('".$imgroot."flash/slideshow.swf', 'flashModule_".$modules[$i]."', '$imgWidth', '$imgHeight', '8', '#FFFFFF');\n";
					$flashJS .= "  so.addVariable('pid', '$img_src');\n";
					$flashJS .= "  so.addVariable('swidth', '$imgWidth');\n";
					$flashJS .= "  so.addVariable('sheight', '$imgHeight');\n";
					$flashJS .= "  so.addParam('scale', 'noscale');\n";
					$flashJS .= "  so.write('slideshow_".$modules[$i]."');\n\n";
				} else {
					$img_src = $imagepath.rawurlencode($image);
					$imgList .= "   <div id='flash_".$modules[$i]."' class='flashModule'>\n";
					$imgList .= "   </div>\n";
				
					$flashJS .= "  var so = new SWFObject('$img_src', 'flashModule_".$modules[$i]."', '$imgWidth', '$imgHeight', '8', '#FFFFFF');\n";
					$flashJS .= "  so.addParam('scale', 'noscale');\n";
					$flashJS .= "  so.write('flash_".$modules[$i]."');\n\n";
				}

			}

			$catIndex = "   <div id='indexModule'>\n";
			$catIndex = "   </div>\n\n";
			$catIndex = $imgList;
		}


		/*
	   $catIndex .= "   <div id='catModule'>\n";
	   $counter = 1;
	   
	   $sqlstr = "SELECT id,title,content,inturl FROM $categorytable ORDER BY position";
	   $erg = mysql_db_query($dbname, $sqlstr);
	   
	   while ($row=mysql_fetch_row($erg)) {

		   $myCid = $row[0];
		   $cinturl = $row[3];
	   	   if($counter<10)
	   	      $counter = "0".$counter;
		   $title = $counter . "." . strtoupper($row[1]);
		   $titleHtml = stripslashes($title);

		   if($myCid) {
			   $catIndex .= "\n    <div class='catTitle'>\n";
			   $catIndex .= "     <div id='cat_".$myCid."' class='catTitleText'>$titleHtml</div>\n";
			   
			   // dropdown menu
			   $content = explode(",", $row[2]);
			   $projects = "       <option value=''>+</option>\n";
			   
			   for($i=0; $i<count($content); $i++) {	
				   if($content[$i]) {
					   $sqlstr2 = "SELECT id,title,active,inturl FROM $projecttable WHERE id = " . $content[$i];
					   $erg2 = mysql_db_query($dbname, $sqlstr2);
					   $row2=mysql_fetch_row($erg2);
					   $myPid = $row2[0];
					   $prodtitle = prepHtml($row2[1]);
					   $active = $row2[2];
					   $pinturl = $row2[3];
					   if($active == 1 && $pinturl) {
						   $projects .= "       <option value='$pinturl'>$prodtitle</option>\n";
					   }
				   }
			   }
	
			   $catDrop = "     <div class='catTitleDrop'>\n";
			   $catDrop .= "      <select name='catTitleDrop_$myCid' onchange='indexnavigate(\"$cinturl\",this);' class='js_index'>\n";
			   $catDrop .= $projects;
			   $catDrop .= "      </select>\n";
			   $catDrop .= "      </div>\n";
			   
			   $catIndex .= $catDrop;

			   $flashJS .= "  var so = new SWFObject('".$imgroot."flash/catTitle.swf', 'catModule_".$myCid."', '250', '11', '8', '#FFFFFF');\n";
			   $flashJS .= "  so.addVariable('text_1', '".prepFlash($title)."');\n";
			   $flashJS .= "  so.addParam('scale', 'noscale');\n";
			   $flashJS .= "  so.write('cat_".$myCid."');\n\n";

			   $catIndex .= "    </div>\n";

			   $counter++;
		   }

	   }
	
	   $catIndex .= "   <div class='break'>&nbsp;</div>\n";
	   $catIndex .= "   </div>\n";

	   $catIndex .= "   <noscript>\n";
	   $catIndex .= "    <div class='break'>&nbsp;</div>\n";
	   $catIndex .= "    <div id='htmlnav'>\n";
	   $catIndex .= "$htmlnav";
	   $catIndex .= "    </div>\n";
	   $catIndex .= "    <div class='break'>&nbsp;</div>\n";
	   $catIndex .= "   </noscript>\n";
	   	*/
	   	
	   return $catIndex;
	}
		
	function prepHtml ($text) {
		
		$text = str_replace("<br>","<br />",$text);
		$text = stripslashes($text);
		$text = trim($text);
		$text = str_replace("&","&amp;",$text);
		$text = str_replace("&&amp;","&amp;",$text);
		$text = str_replace("&amp;lt;","&lt;",$text);
		$text = str_replace("&amp;gt;","&gt;",$text);

		//$text = htmlentities($text);
		$text = nl2br($text);
		//$text = str_replace("&lt;","<",$text);
		//$text = str_replace("&gt;",">",$text);

		return $text;

	}
		
	function prepFlash($text) {
		$text = stripslashes($text);
		$text = trim($text);
		//$text = nl2br($text);
		$text = str_replace(chr(10), "", $text);
		$text = str_replace(chr(13), "", $text);
		$text = str_replace(chr(133), "...", $text);
		$text = str_replace(chr(146), chr(39), $text);
		$text = str_replace(chr(147), chr(34), $text);
		$text = str_replace(chr(148), chr(34), $text);
		$text = str_replace("<br />", "<br>", $text);
		$text = urlencode($text);
		$text = str_replace("%C3%22", "Ó", $text);
		$text = str_replace("%C3%27", "Ò", $text);
		return $text;
	}
	
?>