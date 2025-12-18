<?php

	require 'phpincludes/functions.php';
	$db = mysql_connect($db_host, $db_user, $db_password) or die("Abort: Connection to '$db_host' not possible.");

	$flashJS = "";
	
	//echo "curl: $curl, pulr: $purl";
	
	if($curl) {
		$sqlstr = "SELECT id,position,content FROM $categorytable WHERE inturl = '$curl'";
		$erg = mysql_db_query($dbname, $sqlstr);
		$row=mysql_fetch_row($erg);

		$cid = $row[0];
		$catPos = $row[1];
		$content = explode(",", $row[2]);
		$proPos = 0;
		$pageInfo = "";
		$content_rev = array_reverse($content);
		
		for($i=0; $i<count($content_rev); $i++) {	
			if($content_rev[$i]) {
				$sqlstr = "SELECT 
							id,title,active,text_1,inturl
							FROM $projecttable 
							WHERE id = " . $content_rev[$i];
				$erg = mysql_db_query($dbname, $sqlstr);
				$row=mysql_fetch_row($erg);
				$active = $row[2];
				if($active) {
					$myPid = $row[0];
					$myPurl = $row[4];
					$myTitleTemp = $row[1];
					if($active && $myPurl)
						$proPosTemp++;
					$proInfo = $row[3];
					if($purl == $myPurl) {
						$proPos = $proPosTemp;
						$pageInfo = $proInfo;
						$myTitle = $myTitleTemp;
					}
				}
			}
		}
		
		if(!$purl) {
			$purl = $myPurl;
			$pid = $myPid;
			$proPos = $proPosTemp;
			$myTitle = $myTitleTemp;
			$pageInfo = $proInfo;
		}
		
		if($curl == "data") {

			if($proPos == 1)
				$pageTitle = "Data00";
			else {
				$proPos--;
				if($proPos < 10) {
					$proPos = "0". $proPos;
				} else {
					$proPos = $proPos;
				}
				$pageTitle = "Data" . $proPos;
			}
			$htmlTitle = "Data" .$myTitle;

		} else {

			// build page title
			if($catPos < 10) {
				$catPos = "0". $catPos;
			} else {
				$catPos = $catPos;
			}
			if($proPos < 10) {
				$proPos = "0". $proPos;
			} else {
				$proPos = $proPos;
			}
			
			$pageTitle = $catPos . "." . $proPos;
			
			$htmlTitle = "Lamosca . " .$myTitle;

		}
		
		if($content[0] == "") {
			$pagenotfound = true;
		}

	} 
	
	if(!$curl || $pagenotfound) {
	
		 $sqlstr = "SELECT 
					 id,title,active,text_1 
					 FROM $projecttable 
					 WHERE id = 1";
		 $erg = mysql_db_query($dbname, $sqlstr);
		 $row=mysql_fetch_row($erg);
		 $active = $row[2];
		 $pageTitle = $row[1];
		 $pageInfo = $row[3];
		 $htmlTitle = "Lamosca";
		 
	}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
 <meta http-equiv="content-type" content="text/html; charset=utf-8" />
 <meta name="verify-v1" content="XBN0LvyQZSjPPjPcHpzYKowaSzrepHE/DxNs8KvPf1o=" />
 <title><?php echo $htmlTitle?></title>
<?php include("phpincludes/meta_tags.php"); ?>

 <link href="/css/basic.css" rel="stylesheet" type="text/css" media="screen" />
 <link rel="shortcut icon" href="/favicon.ico" />
 <script type="text/javascript" src="/js/swfobject.js"></script>
 <script type="text/javascript" src="/js/navigation.js"></script>
 <script type="text/javascript" src="/js/lamosca.js"></script>
<?php if($serv == 11) { ?>
 <script type="text/javascript" src="/~thomas/Lamosca/js/swfobject.js"></script>
 <script type="text/javascript" src="/~thomas/Lamosca/js/navigation.js"></script>
 <script type="text/javascript" src="/~thomas/Lamosca/js/lamosca.js"></script>
 <link href="/~thomas/Lamosca/css/basic.css" rel="stylesheet" type="text/css" media="screen" />
<?php } ?>
</head>

<body>
<div id="container">
<?php 
	include("phpincludes/navigation.php"); 
?>
 <div id="project">
  <div id="projectinfo">
   <div id="flashtitle">
<?php 
    	echo "    <div class='pageTitle'>".prepHtml($pageTitle)."</div>\n";
?>
    <br />
    <br />
<?php 
    	echo "    <div class='pageInfo'>".prepHtml($pageInfo)."</div>\n"; 

?>
   </div>
<?php 
	$flashJS .= "  var so = new SWFObject(\"".$imgroot."flash/pageTitle.swf\", \"pageTitle\", \"120\", \"350\", \"8\", \"#FFFFFF\");\n";
	$flashJS .= "  so.addVariable(\"pageTitle\", \"".prepFlash($pageTitle)."\");\n";
	$flashJS .= "  so.addVariable(\"pageInfo\", \"".prepFlash($pageInfo)."\");\n";
	$flashJS .= "  so.addParam(\"scale\", \"noscale\");\n";
	$flashJS .= "  so.write(\"flashtitle\");\n\n";

	if(!$purl) {
		echo "   <div class='pageSponsor'><a href='http://www.cdmon.com/' target='_blank'><img src='".$imgroot."index/cdmon2.gif' width='71' height='42' border='0' alt='Patrocinat per Cdmon' /></a></div>\n"; 
	}
?>
  </div>

  <div id="modules">
<?php
		if($curl && $purl && !$pagenotfound) {
			echo buildModules($curl,$purl);
		} else {
			echo buildIndex();
		}
?>
  </div>
 </div>
 
</div>

<script type="text/javascript">
// <![CDATA[
<?php 
	echo "\n".$flashJS; 
?>
// ]]>
</script>

<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-2729520-1";
urchinTracker();
</script></body>

</html>

<?php
	mysql_close($db);				
?>
