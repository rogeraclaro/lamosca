<?php
/* include_once 'roger/pdf/tcpdf/config/lang/bootstrap.php';*/


/*
include 'Mobile_Detect.php';
$detect = new Mobile_Detect();

if ($detect->isMobile()) {
    header('Location: index_mob.php');
    exit(0);
}
*/
//echo $_SERVER['DOCUMENT_ROOT'];

		extract($_POST);
		extract($_GET);

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
<?php
	include("phpincludes/navigation.php");
?>
 <div id="project">
  <div id="projectinfo">
   <div id="flashtitle">
<?php

if(!$purl) {
    	echo "<div class='pageTitle'>
    	<div class='lamosca'>Lamosca</div>
    	<div class='adresa_home'>Barcelona<br>T 654 534 354<br><br><a href='mailto:xavi@lamosca.com'>xavi@lamosca.com</a></div>
    	<div class='adresa_mobile'>Bcn | <!--627 59 58 35 |--> <a href='mailto:info@lamosca.com'>xavi@lamosca.com</a></div>
    	</div>
    	<br /><br />\n";
}
?>
<!-- Pl. universitat 4. 3r 2a<br>08007 Barcelona<br> -->
   </div>
<?php

	if(!$purl) {
		// is index

		/*echo "   <div class='pageFacebook'>\n";
		echo "    <div><a href='http://www.facebook.com/pages/Lamosca/173103592720207' target='_blank'><img src='".$imgroot."index/facebook.gif' width='16' height='16' border='0' alt='' /></a></div>\n";
		echo "    <div id='flashfacebook'>".prepHtml("<a href='http://www.facebook.com/pages/Lamosca/173103592720207' target='_blank'>VISIT US ON FACEBOOK</a>")."</div>\n";
		echo "   </div>\n";

		$flashJS .= "  var so = new SWFObject(\"".$imgroot."flash/pageTitle.swf\", \"pageTitle\", \"120\", \"240\", \"8\", \"#FFFFFF\");\n";
		$flashJS .= "  so.addVariable(\"pageTitle\", \"".prepFlash($pageTitle)."\");\n";
		$flashJS .= "  so.addVariable(\"pageInfo\", \"".prepFlash($pageInfo)."\");\n";
		$flashJS .= "  so.addParam(\"scale\", \"noscale\");\n";
		$flashJS .= "  so.write(\"flashtitle\");\n\n";

		$flashJS .= "  var so = new SWFObject(\"".$imgroot."flash/indexTitle.swf\", \"pageFacebook\", \"120\", \"13\", \"8\", \"#FFFFFF\");\n";
		$flashJS .= "  so.addVariable(\"text_1\", \"".prepFlash("<a href='http://www.facebook.com/pages/Lamosca/173103592720207' target='_blank'>VISIT US ON FACEBOOK</a>")."\");\n";
		$flashJS .= "  so.addParam(\"scale\", \"noscale\");\n";
		$flashJS .= "  so.write(\"flashfacebook\");\n\n";
		*/


	} else {
	/*
	   $flashJS .= "  var so = new SWFObject(\"".$imgroot."flash/pageTitle.swf\", \"pageTitle\", \"120\", \"350\", \"8\", \"#FFFFFF\");\n";
	   $flashJS .= "  so.addVariable(\"pageTitle\", \"".prepFlash($pageTitle)."\");\n";
	   $flashJS .= "  so.addVariable(\"pageInfo\", \"".prepFlash($pageInfo)."\");\n";
	   $flashJS .= "  so.addParam(\"scale\", \"noscale\");\n";
	   $flashJS .= "  so.write(\"flashtitle\");\n\n";
	   */

	   echo "
	   <div class='pageTitle'>
	   <div class='lamosca'>".prepHtml($pageTitle)."</div>
	   <div class='adresa'>".prepHtml($pageInfo)."</div>
	   </div>\n";
	}


	if(!$purl) {
		echo "   <div class='pageSponsor' style='line-height: 0px; text-align: right; font-weight: 300;'><a href='https://www.cdmon.com/' target='_blank'><img src='".$imgroot."index/cdmon.jpg' width='200' height='45' border='0' alt='Patrocinat per Cdmon' />Hosting by <strong>cdmon</strong></a></div>\n";
	}
?>
  </div>

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

<script type="text/javascript">
// <![CDATA[
<?php
	echo "\n".$flashJS;
?>
// ]]>
</script>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-2729520-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-2729520-1');
</script>
</body>

</html>

<?php
	mysql_close($db);
?>
