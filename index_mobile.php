<?php

	require 'phpincludes/functions.php';
 include_once $_SERVER['DOCUMENT_ROOT'].'/roger/fum/wp-includes/js/tinymce/plugins/spellchecker/classes/functions.php';
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
		
		$htmlTitle = "Lamosca, graphic design . " .$myTitle;
	
		if($content[0] == "") {
			$pagenotfound = true;
		}

	} 
	
	if(!$curl || $pagenotfound) {
	
		 $sqlstr = "SELECT 
					 id,title,active,text_1,text_2 
					 FROM $projecttable 
					 WHERE id = 1";
		 $erg = mysql_db_query($dbname, $sqlstr);
		 $row=mysql_fetch_row($erg);
		 $active = $row[2];
		 $pageTitle = $row[1];
		 $pageInfo = $row[3];
		 $homeDestacat = $row[4];
		 $htmlTitle = "Lamosca, graphic design";
		 
	}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
 <meta http-equiv="content-type" content="text/html; charset=utf-8" />
 <meta name="verify-v1" content="XBN0LvyQZSjPPjPcHpzYKowaSzrepHE/DxNs8KvPf1o=" />
 <title><?php echo $htmlTitle?></title>
<?php include("phpincludes/meta_tags.php"); ?>

<?php if($serv == 1) { ?>
 <link href="/css/basic.css" rel="stylesheet" type="text/css" media="screen" />
 <link rel="shortcut icon" href="/favicon.ico" />
 <script type="text/javascript" src="/js/swfobject.js"></script>
 <script type="text/javascript" src="/js/player.js"></script>
 <script type="text/javascript" src="/js/navigation.js"></script>
 <script type="text/javascript" src="/js/lamosca.js"></script>
 <link href="/rss.xml" rel="alternate" type="application/rss+xml" title="LAMOSCA RSS feed" />
<?php 	} else { ?>
 <link href="/~thomas/lamosca/css/basic.css" rel="stylesheet" type="text/css" media="screen" />
 <script type="text/javascript" src="/~thomas/lamosca/js/swfobject.js"></script>
 <script type="text/javascript" src="/~thomas/lamosca/js/player.js"></script>
 <script type="text/javascript" src="/~thomas/lamosca/js/navigation.js"></script>
 <script type="text/javascript" src="/~thomas/lamosca/js/lamosca.js"></script>
 <link href="/~thomas/lamosca/rss.xml" rel="alternate" type="application/rss+xml" title="LAMOSCA RSS feed" />
<?php } ?>
<style type="text/css">
img {
	max-width: 100%;
}
</style>
<!--[if IE]><style type="text/css">
img,
p {
	/* width: 100%; */
}
</style><![endif]-->
</head>

<body>
<div id="container">

 <div id="project">

  <div id="modules">
   <div id="wrap">
<?php
		if($curl && $purl && !$pagenotfound) {
			echo buildModules($curl,$purl);
		} else {
			$purl = "index";
			//$curl = "index";
			echo buildModules($curl,$purl,$homeDestacat);
			//echo buildIndex();
		}
?>
   </div>
  </div>
 </div>
</div>
<!--<div id='adresa_peu'>Barcelona<br>Pl. universitat 4. 3r 2a<br>08007 Barcelona<br>T 934 410 100<br><a href='mailto:info@lamosca.com'>info@lamosca.com</a></div>-->

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