<?php

	require '../phpincludes/functions.php';
	$db = mysql_connect($db_host, $db_user, $db_password) or die("Abort: Connection to '$db_host' not possible.");

	$pageTitle = "Mosaic";
	$pageInfo = "MOSAIC IS AN EXAMPLE OF COMMUNINTY AND AT THE SAME TIME AN IMAGE. A GRAPHIC PIECE, GENERATED THANKS TO YOUR PARICIPATION";
	
	if(isset($nom)) {
	
		$db = mysql_connect($db_host, $db_user, $db_password) or die("Could not connect: " . mysql_error());
		
		$sqlstr = "SELECT id FROM $mosaictable WHERE nom = '".addslashes($nom)."' AND comentari = '".addslashes($comentari)."' ";
		$erg = mysql_db_query($dbname, $sqlstr);
		
		$rows=mysql_num_rows($erg);

		if($rows < 1) {
			if(isset($color1))
				$color = 3;
			else if(isset($color2))
				$color = 4;
			else if(isset($color3))
				$color = 1;
			else if(isset($color4))
				$color = 0;
			else if(isset($color5))
				$color = 2;
			$web = "http://" . $web;
			$web = str_replace("http://http://","http://",$web);
			
			$db = mysql_connect($db_host, $db_user, $db_password) or die("Could not connect: " . mysql_error());
			$sqlstr = "INSERT $mosaictable VALUES ";
			$sqlstr .= "('', '".addslashes($nom)."', '".addslashes($mail)."', '".addslashes($web)."', '" . addslashes($comentari) . "', $color, '". time() ."')";
			$erg = mysql_db_query($dbname, $sqlstr);
			mysql_close($db);
		}
	}

	function buildMosaic() {
	
		global $dbname;
		$mosaictable="mosaic";	
				
		$sqlstr = "SELECT id, color FROM $mosaictable ORDER BY data ASC";
		$erg = mysql_db_query($dbname, $sqlstr);
		
		$columns = 56;
		$counter = 1;
		
		$mytable = "<table cellspacing='0' cellpadding='0' border='0'>";
		while($row=mysql_fetch_row($erg)) {
						
			if($counter == 1)
				$mytable .= "<tr>";
				
			$mytable .= "<td><a href='#' onClick='tw(" . $row[0] . ")'><img src='0" . $row[1] . ".gif' border='0' alt='' width='6' height='6' /></a></td>";
			
			if($counter == $columns) {
				$mytable .= "</tr>\n\r";
				$counter = 1;
			} else {
				$counter++;
			}
		}
		if($counter != 1) {
			for($i=$counter; $i <= $columns; $i++) {
				$mytable .= "<td></td>";
				if($i == $columns) 
					$mytable .= "</tr>\n\r";
			}
		}
		$mytable .= "</table>";
		
		echo $mytable;
	}
	


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
 <meta http-equiv="content-type" content="text/html; charset=utf-8" />
 <title>Lamosca . Mosaic</title>
<?php include("../phpincludes/meta_tags.php"); ?>
 <link href="/css/basic.css" rel="stylesheet" type="text/css" media="screen" />
 <link href="mosaic.css" rel="stylesheet" type="text/css" media="screen" />
 <link rel="shortcut icon" href="favicon.ico" />
 <script type="text/javascript" src="/js/swfobject.js"></script>
 <script type="text/javascript" src="/js/navigation.js"></script>
 <script type="text/javascript" src="/js/mosaic.js"></script>
</head>

<body>
<div id="container">

<?php include("../phpincludes/navigation.php"); ?>
 
 <div id="project">
 
  <div id="projectinfo">
   <div id="flashtitle">
		<?php echo $pageTitle; ?>
   </div>
   <script type="text/javascript">
		// <![CDATA[
			var so = new SWFObject("../img/flash/pageTitle.swf", "pageTitle", "120", "350", "6", "#FFFFFF");
			so.addVariable("pageTitle", "<?php echo $pageTitle; ?>");
			so.addVariable("pageInfo", "<?php echo prepFlash($pageInfo); ?>");
			so.addParam("scale", "noscale");
			so.write("flashtitle");
		// ]]>
	</script>
  </div>
  
  <div id="modules">

  <table border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td align="left">

          <form name="form1" method="post" action="mosaic.php"  onsubmit="return checkForm()">

            <table width="450" border="0" cellspacing="0" cellpadding="0">
              <tr valign="bottom">
                <td height="22"><img src="img/name.gif" alt="" width="220" height="20" /></td>
                <td height="22">&nbsp;</td>
                <td height="22" align="left"><img src="img/coment.gif" alt="" width="220" height="20" /></td>
              </tr>

              <tr valign="top">
                <td width="220">
                  <input type="text" name="nom" style="font-size: 7pt; width : 220px; font-family: verdana, helvetica, sans-serif;border : 1px solid Black " tabindex="1" />
                </td>
                <td width="10">&nbsp;</td>
                <td rowspan="3" align="left">
                  <textarea name="comentari" style="font-size: 7pt; width : 218px; font-family: verdana, helvetica, sans-serif;border : 1px solid Black " rows="4" tabindex="4"></textarea>
                </td>
              </tr>

              <tr valign="top">
                <td width="220"><img src="img/mail.gif" alt="" width="220" height="19" /></td>
                <td width="10">&nbsp;</td>
              </tr>

              <tr valign="top">
                <td width="220">
                  <input type="text" name="mail" style="font-size: 7pt; width : 220px; font-family: verdana, helvetica, sans-serif;border : 1px solid Black " tabindex="2" />
                </td>
                <td width="10">&nbsp;</td>
              </tr>

              <tr valign="top">
                <td width="220"><img src="img/web.gif" alt="" width="220" height="19" /></td>
                <td width="10">&nbsp;</td>
                <td width="220" align="left"><img src="img/color.gif" alt="" width="220" height="19" /></td>
              </tr>

              <tr valign="top">
                <td width="220">
                  <input type="text" name="web" style="font-size: 7pt; width : 220px; font-family: verdana, helvetica, sans-serif;border : 1px solid Black " value="http://" tabindex="3" />
                </td>
                <td width="10">&nbsp;</td>
                <td width="220" align="left">

                  <table style="width:219px; height:13px" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td align="center" bgcolor="#F1BB00" height="18" width="24">
                        <input type="checkbox" name="color1" value="1" onClick="mark(this.value)" tabindex="5" />
                      </td>
                      <td width="1" height="18">&nbsp;</td>
                      <td align="center" bgcolor="#E2E0CD" height="18" width="24">
                        <input type="checkbox" name="color2" value="2" onClick="mark(this.value)" tabindex="6" />
                      </td>
                      <td width="1" height="18">&nbsp;</td>
                      <td align="center" bgcolor="#938156" height="18" width="24">
                        <input type="checkbox" name="color3" value="3" onClick="mark(this.value)" tabindex="7" />
                      </td>
                      <td width="1" height="18">&nbsp;</td>
                      <td align="center" bgcolor="#D58137" height="18" width="24">
                        <input type="checkbox" name="color4" value="4" onClick="mark(this.value)" tabindex="8" />
                      </td>
                      <td width="1" height="18">&nbsp;</td>
                      <td align="center" bgcolor="#763D12" height="18" width="24">
                        <input type="checkbox" name="color5" value="5" onClick="mark(this.value)" tabindex="9" />
                      </td>
                      <td width="1" height="18">&nbsp;</td>
                      <td align="right" width="94" valign="bottom" height="18">
                        <input type="image" tabindex="10" name="submit" src="img/ok.gif" />
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>

              <tr>
                <td width="220"><img src="img/separa.gif" alt="" width="220" height="19" /></td>
                <td width="10">&nbsp;</td>
                <td width="220" align="left"><img src="img/separa.gif" alt="" width="220" height="19" /></td>
              </tr>
            </table>
            
          </form>
        </td>
      </tr>

      <tr>
       <td align="left" valign="top">
		<?php
			buildMosaic();
		?>
        </td>
      </tr>
    </table>
    

  </div>
 </div>
 
</div>

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
